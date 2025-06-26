<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
        'cover_image',
        'recipe_count'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'recipe_count' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'collection_recipes');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
    
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeMostRecipes($query)
    {
        return $query->orderBy('recipe_count', 'desc');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function updateRecipeCount()
    {
        $this->recipe_count = $this->recipes()->count();
        $this->save();
    }

    public function isPublic()
    {
        return $this->is_public;
    }

    public function isPrivate()
    {
        return !$this->is_public;
    }
}