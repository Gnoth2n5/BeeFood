<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ModerationRule;

class ModerationRuleDumpSeeder extends Seeder
{
    /**
     * Run the seeder.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('moderation_rules')->truncate();

        // Insert data
        $data = [
            [
                'id' => 1,
                'name' => 'Từ ngữ không phù hợp',
                'keywords' => 'đm, địt, đụ, lồn, cặc, buồi, đít, đéo, mẹ kiếp, đcm, đcmđ, đcmđm, đcmđmm, đcmđmmm',
                'action' => 'reject',
                'description' => 'Từ chối các công thức có chứa từ ngữ tục tĩu, không phù hợp',
                'is_active' => 1,
                'priority' => 10,
                'fields_to_check' => '["title","description","summary","ingredients","instructions","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 2,
                'name' => 'Spam và quảng cáo',
                'keywords' => 'mua ngay, giảm giá, khuyến mãi, liên hệ, hotline, website, www, http, https, .com, .vn, 090, 091, 092, 093, 094, 095, 096, 097, 098, 099',
                'action' => 'reject',
                'description' => 'Từ chối các công thức có nội dung spam hoặc quảng cáo',
                'is_active' => 1,
                'priority' => 9,
                'fields_to_check' => '["title","description","summary","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 3,
                'name' => 'Nội dung chính trị nhạy cảm',
                'keywords' => 'đảng, chính trị, chính phủ, nhà nước, lãnh đạo, thủ tướng, chủ tịch, bộ trưởng',
                'action' => 'flag',
                'description' => 'Đánh dấu để kiểm tra thủ công các nội dung liên quan đến chính trị',
                'is_active' => 1,
                'priority' => 8,
                'fields_to_check' => '["title","description","summary","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 4,
                'name' => 'Từ khóa nhạy cảm',
                'keywords' => 'ma túy, heroin, cocaine, thuốc lắc, cần sa, rượu, bia, thuốc lá, cờ bạc, cá độ',
                'action' => 'reject',
                'description' => 'Từ chối các công thức có nội dung liên quan đến chất cấm hoặc hoạt động bất hợp pháp',
                'is_active' => 1,
                'priority' => 7,
                'fields_to_check' => '["title","description","summary","ingredients","instructions","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 5,
                'name' => 'Nội dung bạo lực',
                'keywords' => 'giết, chết, máu, bạo lực, đánh nhau, đâm, chém, súng, dao, vũ khí',
                'action' => 'reject',
                'description' => 'Từ chối các công thức có nội dung bạo lực hoặc không phù hợp',
                'is_active' => 1,
                'priority' => 6,
                'fields_to_check' => '["title","description","summary","instructions","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 6,
                'name' => 'Từ khóa tình dục',
                'keywords' => 'sex, tình dục, làm tình, quan hệ, yêu, hôn, ôm, vuốt ve, kích thích',
                'action' => 'flag',
                'description' => 'Đánh dấu để kiểm tra thủ công các nội dung liên quan đến tình dục',
                'is_active' => 1,
                'priority' => 5,
                'fields_to_check' => '["title","description","summary","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 7,
                'name' => 'Nội dung phân biệt đối xử',
                'keywords' => 'da đen, da trắng, mọi rợ, man rợ, dân tộc, chủng tộc, phân biệt, kỳ thị',
                'action' => 'reject',
                'description' => 'Từ chối các công thức có nội dung phân biệt đối xử hoặc kỳ thị',
                'is_active' => 1,
                'priority' => 4,
                'fields_to_check' => '["title","description","summary","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 8,
                'name' => 'Từ khóa y tế nhạy cảm',
                'keywords' => 'thuốc, bệnh, điều trị, chữa bệnh, bác sĩ, bệnh viện, phòng khám, triệu chứng, chẩn đoán',
                'action' => 'flag',
                'description' => 'Đánh dấu để kiểm tra thủ công các nội dung liên quan đến y tế',
                'is_active' => 1,
                'priority' => 3,
                'fields_to_check' => '["title","description","summary","ingredients","instructions","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 9,
                'name' => 'Nội dung tôn giáo nhạy cảm',
                'keywords' => 'phật, chúa, allah, thánh, thần, cúng, lễ, cầu nguyện, tôn giáo, tín ngưỡng',
                'action' => 'flag',
                'description' => 'Đánh dấu để kiểm tra thủ công các nội dung liên quan đến tôn giáo',
                'is_active' => 1,
                'priority' => 2,
                'fields_to_check' => '["title","description","summary","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
            [
                'id' => 10,
                'name' => 'Từ khóa thương hiệu',
                'keywords' => 'coca cola, pepsi, heineken, tiger, carlsberg, marlboro, vinataba, sabeco, habeco',
                'action' => 'auto_approve',
                'description' => 'Cho phép các công thức có đề cập đến thương hiệu nhưng vẫn ghi nhận',
                'is_active' => 1,
                'priority' => 1,
                'fields_to_check' => '["title","description","summary","ingredients","instructions","tips","notes"]',
                'created_by' => 1,
                'created_at' => '2025-08-08 05:52:41',
                'updated_at' => '2025-08-08 05:52:41',
            ],
        ];

        DB::table('moderation_rules')->insert($data);
    }
}
