<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmbeddingService
{
	/**
	 * Hugging Face Inference endpoint for feature extraction using multilingual-e5-large.
	 */
	private const ENDPOINT = 'https://router.huggingface.co/hf-inference/models/intfloat/multilingual-e5-large/pipeline/feature-extraction';

	/**
	 * Generate embedding(s) for the provided input text(s).
	 *
	 * - If $inputs is a string, returns a single embedding vector (array of floats)
	 * - If $inputs is an array of strings, returns an array of embedding vectors
	 *
	 * @param string|array $inputs
	 * @param array|null $context Unused placeholder for future query/context patterns (kept for compatibility)
	 * @param bool $normalize Whether to L2-normalize the returned vector(s)
	 * @return array
	 * @throws \RuntimeException on configuration or response shape errors
	 */
	public function embed(string|array $inputs, ?array $context = null, bool $normalize = true): array
	{
		$token = env('EMBEDDING_TOKEN');
		if ($token === '') {
			throw new \RuntimeException('EMBEDDING_TOKEN is not set in the environment.');
		}

		// Determine SSL verification option similar to other services
		$caBundle = env('CURL_CA_BUNDLE') ?: env('SSL_CERT_FILE') ?: env('HTTP_CA_BUNDLE');
		$verifyOption = false;
		if (!empty($caBundle) && is_string($caBundle) && file_exists($caBundle)) {
			$verifyOption = $caBundle;
		}

		$payloadInputs = is_array($inputs)
			? array_values(array_filter($inputs, static fn($t) => is_string($t) && trim($t) !== ''))
			: $inputs;

		if ((is_array($payloadInputs) && empty($payloadInputs)) || (!is_array($payloadInputs) && trim((string) $payloadInputs) === '')) {
			return is_array($inputs) ? [] : [];
		}

		$response = Http::timeout(30)
			->withOptions(['verify' => false])
			->withToken($token)
			->acceptJson()
			->post(self::ENDPOINT, [
				'inputs' => $payloadInputs,
			]);

		if (!$response->ok()) {
			Log::warning('Embedding API request failed', [
				'endpoint' => self::ENDPOINT,
				'status' => $response->status(),
				'body' => $response->body(),
			]);
			throw new \RuntimeException('Failed to get embedding from Hugging Face (HTTP ' . $response->status() . ').');
		}

		$body = $response->json();

		// When sending a batch, the API typically returns a list aligned with inputs
		if (is_array($inputs)) {
			if (!is_array($body)) {
				throw new \RuntimeException('Unexpected embedding response format for batch input.');
			}
			$results = [];
			foreach ($body as $idx => $item) {
				$vector = $this->toSentenceVector($item);
				$results[] = $normalize ? $this->l2Normalize($vector) : $vector;
			}
			return $results;
		}

		// Single input
		$vector = $this->toSentenceVector($body);
		return $normalize ? $this->l2Normalize($vector) : $vector;
	}

	/**
	 * Convert raw HF feature-extraction output to a single sentence vector.
	 * The API may return either:
	 * - 1D array [dim]
	 * - 2D array [tokens][dim] -> we mean-pool across tokens
	 */
	private function toSentenceVector(mixed $embedding): array
	{
		if (!is_array($embedding) || empty($embedding)) {
			throw new \RuntimeException('Empty or invalid embedding response.');
		}

		$firstKey = array_key_first($embedding);
		$firstVal = $embedding[$firstKey];

		// 1D already pooled
		if (is_numeric($firstVal)) {
			return array_map(static fn($v) => (float) $v, $embedding);
		}

		// 2D: mean-pool over token axis
		if (is_array($firstVal)) {
			return $this->meanPool($embedding);
		}

		throw new \RuntimeException('Unrecognized embedding shape.');
	}

	/**
	 * Mean pooling across token embeddings.
	 * @param array $tokenEmbeddings [tokens][dim]
	 */
	private function meanPool(array $tokenEmbeddings): array
	{
		$tokenCount = count($tokenEmbeddings);
		if ($tokenCount === 0) {
			return [];
		}

		$dim = is_array($tokenEmbeddings[0]) ? count($tokenEmbeddings[0]) : 0;
		if ($dim === 0) {
			return [];
		}

		$sums = array_fill(0, $dim, 0.0);
		foreach ($tokenEmbeddings as $tokenVector) {
			for ($i = 0; $i < $dim; $i++) {
				$sums[$i] += (float) ($tokenVector[$i] ?? 0.0);
			}
		}

		for ($i = 0; $i < $dim; $i++) {
			$sums[$i] /= max(1, $tokenCount);
		}

		return $sums;
	}

	/**
	 * L2-normalize a vector.
	 */
	private function l2Normalize(array $vector): array
	{
		$norm = 0.0;
		foreach ($vector as $v) {
			$norm += ((float) $v) * ((float) $v);
		}
		$norm = sqrt($norm);
		if ($norm <= 0.0) {
			return $vector;
		}
		return array_map(static fn($v) => (float) $v / $norm, $vector);
	}
}


