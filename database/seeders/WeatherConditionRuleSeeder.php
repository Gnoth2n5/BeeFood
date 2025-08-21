<?php

namespace Database\Seeders;

use App\Models\WeatherConditionRule;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class WeatherConditionRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if categories and tags exist
        $categories = Category::all();
        $tags = Tag::all();

        if ($categories->isEmpty() || $tags->isEmpty()) {
            $this->command->error('❌ Cần có categories và tags trước khi chạy seeder này!');
            $this->command->info('💡 Hãy chạy: php artisan db:seed --class=CategorySeeder');
            $this->command->info('💡 Sau đó chạy: php artisan db:seed --class=TagSeeder');
            return;
        }

        // Add missing categories if they don't exist
        $this->addMissingCategories();

        // Clear existing weather condition rules
        WeatherConditionRule::truncate();
        $this->command->info('🧹 Đã xóa các quy tắc thời tiết cũ');

        $rules = [
            // === NHIỆT ĐỘ RẤT CAO (>= 35°C) - MÙA HÈ NÓNG BỨC ===
            [
                'name' => 'Nhiệt độ rất cao (>= 35°C)',
                'description' => 'Thời tiết nóng bức, cần món ăn mát lạnh để giải nhiệt',
                'temperature_min' => 35,
                'temperature_max' => null,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Salad', 'Đồ uống', 'Tráng miệng', 'Món mát']),
                'suggested_tags' => $this->getTagIds(['mát', 'lạnh', 'giải nhiệt', 'tươi', 'nhẹ', 'mùa hè']),
                'suggestion_reason' => 'Nhiệt độ rất cao trên 35°C - cần các món ăn mát lạnh, nhẹ để giải nhiệt và bổ sung nước',
                'priority' => 10,
                'is_active' => true,
                'seasonal_rules' => ['summer' => true, 'hot' => true]
            ],

            // === NHIỆT ĐỘ CAO (30-35°C) - MÙA HÈ ===
            [
                'name' => 'Nhiệt độ cao (30-35°C)',
                'description' => 'Thời tiết nóng, cần món ăn mát, nhẹ',
                'temperature_min' => 30,
                'temperature_max' => 35,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Salad', 'Đồ uống', 'Tráng miệng', 'Món mát', 'Súp']),
                'suggested_tags' => $this->getTagIds(['mát', 'nhẹ', 'giải nhiệt', 'tươi', 'mùa hè']),
                'suggestion_reason' => 'Nhiệt độ cao 30-35°C - phù hợp với các món ăn mát, nhẹ để giải nhiệt',
                'priority' => 9,
                'is_active' => true,
                'seasonal_rules' => ['summer' => true, 'hot' => true]
            ],

            // === NHIỆT ĐỘ CAO + ĐỘ ẨM CAO (24-30°C, >70%) - MÙA HÈ ẨM ===
            [
                'name' => 'Nhiệt độ cao độ ẩm cao (24-30°C, >70%)',
                'description' => 'Nhiệt độ 24-30°C và độ ẩm cao >70% - mùa hè ẩm ướt',
                'temperature_min' => 24,
                'temperature_max' => 30,
                'humidity_min' => 70,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Súp', 'Salad', 'Món mát', 'Đồ uống', 'Canh']),
                'suggested_tags' => $this->getTagIds(['mát', 'nhẹ', 'giải nhiệt', 'tươi', 'súp', 'mùa hè']),
                'suggestion_reason' => 'Nhiệt độ cao (24-30°C) và độ ẩm cao (>70%) - gợi ý các món nhẹ như súp và salad để giải nhiệt',
                'priority' => 8,
                'is_active' => true,
                'seasonal_rules' => ['summer' => true, 'humid' => true]
            ],

            // === NHIỆT ĐỘ CAO + ĐỘ ẨM THẤP (24-30°C, <70%) - MÙA HÈ KHÔ ===
            [
                'name' => 'Nhiệt độ cao độ ẩm thấp (24-30°C, <70%)',
                'description' => 'Nhiệt độ 24-30°C và độ ẩm thấp <70% - mùa hè khô',
                'temperature_min' => 24,
                'temperature_max' => 30,
                'humidity_min' => null,
                'humidity_max' => 70,
                'suggested_categories' => $this->getCategoryIds(['Canh', 'Súp', 'Đồ uống', 'Món nước', 'Cháo']),
                'suggested_tags' => $this->getTagIds(['nước', 'canh', 'súp', 'cháo', 'dễ tiêu', 'mùa hè']),
                'suggestion_reason' => 'Nhiệt độ cao (24-30°C) và độ ẩm thấp (<70%) - gợi ý các món nước và món chế biến nhanh',
                'priority' => 7,
                'is_active' => true,
                'seasonal_rules' => ['summer' => true, 'dry' => true]
            ],

            // === NHIỆT ĐỘ MÁT MẺ (15-24°C) - MÙA THU/XUÂN ===
            [
                'name' => 'Nhiệt độ mát mẻ (15-24°C)',
                'description' => 'Thời tiết mát mẻ, dễ chịu - mùa thu hoặc xuân',
                'temperature_min' => 15,
                'temperature_max' => 24,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Món chính', 'Món nóng', 'Thịt', 'Hải sản', 'Món phụ']),
                'suggested_tags' => $this->getTagIds(['cân bằng', 'đa dạng', 'dinh dưỡng', 'mùa thu', 'mùa xuân']),
                'suggestion_reason' => 'Thời tiết mát mẻ (15-24°C) - gợi ý các món ăn đa dạng, cân bằng dinh dưỡng',
                'priority' => 6,
                'is_active' => true,
                'seasonal_rules' => ['autumn' => true, 'spring' => true, 'mild' => true]
            ],

            // === NHIỆT ĐỘ LẠNH (10-15°C) - MÙA ĐÔNG NHẸ ===
            [
                'name' => 'Nhiệt độ lạnh (10-15°C)',
                'description' => 'Nhiệt độ lạnh 10-15°C - mùa đông nhẹ',
                'temperature_min' => 10,
                'temperature_max' => 15,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Lẩu', 'Cháo', 'Súp nóng', 'Món nóng', 'Thịt']),
                'suggested_tags' => $this->getTagIds(['nóng', 'ấm', 'dinh dưỡng', 'lẩu', 'cháo', 'mùa đông']),
                'suggestion_reason' => 'Thời tiết lạnh (10-15°C) - phù hợp với các món ăn nóng, ấm và giàu dinh dưỡng',
                'priority' => 8,
                'is_active' => true,
                'seasonal_rules' => ['winter' => true, 'cold' => true]
            ],

            // === NHIỆT ĐỘ RẤT LẠNH (< 10°C) - MÙA ĐÔNG KHẮC NGHIỆT ===
            [
                'name' => 'Nhiệt độ rất lạnh (< 10°C)',
                'description' => 'Nhiệt độ rất lạnh dưới 10°C - mùa đông khắc nghiệt',
                'temperature_min' => null,
                'temperature_max' => 10,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Lẩu', 'Cháo', 'Súp nóng', 'Món nóng', 'Thịt', 'Món chính']),
                'suggested_tags' => $this->getTagIds(['nóng', 'ấm', 'dinh dưỡng', 'lẩu', 'cháo', 'mùa đông', 'giữ ấm']),
                'suggestion_reason' => 'Thời tiết rất lạnh (dưới 10°C) - cần các món ăn nóng, giàu dinh dưỡng để giữ ấm cơ thể',
                'priority' => 10,
                'is_active' => true,
                'seasonal_rules' => ['winter' => true, 'very_cold' => true]
            ],

            // === ĐỘ ẨM CAO (>80%) - MÙA MƯA ===
            [
                'name' => 'Độ ẩm cao (>80%)',
                'description' => 'Độ ẩm cao trên 80% - mùa mưa ẩm ướt',
                'temperature_min' => null,
                'temperature_max' => null,
                'humidity_min' => 80,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Món khô', 'Món cay', 'Nướng', 'Chiên', 'Món nóng']),
                'suggested_tags' => $this->getTagIds(['khô', 'cay', 'nướng', 'chiên', 'mùa mưa', 'cân bằng']),
                'suggestion_reason' => 'Độ ẩm cao (>80%) - gợi ý các món ăn khô, cay để cân bằng độ ẩm',
                'priority' => 7,
                'is_active' => true,
                'seasonal_rules' => ['rainy' => true, 'humid' => true]
            ],

            // === ĐỘ ẨM THẤP (<40%) - MÙA KHÔ ===
            [
                'name' => 'Độ ẩm thấp (<40%)',
                'description' => 'Độ ẩm thấp dưới 40% - mùa khô',
                'temperature_min' => null,
                'temperature_max' => null,
                'humidity_min' => null,
                'humidity_max' => 40,
                'suggested_categories' => $this->getCategoryIds(['Canh', 'Súp', 'Đồ uống', 'Món mát', 'Món nước']),
                'suggested_tags' => $this->getTagIds(['nước', 'canh', 'súp', 'mát', 'mùa khô', 'bổ sung ẩm']),
                'suggestion_reason' => 'Độ ẩm thấp (<40%) - gợi ý các món ăn có nước, mát để bổ sung độ ẩm',
                'priority' => 6,
                'is_active' => true,
                'seasonal_rules' => ['dry' => true, 'low_humidity' => true]
            ],

            // === MÙA MƯA ẨM ƯỚT (20-28°C, >70%) ===
            [
                'name' => 'Mùa mưa ẩm ướt (20-28°C, >70%)',
                'description' => 'Quy tắc cho mùa mưa ẩm ướt',
                'temperature_min' => 20,
                'temperature_max' => 28,
                'humidity_min' => 70,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Món khô', 'Món cay', 'Nướng', 'Món nóng', 'Lẩu']),
                'suggested_tags' => $this->getTagIds(['khô', 'cay', 'nướng', 'ấm', 'mùa mưa', 'giữ ấm']),
                'suggestion_reason' => 'Mùa mưa ẩm ướt - phù hợp với các món ăn khô, cay để cân bằng độ ẩm và giữ ấm',
                'priority' => 7,
                'is_active' => true,
                'seasonal_rules' => ['rainy' => true, 'humid' => true, 'warm' => true]
            ],

            // === MÙA ĐÔNG LẠNH GIÁ (< 15°C) ===
            [
                'name' => 'Mùa đông lạnh giá (< 15°C)',
                'description' => 'Quy tắc cho mùa đông lạnh giá',
                'temperature_min' => null,
                'temperature_max' => 15,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Lẩu', 'Cháo', 'Súp nóng', 'Món nóng', 'Thịt']),
                'suggested_tags' => $this->getTagIds(['nóng', 'ấm', 'dinh dưỡng', 'lẩu', 'cháo', 'mùa đông', 'giữ ấm']),
                'suggestion_reason' => 'Mùa đông lạnh giá - cần các món ăn nóng, ấm và giàu dinh dưỡng để giữ ấm',
                'priority' => 9,
                'is_active' => true,
                'seasonal_rules' => ['winter' => true, 'cold' => true]
            ],

            // === MÙA HÈ NÓNG KHÔ (>= 30°C, <50%) ===
            [
                'name' => 'Mùa hè nóng khô (>= 30°C, <50%)',
                'description' => 'Quy tắc cho mùa hè nóng khô',
                'temperature_min' => 30,
                'temperature_max' => null,
                'humidity_min' => null,
                'humidity_max' => 50,
                'suggested_categories' => $this->getCategoryIds(['Đồ uống', 'Món mát', 'Tráng miệng', 'Salad', 'Canh']),
                'suggested_tags' => $this->getTagIds(['mát', 'lạnh', 'giải nhiệt', 'nước', 'mùa hè', 'bổ sung ẩm']),
                'suggestion_reason' => 'Mùa hè nóng khô - cần các món ăn mát, có nước để giải nhiệt và bổ sung độ ẩm',
                'priority' => 9,
                'is_active' => true,
                'seasonal_rules' => ['summer' => true, 'hot' => true, 'dry' => true]
            ],

            // === MÙA XUÂN MÁT MẺ (15-22°C) ===
            [
                'name' => 'Mùa xuân mát mẻ (15-22°C)',
                'description' => 'Quy tắc cho mùa xuân mát mẻ',
                'temperature_min' => 15,
                'temperature_max' => 22,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Món chính', 'Món phụ', 'Thịt', 'Hải sản', 'Rau củ']),
                'suggested_tags' => $this->getTagIds(['cân bằng', 'đa dạng', 'dinh dưỡng', 'mùa xuân', 'tươi mới']),
                'suggestion_reason' => 'Mùa xuân mát mẻ - gợi ý các món ăn đa dạng, tươi mới và cân bằng dinh dưỡng',
                'priority' => 6,
                'is_active' => true,
                'seasonal_rules' => ['spring' => true, 'mild' => true, 'fresh' => true]
            ],

            // === MÙA THU MÁT MẺ (18-25°C) ===
            [
                'name' => 'Mùa thu mát mẻ (18-25°C)',
                'description' => 'Quy tắc cho mùa thu mát mẻ',
                'temperature_min' => 18,
                'temperature_max' => 25,
                'humidity_min' => null,
                'humidity_max' => null,
                'suggested_categories' => $this->getCategoryIds(['Món chính', 'Món nóng', 'Thịt', 'Hải sản', 'Món phụ']),
                'suggested_tags' => $this->getTagIds(['cân bằng', 'đa dạng', 'dinh dưỡng', 'mùa thu', 'ấm áp']),
                'suggestion_reason' => 'Mùa thu mát mẻ - gợi ý các món ăn đa dạng, ấm áp và cân bằng dinh dưỡng',
                'priority' => 6,
                'is_active' => true,
                'seasonal_rules' => ['autumn' => true, 'mild' => true, 'warm' => true]
            ]
        ];

        // Create weather condition rules
        foreach ($rules as $ruleData) {
            WeatherConditionRule::create($ruleData);
        }

        $this->command->info('✅ Đã tạo thành công ' . count($rules) . ' quy tắc điều kiện thời tiết:');
        $this->command->info('   🌡️  Nhiệt độ cao: 3 quy tắc');
        $this->command->info('   🌡️  Nhiệt độ mát mẻ: 2 quy tắc');
        $this->command->info('   ❄️  Nhiệt độ lạnh: 2 quy tắc');
        $this->command->info('   💧  Độ ẩm cao: 1 quy tắc');
        $this->command->info('   🌵  Độ ẩm thấp: 1 quy tắc');
        $this->command->info('   🌧️  Mùa mưa: 1 quy tắc');
        $this->command->info('   🌸  Mùa xuân: 1 quy tắc');
        $this->command->info('   🍂  Mùa thu: 1 quy tắc');
        $this->command->info('   ☀️  Mùa hè: 3 quy tắc');
        $this->command->info('   ❄️  Mùa đông: 2 quy tắc');
        $this->command->info('');
        $this->command->info('🎯 Các quy tắc này sẽ giúp đề xuất món ăn phù hợp với điều kiện thời tiết thực tế!');
    }

    /**
     * Add missing categories that are commonly needed for weather-based suggestions.
     */
    protected function addMissingCategories()
    {
        $missingCategories = [
            ['name' => 'Hải sản', 'slug' => 'hai-san', 'description' => 'Các món ăn từ hải sản', 'icon' => 'heroicon-o-fish', 'color' => '#0EA5E9', 'sort_order' => 16],
            ['name' => 'Món khô', 'slug' => 'mon-kho', 'description' => 'Các món ăn khô, ít nước', 'icon' => 'heroicon-o-sun', 'color' => '#F59E0B', 'sort_order' => 17],
            ['name' => 'Món cay', 'slug' => 'mon-cay', 'description' => 'Các món ăn cay nóng', 'icon' => 'heroicon-o-fire', 'color' => '#DC2626', 'sort_order' => 18],
            ['name' => 'Nướng', 'slug' => 'nuong', 'description' => 'Các món ăn nướng', 'icon' => 'heroicon-o-fire', 'color' => '#B45309', 'sort_order' => 19],
            ['name' => 'Chiên', 'slug' => 'chien', 'description' => 'Các món ăn chiên', 'icon' => 'heroicon-o-fire', 'color' => '#D97706', 'sort_order' => 20],
            ['name' => 'Rau củ', 'slug' => 'rau-cu', 'description' => 'Các món ăn từ rau củ', 'icon' => 'heroicon-o-leaf', 'color' => '#16A34A', 'sort_order' => 21],
        ];

        foreach ($missingCategories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('📝 Đã thêm ' . count($missingCategories) . ' categories mới cần thiết cho weather rules');
    }

    /**
     * Get category IDs by names.
     */
    protected function getCategoryIds($categoryNames)
    {
        return Category::whereIn('name', $categoryNames)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Get tag IDs by names.
     */
    protected function getTagIds($tagNames)
    {
        return Tag::whereIn('name', $tagNames)
            ->pluck('id')
            ->toArray();
    }
} 