<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content',
        'post_id',
        'user_id',
        'parent_id',
        'status',
        'like_count',
        'dislike_count',
        'is_edited',
        'edited_at',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
        'like_count' => 'integer',
        'dislike_count' => 'integer',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the child comments (replies).
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Get all nested replies recursively.
     */
    public function allReplies(): HasMany
    {
        return $this->replies()->with('allReplies');
    }

    /**
     * Check if comment has replies.
     */
    public function hasReplies(): bool
    {
        return $this->replies()->exists();
    }

    /**
     * Check if comment is a reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Check if comment is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if comment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if comment is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope to get only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get only top-level comments (no parent).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get comments for a specific post.
     */
    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    /**
     * Get the total replies count including nested ones.
     */
    public function getTotalRepliesCountAttribute(): int
    {
        $count = $this->replies()->count();
        foreach ($this->replies as $reply) {
            $count += $reply->total_replies_count;
        }
        return $count;
    }

    /**
     * Mark comment as edited.
     */
    public function markAsEdited(): void
    {
        $this->update([
            'is_edited' => true,
            'edited_at' => now(),
        ]);
    }

    /**
     * Increment like count.
     */
    public function incrementLikeCount(): void
    {
        $this->increment('like_count');
    }

    /**
     * Increment dislike count.
     */
    public function incrementDislikeCount(): void
    {
        $this->increment('dislike_count');
    }

    /**
     * Decrement like count.
     */
    public function decrementLikeCount(): void
    {
        if ($this->like_count > 0) {
            $this->decrement('like_count');
        }
    }

    /**
     * Decrement dislike count.
     */
    public function decrementDislikeCount(): void
    {
        if ($this->dislike_count > 0) {
            $this->decrement('dislike_count');
        }
    }
}
