<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
class CommentSystem extends Component
{
    use WithPagination;

    public Post $post;
    public $newComment = '';
    public $editingComment = null;
    public $editContent = '';
    public $replyingTo = null;
    public $replyContent = '';
    public $showReplyForm = null;

    protected $rules = [
        'newComment' => 'required|string|max:1000',
        'editContent' => 'required|string|max:1000',
        'replyContent' => 'required|string|max:1000',
    ];

    protected $messages = [
        'newComment.required' => 'Vui lòng nhập nội dung bình luận.',
        'newComment.max' => 'Bình luận không được quá 1000 ký tự.',
        'editContent.required' => 'Vui lòng nhập nội dung bình luận.',
        'editContent.max' => 'Bình luận không được quá 1000 ký tự.',
        'replyContent.required' => 'Vui lòng nhập nội dung trả lời.',
        'replyContent.max' => 'Trả lời không được quá 1000 ký tự.',
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function postComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:1000',
        ]);

        try {
           
            $comment = Comment::create([
                'content' => $this->newComment,
                'post_id' => $this->post->id,
                'user_id' => Auth::id(),
                'parent_id' => null,
                'status' => 'approved',
            ]);

            $this->newComment = '';
            // $this->dispatch('comment-posted');
            // $this->dispatch('show-message', [
            //     'message' => 'Bình luận đã được gửi thành công!',
            //     'type' => 'success'
            // ]);

        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'message' => 'Có lỗi xảy ra khi gửi bình luận: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function startEdit($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            $this->dispatch('show-message', [
                'message' => 'Bạn không có quyền chỉnh sửa bình luận này.',
                'type' => 'error'
            ]);
            return;
        }

        $this->editingComment = $commentId;
        $this->editContent = $comment->content;
    }

    public function cancelEdit()
    {
        $this->editingComment = null;
        $this->editContent = '';
    }

    public function updateComment($commentId)
    {
        $this->validate([
            'editContent' => 'required|string|max:1000',
        ]);

        try {
            $comment = Comment::findOrFail($commentId);
            
            if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                $this->dispatch('show-message', [
                    'message' => 'Bạn không có quyền chỉnh sửa bình luận này.',
                    'type' => 'error'
                ]);
                return;
            }

            $comment->update(['content' => $this->editContent]);
            $comment->markAsEdited();

            $this->editingComment = null;
            $this->editContent = '';
            
            $this->dispatch('show-message', [
                'message' => 'Bình luận đã được cập nhật thành công!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'message' => 'Có lỗi xảy ra khi cập nhật bình luận: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function deleteComment($commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);
            
            if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                $this->dispatch('show-message', [
                    'message' => 'Bạn không có quyền xóa bình luận này.',
                    'type' => 'error'
                ]);
                return;
            }

            $comment->delete();
            
            $this->dispatch('show-message', [
                'message' => 'Bình luận đã được xóa thành công!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'message' => 'Có lỗi xảy ra khi xóa bình luận: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->showReplyForm = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->showReplyForm = null;
        $this->replyContent = '';
    }

    public function postReply($commentId)
    {
        $this->validate([
            'replyContent' => 'required|string|max:1000',
        ]);

        try {
            $parentComment = Comment::findOrFail($commentId);
            
            $reply = Comment::create([
                'content' => $this->replyContent,
                'post_id' => $this->post->id,
                'user_id' => Auth::id(),
                'parent_id' => $commentId,
                'status' => 'approved',
            ]);

            $this->replyingTo = null;
            $this->showReplyForm = null;
            $this->replyContent = '';
            
            $this->dispatch('show-message', [
                'message' => 'Trả lời đã được gửi thành công!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'message' => 'Có lỗi xảy ra khi gửi trả lời: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function likeComment($commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);
            $comment->incrementLikeCount();
            
            $this->dispatch('comment-liked', ['commentId' => $commentId, 'likeCount' => $comment->fresh()->like_count]);
        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'message' => 'Có lỗi xảy ra khi thích bình luận: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function dislikeComment($commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);
            $comment->incrementDislikeCount();
            
            $this->dispatch('comment-disliked', ['commentId' => $commentId, 'dislikeCount' => $comment->fresh()->dislike_count]);
        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'message' => 'Có lỗi xảy ra khi không thích bình luận: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.comment-system', [
            'comments' => $comments,
            'commentsCount' => $comments->count()
        ]);
    }
}
