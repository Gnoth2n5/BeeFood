<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $users = User::all();

        if ($posts->isEmpty() || $users->isEmpty()) {
            $this->command->info('No posts or users found. Please run PostSeeder and UserSeeder first.');
            return;
        }

        // Sample comment contents
        $commentContents = [
            'Bài viết rất hay và hữu ích! Cảm ơn tác giả đã chia sẻ.',
            'Tôi đã thử làm theo và kết quả rất tốt. Mọi người nên thử!',
            'Thông tin chi tiết và dễ hiểu. Rất phù hợp cho người mới bắt đầu.',
            'Cách trình bày rõ ràng, dễ theo dõi. Tôi sẽ áp dụng ngay.',
            'Bài viết có nhiều mẹo hay mà tôi chưa biết. Cảm ơn!',
            'Nội dung chất lượng cao, đáng để đọc và tham khảo.',
            'Tôi thích cách tác giả giải thích từng bước một cách chi tiết.',
            'Bài viết này đã giúp tôi cải thiện kỹ năng nấu ăn rất nhiều.',
            'Thông tin rất thực tế và có thể áp dụng ngay.',
            'Cảm ơn tác giả đã chia sẻ kinh nghiệm quý báu.',
        ];

        $replyContents = [
            'Đồng ý với bạn!',
            'Tôi cũng nghĩ vậy.',
            'Cảm ơn bạn đã chia sẻ thêm.',
            'Ý kiến hay đấy!',
            'Tôi sẽ thử cách này.',
            'Bạn nói đúng rồi.',
            'Thêm một ý kiến nữa.',
            'Tôi cũng có kinh nghiệm tương tự.',
            'Cảm ơn bạn đã góp ý.',
            'Đây là một điểm rất quan trọng.',
        ];

        foreach ($posts as $post) {
            // Create top-level comments
            $topLevelComments = [];
            $commentCount = rand(3, 8);
            
            for ($i = 0; $i < $commentCount; $i++) {
                $comment = Comment::create([
                    'content' => $commentContents[array_rand($commentContents)],
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'parent_id' => null,
                    'status' => 'approved',
                    'like_count' => rand(0, 15),
                    'dislike_count' => rand(0, 3),
                    'created_at' => $post->published_at ? $post->published_at->addHours(rand(1, 48)) : now()->subDays(rand(1, 7)),
                ]);
                
                $topLevelComments[] = $comment;
                
                // Create replies for some comments
                if (rand(0, 1) && count($topLevelComments) > 0) {
                    $replyCount = rand(1, 4);
                    for ($j = 0; $j < $replyCount; $j++) {
                        Comment::create([
                            'content' => $replyContents[array_rand($replyContents)],
                            'post_id' => $post->id,
                            'user_id' => $users->random()->id,
                            'parent_id' => $comment->id,
                            'status' => 'approved',
                            'like_count' => rand(0, 8),
                            'dislike_count' => rand(0, 2),
                            'created_at' => $comment->created_at->addHours(rand(1, 24)),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Comments seeded successfully!');
    }
}
