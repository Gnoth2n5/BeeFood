<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display comments for a post.
     */
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    /**
     * Store a new comment.
     */
    public function store(Request $request, Post $post)
    {
        // Debug logging to see if this method is being called
        Log::info('CommentController@store method called', [
            'request_data' => $request->all(),
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);
        
        try {
            Log::info('Storing comment', ['request' => $request->all(), 'post' => $post->id]);
            
            $request->validate([
                'content' => 'required|string|max:1000',
                'parent_id' => [
                    'nullable',
                    'exists:comments,id',
                    Rule::exists('comments', 'id')->where(function ($query) use ($post) {
                        $query->where('post_id', $post->id);
                    }),
                ],
            ]);
    
            $comment = Comment::create([
                'content' => $request->content,
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'parent_id' => $request->parent_id,
                'status' => 'approved', // You can change this to 'pending' if you want moderation
            ]);
    
            $comment->load(['user', 'replies.user']);
    
            return response()->json([
                'message' => 'Comment posted successfully!',
                'comment' => $comment,
            ], 201);
            
        } catch(\Exception $e) {
            Log::error('Error storing comment', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Lỗi khi lưu bình luận: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        $comment->markAsEdited();

        return response()->json([
            'message' => 'Comment updated successfully!',
            'comment' => $comment->fresh(['user', 'replies.user']),
        ]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if user owns the comment or is admin
        if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully!',
        ]);
    }

    /**
     * Like a comment.
     */
    public function like(Comment $comment)
    {
        // You can implement a more sophisticated like system here
        // For now, we'll just increment the like count
        $comment->incrementLikeCount();

        return response()->json([
            'message' => 'Comment liked!',
            'like_count' => $comment->fresh()->like_count,
        ]);
    }

    /**
     * Dislike a comment.
     */
    public function dislike(Comment $comment)
    {
        $comment->incrementDislikeCount();

        return response()->json([
            'message' => 'Comment disliked!',
            'dislike_count' => $comment->fresh()->dislike_count,
        ]);
    }

    /**
     * Reply to a comment.
     */
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = Comment::create([
            'content' => $request->content,
            'post_id' => $comment->post_id,
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
            'status' => 'approved',
        ]);

        $reply->load(['user']);

        return response()->json([
            'message' => 'Reply posted successfully!',
            'reply' => $reply,
        ], 201);
    }
}
