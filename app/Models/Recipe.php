<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'summary',
        'cooking_time',
        'preparation_time',
        'total_time',
        'difficulty',
        'servings',
        'calories_per_serving',
        'ingredients',
        'instructions',
        'tips',
        'notes',
        'featured_image',
        'video_url',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'view_count',
        'favorite_count',
        'rating_count',
        'average_rating',
        'published_at'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
        'cooking_time' => 'integer',
        'preparation_time' => 'integer',
        'total_time' => 'integer',
        'servings' => 'integer',
        'calories_per_serving' => 'integer',
        'view_count' => 'integer',
        'favorite_count' => 'integer',
        'rating_count' => 'integer',
        'average_rating' => 'decimal:2'
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recipe) {
            if (empty($recipe->slug)) {
                $recipe->slug = Str::slug($recipe->title);
            }
            
            if (empty($recipe->total_time) && ($recipe->cooking_time || $recipe->preparation_time)) {
                $recipe->total_time = ($recipe->cooking_time ?? 0) + ($recipe->preparation_time ?? 0);
            }
        });

        static::updating(function ($recipe) {
            if ($recipe->isDirty('cooking_time') || $recipe->isDirty('preparation_time')) {
                $recipe->total_time = ($recipe->cooking_time ?? 0) + ($recipe->preparation_time ?? 0);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'recipe_categories');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tags');
    }

    public function images()
    {
        return $this->hasMany(RecipeImage::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_recipes');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'approved')
                    ->whereNotNull('published_at');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function scopeDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeCookingTime($query, $minMinutes = null, $maxMinutes = null)
    {
        if ($minMinutes) {
            $query->where('cooking_time', '>=', $minMinutes);
        }
        if ($maxMinutes) {
            $query->where('cooking_time', '<=', $maxMinutes);
        }
        return $query;
    }

    public function scopeMinRating($query, $rating)
    {
        return $query->where('average_rating', '>=', $rating);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc')
                    ->orderBy('favorite_count', 'desc');
    }

    public function scopeTopRated($query)
    {
        return $query->orderBy('average_rating', 'desc')
                    ->orderBy('rating_count', 'desc');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function isPublished()
    {
        return $this->status === 'approved' && $this->published_at !== null;
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function updateRatingStats()
    {
        $ratings = $this->ratings();
        $this->rating_count = $ratings->count();
        $this->average_rating = $ratings->avg('rating') ?? 0;
        $this->save();
    }

    public function updateFavoriteCount()
    {
        $this->favorite_count = $this->favorites()->count();
        $this->save();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}



?>