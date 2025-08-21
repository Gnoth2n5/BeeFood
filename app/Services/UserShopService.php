<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserShop;
use App\Models\ShopItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserShopService
{
    public function createOrUpdate(User $user, array $data): UserShop
    {
        // Get existing shop to preserve current image if no new one is uploaded
        $existingShop = UserShop::where('user_id', $user->id)->first();
        
        // Handle featured image upload
        $featuredImage = $existingShop?->featured_image; // Keep existing image by default
        
        if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
            // Delete old image if exists
            if ($existingShop && $existingShop->featured_image) {
                $this->deleteOldImage($existingShop->featured_image);
            }
            
            // Store new image
            $featuredImage = $this->storeImage($data['featured_image'], $user->id);
        }
        
        // Process items data - prepare array for syncing ShopItem records
        $normalizedItems = [];
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['name'])) {
                    $normalizedItems[] = [
                        'id' => isset($item['id']) ? (int) $item['id'] : null,
                        'name' => trim($item['name']),
                        'price' => isset($item['price']) && $item['price'] !== '' ? (float)$item['price'] : null,
                        'description' => isset($item['description']) ? trim($item['description']) : null,
                        'featured_image' => isset($item['featured_image']) ? $item['featured_image'] : null,
                    ];
                }
            }
        }
        
        // Geocode address to coordinates when needed
        $latitudeFromForm = $data['latitude'] ?? null;
        $longitudeFromForm = $data['longitude'] ?? null;
        $shouldGeocode = !empty($data['address']) && (
            empty($latitudeFromForm) || empty($longitudeFromForm) ||
            ($existingShop && $existingShop->address !== trim($data['address']))
        );
        $geoLat = $latitudeFromForm;
        $geoLng = $longitudeFromForm;
        if ($shouldGeocode) {
            $coords = $this->geocodeAddress(trim($data['address']));
            if ($coords !== null) {
                $geoLat = $geoLat === null || $geoLat === '' ? $coords['lat'] : (float) $geoLat;
                $geoLng = $geoLng === null || $geoLng === '' ? $coords['lng'] : (float) $geoLng;
            }
        }

        // Build shop embedding text and vector
        $shopEmbeddingText = $this->buildShopEmbeddingText(
            name: trim($data['name']),
            description: isset($data['description']) ? trim($data['description']) : null,
            address: isset($data['address']) ? trim($data['address']) : null,
            website: isset($data['website']) ? trim($data['website']) : null,
            phone: isset($data['phone']) ? trim($data['phone']) : null,
            items: $normalizedItems
        );
        // $shopEmbedding = $this->safeEmbed($shopEmbeddingText);

        $payload = [
            'name' => trim($data['name']),
            'slug' => Str::slug($data['name'] . '-' . $user->id),
            'address' => isset($data['address']) ? trim($data['address']) : null,
            'phone' => isset($data['phone']) ? trim($data['phone']) : null,
            'website' => isset($data['website']) ? trim($data['website']) : null,
            'description' => isset($data['description']) ? trim($data['description']) : null,
            'featured_image' => $featuredImage,
            'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : true,
            'latitude' => $geoLat !== null && $geoLat !== '' ? (float) $geoLat : null,
            'longitude' => $geoLng !== null && $geoLng !== '' ? (float) $geoLng : null,
            // 'embedding' => $shopEmbedding,
        ];

        $shop = UserShop::updateOrCreate(
            ['user_id' => $user->id],
            $payload
        );

        // Sync ShopItem records (create/update/delete)
        $existingItemIds = ShopItem::where('user_shop_id', $shop->id)->pluck('id')->all();
        $keptIds = [];
        foreach ($normalizedItems as $itemData) {
            if (!empty($itemData['id'])) {
                // Update existing
                $shopItem = ShopItem::where('user_shop_id', $shop->id)
                    ->where('id', $itemData['id'])
                    ->first();
                if ($shopItem) {
                    // Handle item image upload if provided
                    $itemImagePath = $shopItem->featured_image;
                    if ($itemData['featured_image'] instanceof UploadedFile) {
                        // Delete old image if exists
                        if (!empty($itemImagePath) && Storage::disk('public')->exists($itemImagePath)) {
                            Storage::disk('public')->delete($itemImagePath);
                        }
                        $itemImagePath = $this->storeItemImage($itemData['featured_image'], $shop->id);
                    }

                    $itemEmbeddingText = $this->buildItemEmbeddingText(
                        name: $itemData['name'],
                        description: $itemData['description']
                    );
                    // $itemEmbedding = $this->safeEmbed($itemEmbeddingText);

                    $shopItem->update([
                        'name' => $itemData['name'],
                        'price' => $itemData['price'],
                        'description' => $itemData['description'],
                        'featured_image' => $itemImagePath,
                        // 'embedding' => $itemEmbedding,
                    ]);
                    $keptIds[] = $shopItem->id;
                    continue;
                }
            }
            // Create new
            $newItemImagePath = null;
            if ($itemData['featured_image'] instanceof UploadedFile) {
                $newItemImagePath = $this->storeItemImage($itemData['featured_image'], $shop->id);
            }

            $newItemEmbeddingText = $this->buildItemEmbeddingText(
                name: $itemData['name'],
                description: $itemData['description']
            );
            // $newItemEmbedding = $this->safeEmbed($newItemEmbeddingText);

            $newItem = ShopItem::create([
                'user_shop_id' => $shop->id,
                'name' => $itemData['name'],
                'price' => $itemData['price'],
                'description' => $itemData['description'],
                'featured_image' => $newItemImagePath,
                'is_active' => true,
                'stock_quantity' => 0,
                // 'embedding' => $newItemEmbedding,
            ]);
            $keptIds[] = $newItem->id;
        }

        // Delete items that were removed from the form
        $idsToDelete = array_diff($existingItemIds, $keptIds);
        if (!empty($idsToDelete)) {
            $itemsToDelete = ShopItem::where('user_shop_id', $shop->id)
                ->whereIn('id', $idsToDelete)
                ->get();
            foreach ($itemsToDelete as $item) {
                if (!empty($item->featured_image) && Storage::disk('public')->exists($item->featured_image)) {
                    Storage::disk('public')->delete($item->featured_image);
                }
            }
            ShopItem::where('user_shop_id', $shop->id)->whereIn('id', $idsToDelete)->delete();
        }

        return $shop;
    }

    /**
     * Build representative text for a shop to generate an embedding.
     */
    private function buildShopEmbeddingText(string $name, ?string $description, ?string $address, ?string $website, ?string $phone, array $items): string
    {
        $parts = [];
        if ($name !== '') {
            $parts[] = $name;
        }
        if (!empty($description)) {
            $parts[] = $description;
        }
        if (!empty($address)) {
            $parts[] = 'Address: ' . $address;
        }
        if (!empty($phone)) {
            $parts[] = 'Phone: ' . $phone;
        }
        if (!empty($website)) {
            $parts[] = 'Website: ' . $website;
        }
        // Include a few item names to enrich shop semantics
        $itemNames = [];
        foreach ($items as $item) {
            if (!empty($item['name'])) {
                $itemNames[] = $item['name'];
            }
            if (count($itemNames) >= 10) {
                break;
            }
        }
        if (!empty($itemNames)) {
            $parts[] = 'Items: ' . implode(', ', $itemNames);
        }

        return trim(implode('\n', $parts));
    }

    /**
     * Build representative text for a shop item to generate an embedding.
     */
    private function buildItemEmbeddingText(string $name, ?string $description): string
    {
        $parts = [];
        if ($name !== '') {
            $parts[] = $name;
        }
        if (!empty($description)) {
            $parts[] = $description;
        }
        return trim(implode('\n', $parts));
    }

    /**
     * Safely request an embedding. Returns array on success, null on failure.
     */
    private function safeEmbed(string $text): ?array
    {
        try {
            if ($text === '') {
                return null;
            }
            /** @var EmbeddingService $service */
            $service = app(EmbeddingService::class);
            $vector = $service->embed($text, [$text]);
            // Ensure we only persist arrays of floats
            if (is_array($vector)) {
                return array_map(static fn($v) => (float) $v, $vector);
            }
        } catch (\Throwable $e) {
            Log::warning('Embedding generation failed', [
                'error' => $e->getMessage(),
            ]);
        }
        return null;
    }

    /**
     * Geocode a street address using Nominatim API.
     * Returns ['lat' => float, 'lng' => float] or null on failure.
     */
    private function geocodeAddress(string $address): ?array
    {
        // Determine SSL verification option: use CA bundle path from env if provided, else default true
        $caBundle = env('CURL_CA_BUNDLE') ?: env('SSL_CERT_FILE') ?: env('HTTP_CA_BUNDLE');
        $verifyOption = true;
        if (!empty($caBundle) && is_string($caBundle) && file_exists($caBundle)) {
            $verifyOption = $caBundle;
        }

        // Try multiple geocoding strategies for better results
        $strategies = [
            // Strategy 1: Full address with country restriction
            $address,
            // Strategy 2: Simplified address (remove extra details)
            $this->simplifyVietnameseAddress($address),
            // Strategy 3: Extract city/province only
            $this->extractCityProvince($address),
        ];

        foreach ($strategies as $strategy) {
            if (empty($strategy)) continue;

            try {
                $coordinates = $this->tryGeocode($strategy, $verifyOption);
                if ($coordinates !== null) {
                    Log::info('Address geocoded successfully', [
                        'original_address' => $address,
                        'successful_strategy' => $strategy,
                        'coordinates' => $coordinates
                    ]);
                    return $coordinates;
                }
            } catch (\Exception $e) {
                Log::warning('Geocoding strategy failed', [
                    'strategy' => $strategy,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        Log::warning('All geocoding strategies failed', [
            'address' => $address,
            'strategies_tried' => $strategies
        ]);

        return null;
    }

    /**
     * Try to geocode a single address string
     */
    private function tryGeocode(string $address, $verifyOption): ?array
    {
        $response = Http::timeout(10)
            ->withOptions(['verify' => false])
            ->withHeaders([
                'User-Agent' => 'BeeApp/1.0 (https://yourdomain.com; contact@yourdomain.com)',
                'Accept-Language' => 'vi,en;q=0.9',
            ])
            ->get('https://nominatim.openstreetmap.org/search', [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 1,
                'countrycodes' => 'vn',
            ]);

            Log::info($response);

        if (!$response->ok()) {
            return null;
        }

        $body = $response->json();
        if (!is_array($body) || empty($body) || !isset($body[0]['lat']) || !isset($body[0]['lon'])) {
            return null;
        }

        $location = $body[0];
        return [
            'lat' => isset($location['lat']) ? (float) $location['lat'] : null,
            'lng' => isset($location['lon']) ? (float) $location['lon'] : null,
        ];
    }

    /**
     * Simplify Vietnamese address by removing unnecessary details
     */
    private function simplifyVietnameseAddress(string $address): string
    {
        // Remove common Vietnamese address prefixes and suffixes
        $patterns = [
            '/\b(Xa|Xã|Phuong|Phường|Thon|Thôn|Ap|Ấp|Khu|Khu pho|Khu phố)\s+/i',
            '/\b(Huyen|Huyện|Quan|Quận|Thanh pho|Thành phố|Tinh|Tỉnh)\s+/i',
            '/\s+/', // Multiple spaces to single space
        ];
        
        $simplified = preg_replace($patterns, ' ', $address);
        $simplified = trim($simplified);
        
        // If still too long, take first few words
        $words = explode(' ', $simplified);
        if (count($words) > 4) {
            $simplified = implode(' ', array_slice($words, 0, 4));
        }
        
        return $simplified;
    }

    /**
     * Extract city and province from Vietnamese address
     */
    private function extractCityProvince(string $address): string
    {
        // Look for city/province patterns
        if (preg_match('/(?:Thanh pho|Thành phố|Huyen|Huyện|Tinh|Tỉnh)\s+([A-Za-zÀ-ỹ\s]+)/i', $address, $matches)) {
            return trim($matches[1]);
        }
        
        // Fallback: extract last few words that might be city/province
        $words = explode(' ', trim($address));
        if (count($words) >= 2) {
            return implode(' ', array_slice($words, -2));
        }
        
        return $address;
    }

    /**
     * Store shop item image in shop_items folder
     */
    private function storeItemImage(UploadedFile $file, int $shopId): string
    {
        $directory = 'shop_items';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $filename = 'shop_' . $shopId . '_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'public');
        return $path;
    }

    /**
     * Store shop image in shops folder
     */
    private function storeImage(UploadedFile $file, int $userId): string
    {
        // Create directory if it doesn't exist
        $directory = 'shops';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Generate unique filename
        $filename = 'shop_' . $userId . '_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs($directory, $filename, 'public');
        
        return $path;
    }

    /**
     * Delete old image when updating
     */
    public function deleteOldImage(?string $oldImagePath): void
    {
        if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
        }
    }

    public function getPublicShopBySlug(string $slug): ?UserShop
    {
        return UserShop::where('slug', $slug)->where('is_active', true)->first();
    }
}


