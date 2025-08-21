<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OpenAiService
{
    protected $apiKey;
    protected $baseUrl = 'https://openrouter.ai/api/v1';
    protected static $circuitBreakerKey = 'openai_service_circuit_breaker';
    protected static $failureThreshold = 5; // Number of failures before opening circuit
    protected static $recoveryTimeout = 300; // 5 minutes

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Check if circuit breaker is open (service is down)
     */
    protected function isCircuitBreakerOpen(): bool
    {
        $failureData = cache()->get(self::$circuitBreakerKey);

        if (!$failureData) {
            return false;
        }

        // If we've hit the failure threshold and haven't passed recovery timeout
        if (
            $failureData['count'] >= self::$failureThreshold &&
            time() - $failureData['last_failure'] < self::$recoveryTimeout
        ) {
            return true;
        }

        // Reset if recovery timeout has passed
        if (time() - $failureData['last_failure'] >= self::$recoveryTimeout) {
            cache()->forget(self::$circuitBreakerKey);
        }

        return false;
    }

    /**
     * Record a failure in the circuit breaker
     */
    protected function recordFailure(): void
    {
        $failureData = cache()->get(self::$circuitBreakerKey, ['count' => 0, 'last_failure' => time()]);
        $failureData['count']++;
        $failureData['last_failure'] = time();

        cache()->put(self::$circuitBreakerKey, $failureData, self::$recoveryTimeout * 2);
    }

    /**
     * Record a success in the circuit breaker (reset failures)
     */
    protected function recordSuccess(): void
    {
        cache()->forget(self::$circuitBreakerKey);
    }

    /**
     * Get recipe suggestions based on ingredients using site's recipe database
     */
    public function getRecipeSuggestions($prompt)
    {
        try {
            Log::info('OpenRouter recipe suggestions', [
                'prompt' => $prompt
            ]);

            return $this->recommendFromSite($prompt);
        } catch (Exception $e) {
            Log::error('OpenRouter recipe suggestions error', [
                'message' => $e->getMessage(),
                'prompt' => $prompt
            ]);

            return [
                'success' => false,
                'error' => 'Không thể tạo gợi ý công thức. Vui lòng thử lại.'
            ];
        }
    }

    /**
     * Recommend recipes from site's current database using semantic similarity.
     * Flow: 1) Embed query 2) Vector similarity against Recipe.embeddings 3) Ask LLM to personalize.
     */
    public function recommendFromSite(string $prompt): array
    {
        try {
            // Step 1: Embed the query
            /** @var EmbeddingService $embeddingService */
            $embeddingService = app(EmbeddingService::class);
            $queryVector = $embeddingService->embed($prompt);
            if (!is_array($queryVector) || empty($queryVector)) {
                return [
                    'success' => false,
                    'error' => 'Không thể tạo embedding cho truy vấn.'
                ];
            }

            // Step 2: Search DB by vector similarity (cosine)
            $candidates = Recipe::query()
                ->where('status', 'approved')
                ->whereNotNull('embedding')
                ->select(['id', 'title', 'summary', 'slug', 'embedding', 'featured_image', 'cooking_time', 'difficulty'])
                ->get();

            $scored = [];
            foreach ($candidates as $recipe) {
                $vec = is_array($recipe->embedding) ? $recipe->embedding : [];
                if (empty($vec)) {
                    continue;
                }
                $score = $this->cosineSimilarity($queryVector, $vec);
                if ($score > 0) {
                    $scored[] = [
                        'id' => $recipe->id,
                        'title' => $recipe->title,
                        'summary' => $recipe->summary,
                        'slug' => $recipe->slug,
                        'featured_image' => $recipe->featured_image,
                        'cooking_time' => $recipe->cooking_time,
                        'difficulty_level' => $recipe->difficulty_level,
                        'similarity' => round($score, 6),
                    ];
                }
            }

            if (empty($scored)) {
                return [
                    'success' => false,
                    'error' => 'Không tìm thấy công thức phù hợp.'
                ];
            }

            usort($scored, function ($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });
            $top = array_slice($scored, 0, max(3, 5));

            // Step 3: Send to LLM for personalized recommendation
            $systemMessage = [
                'role' => 'system',
                'content' => 'Bạn là trợ lý ẩm thực của website. Chỉ đề xuất các công thức trong danh sách CONTEXT bên dưới. Trả lời bằng tiếng Việt, ngắn gọn, thân thiện. Khi đề xuất công thức, hãy nhắc tên cụ thể của từng món ăn để người dùng dễ hiểu.'
            ];

            $contextJson = json_encode($top, JSON_UNESCAPED_UNICODE);
            $userMessage = [
                'role' => 'user',
                'content' => "Yêu cầu: {$prompt}\n\nCONTEXT (các công thức khả dụng): {$contextJson}\n\nChọn ra từ 1 đến 5 công thức phù hợp nhất trong CONTEXT, giải thích ngắn lý do phù hợp. Hãy nhắc tên cụ thể của từng món ăn được đề xuất."
            ];

            $response = $this->sendMessageWithMessages([$systemMessage, $userMessage]);
            if (($response['success'] ?? false) === true) {
                // Parse the AI response to extract only the suggested recipes
                $suggestedRecipes = $this->extractSuggestedRecipes($response['message'], $top);

                // Attach only the suggested recipes, not all candidates
                if (!empty($suggestedRecipes)) {
                    $response['recipes'] = $suggestedRecipes;
                }
            }
            return $response;
        } catch (Exception $e) {
            Log::error('Recommendation flow failed', [
                'message' => $e->getMessage(),
                'query' => $prompt,
            ]);
            return [
                'success' => false,
                'error' => 'Không thể tạo gợi ý dựa trên dữ liệu trang. Vui lòng thử lại.'
            ];
        }
    }

    /**
     * Extract suggested recipes from AI response by matching recipe titles
     */
    private function extractSuggestedRecipes(string $aiResponse, array $candidates): array
    {
        $suggestedRecipes = [];

        // Convert AI response to lowercase for better matching
        $responseLower = strtolower($aiResponse);

        Log::info('Extracting suggested recipes', [
            'ai_response_length' => strlen($aiResponse),
            'candidates_count' => count($candidates),
            'response_preview' => substr($aiResponse, 0, 200) . '...'
        ]);

        // First, try exact title matching
        foreach ($candidates as $recipe) {
            $titleLower = strtolower($recipe['title']);

            // Check if the recipe title appears in the AI response
            if (str_contains($responseLower, $titleLower)) {
                $suggestedRecipes[] = $recipe;
                Log::info('Exact match found', ['recipe_title' => $recipe['title']]);
            }
        }

        // If no exact matches found, try partial matching
        if (empty($suggestedRecipes)) {
            Log::info('No exact matches found, trying partial matching');
            foreach ($candidates as $recipe) {
                $titleWords = explode(' ', strtolower($recipe['title']));
                $matchCount = 0;

                foreach ($titleWords as $word) {
                    if (strlen($word) > 2 && str_contains($responseLower, $word)) {
                        $matchCount++;
                    }
                }

                // If more than 50% of words match, consider it suggested
                if ($matchCount > 0 && ($matchCount / count($titleWords)) > 0.5) {
                    $suggestedRecipes[] = $recipe;
                    Log::info('Partial match found', [
                        'recipe_title' => $recipe['title'],
                        'match_count' => $matchCount,
                        'total_words' => count($titleWords),
                        'match_ratio' => $matchCount / count($titleWords)
                    ]);
                }
            }
        }

        // If still no matches, check if the AI response contains recipe-related keywords
        if (empty($suggestedRecipes)) {
            Log::info('No title matches found, checking for recipe-related keywords');
            $recipeKeywords = ['công thức', 'món ăn', 'nấu', 'chế biến', 'gợi ý', 'đề xuất'];
            $hasRecipeKeywords = false;

            foreach ($recipeKeywords as $keyword) {
                if (str_contains($responseLower, $keyword)) {
                    $hasRecipeKeywords = true;
                    break;
                }
            }

            // If AI mentions recipes but no specific titles, take top 3 candidates
            if ($hasRecipeKeywords) {
                $suggestedRecipes = array_slice($candidates, 0, 3);
                Log::info('Taking top candidates based on recipe keywords', [
                    'suggested_titles' => array_column($suggestedRecipes, 'title')
                ]);
            }
        }

        Log::info('Recipe extraction complete', [
            'suggested_count' => count($suggestedRecipes),
            'suggested_titles' => array_column($suggestedRecipes, 'title')
        ]);

        // Limit to maximum 5 recipes
        return array_slice($suggestedRecipes, 0, 5);
    }

    /**
     * Send chat with full messages array, reusing circuit breaker and retry logic.
     */
    private function sendMessageWithMessages(array $messages)
    {
        try {
            if ($this->isCircuitBreakerOpen()) {
                Log::warning('OpenRouter service circuit breaker is open');
                return [
                    'success' => false,
                    'error' => 'Dịch vụ AI hiện tại không khả dụng do quá nhiều lỗi. Vui lòng thử lại sau 5 phút.'
                ];
            }

            if (function_exists('ini_set')) {
                ini_set('memory_limit', '256M');
            }

            if (!$this->apiKey) {
                return [
                    'success' => false,
                    'error' => 'OpenRouter API key chưa được cấu hình. Vui lòng kiểm tra cài đặt.'
                ];
            }

            $payload = [
                'model' => 'deepseek/deepseek-chat-v3-0324:free',
                'messages' => $messages,
                'max_tokens' => 300,
                'temperature' => 0.5,
                'top_p' => 0.5,
                'stream' => false,
            ];

            $maxRetries = 3;
            $baseDelay = 1;

            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    $timeout = min(15 + ($attempt * 5), 30);
                    Log::info('OpenRouter API request (custom messages)', [
                        'attempt' => $attempt,
                        'payload' => $payload,
                        'timeout' => $timeout
                    ]);

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                        'HTTP-Referer' => request()->getSchemeAndHttpHost() ?? 'http://localhost',
                        'X-Title' => 'Bee Recipe Assistant',
                    ])->withOptions([
                        'verify' => env('APP_ENV') === 'production' ? true : false,
                        'timeout' => $timeout,
                        'connect_timeout' => 10,
                    ])->timeout($timeout)->post($this->baseUrl . '/chat/completions', $payload);

                    if ($response->successful()) {
                        break;
                    }

                    if ($attempt < $maxRetries && ($response->status() >= 500 || $response->status() === 408)) {
                        $delay = $baseDelay * pow(2, $attempt - 1);
                        Log::warning("OpenRouter API attempt {$attempt} failed, retrying in {$delay}s", [
                            'status' => $response->status(),
                            'attempt' => $attempt
                        ]);
                        sleep($delay);
                        continue;
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    Log::warning("OpenRouter connection error on attempt {$attempt}", [
                        'message' => $e->getMessage(),
                        'attempt' => $attempt
                    ]);

                    if ($attempt < $maxRetries) {
                        $delay = $baseDelay * pow(2, $attempt - 1);
                        sleep($delay);
                        continue;
                    }

                    throw $e;
                }
            }

            if ($response->successful()) {
                $data = $response->json();
                Log::info('OpenRouter API data (custom messages)', [
                    'data' => $data
                ]);

                $messageContent = $data['choices'][0]['message']['content'] ?? '';
                if (empty($messageContent) && isset($data['choices'][0]['message']['reasoning'])) {
                    $messageContent = $data['choices'][0]['message']['reasoning'];
                }
                if (empty($messageContent)) {
                    $messageContent = 'Xin lỗi, tôi không thể trả lời câu hỏi này.';
                }

                return [
                    'success' => true,
                    'message' => $messageContent,
                    'usage' => $data['usage'] ?? []
                ];
            }

            $errorData = $response->json();
            $errorMessage = $this->getErrorMessage($errorData);

            Log::error('OpenRouter API error (custom messages)', [
                'status' => $response->status(),
                'response' => $errorData
            ]);

            return [
                'success' => false,
                'error' => $errorMessage
            ];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('OpenRouter connection error (custom messages)', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'timed out')) {
                return [
                    'success' => false,
                    'error' => 'Kết nối với AI bị timeout. Hệ thống đang quá tải, vui lòng thử lại sau ít phút.'
                ];
            }

            return [
                'success' => false,
                'error' => 'Không thể kết nối với dịch vụ AI. Vui lòng kiểm tra kết nối mạng và thử lại.'
            ];
        } catch (Exception $e) {
            Log::error('OpenRouter service error (custom messages)', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Có lỗi xảy ra khi kết nối với AI. Vui lòng thử lại sau.'
            ];
        }
    }

    /**
     * Parse error messages from exceptions
     */
    private function parseErrorMessage(string $errorMessage): string
    {
        if (str_contains($errorMessage, 'insufficient_quota') || str_contains($errorMessage, 'quota_exceeded')) {
            return 'API đã hết quota. Vui lòng thử lại sau hoặc liên hệ admin để nâng cấp.';
        }

        if (str_contains($errorMessage, 'invalid_api_key')) {
            return 'API key không hợp lệ. Vui lòng liên hệ admin.';
        }

        if (str_contains($errorMessage, 'rate_limit_exceeded')) {
            return 'Đã vượt quá giới hạn request. Vui lòng thử lại sau ít phút.';
        }

        if (str_contains($errorMessage, 'model_not_found')) {
            return 'Model AI không khả dụng. Vui lòng thử lại sau.';
        }

        return 'Có lỗi xảy ra khi kết nối với AI. Vui lòng thử lại sau.';
    }

    /**
     * Handle API error messages
     */
    private function getErrorMessage($errorData): string
    {
        if (isset($errorData['error']['code'])) {
            switch ($errorData['error']['code']) {
                case 'insufficient_quota':
                case 'quota_exceeded':
                    return 'API đã hết quota. Vui lòng thử lại sau hoặc liên hệ admin để nâng cấp.';

                case 'invalid_api_key':
                    return 'API key không hợp lệ. Vui lòng liên hệ admin.';

                case 'rate_limit_exceeded':
                    return 'Đã vượt quá giới hạn request. Vui lòng thử lại sau ít phút.';

                case 'model_not_found':
                    return 'Model AI không khả dụng. Vui lòng thử lại sau.';

                case 400:
                    // Handle specific 400 errors
                    if (isset($errorData['error']['message'])) {
                        if (str_contains($errorData['error']['message'], 'Expected object, received boolean')) {
                            return 'Lỗi cấu hình API. Vui lòng liên hệ admin để kiểm tra cài đặt.';
                        }
                        if (str_contains($errorData['error']['message'], 'validation')) {
                            return 'Dữ liệu gửi đến AI không hợp lệ. Vui lòng thử lại.';
                        }
                    }
                    return 'Yêu cầu không hợp lệ. Vui lòng kiểm tra thông tin và thử lại.';

                default:
                    return $errorData['error']['message'] ?? 'Có lỗi xảy ra với dịch vụ AI.';
            }
        }

        // Handle cases where error structure is different
        if (isset($errorData['error']['message'])) {
            $message = $errorData['error']['message'];

            // Check for specific error patterns
            if (str_contains($message, 'Expected object, received boolean')) {
                return 'Lỗi cấu hình API. Vui lòng liên hệ admin để kiểm tra cài đặt.';
            }

            if (str_contains($message, 'validation')) {
                return 'Dữ liệu gửi đến AI không hợp lệ. Vui lòng thử lại.';
            }

            return $message;
        }

        return 'Không thể kết nối với dịch vụ AI. Vui lòng thử lại.';
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get available models (for future expansion)
     */
    public function getAvailableModels(): array
    {
        return [
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
            'gpt-4' => 'GPT-4',
            'gpt-4-turbo' => 'GPT-4 Turbo'
        ];
    }

    /**
     * Compute cosine similarity between two vectors.
     */
    private function cosineSimilarity(array $a, array $b): float
    {
        if (empty($a) || empty($b)) {
            return 0.0;
        }
        $len = min(count($a), count($b));
        if ($len === 0) {
            return 0.0;
        }
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        for ($i = 0; $i < $len; $i++) {
            $va = (float) $a[$i];
            $vb = (float) $b[$i];
            $dot += $va * $vb;
            $normA += $va * $va;
            $normB += $vb * $vb;
        }
        if ($normA <= 0.0 || $normB <= 0.0) {
            return 0.0;
        }
        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
