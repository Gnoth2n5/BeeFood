<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\User;

class FavoriteService
{
   
    public function toggle(Recipe $recipe, User $user): array
    {
        $favorite = Favorite::where('user_id', $user->id)
                           ->where('recipe_id', $recipe->id)
                           ->first();

        if ($favorite) {
            $favorite->delete();
            $recipe->updateFavoriteCount();
            $isFavorited = false;
            $message = 'Đã xóa khỏi danh sách yêu thích.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'recipe_id' => $recipe->id,
            ]);
            $recipe->updateFavoriteCount();
            $isFavorited = true;
            $message = 'Đã thêm vào danh sách yêu thích.';
        }

        return [
            'is_favorited' => $isFavorited,
            'message' => $message,
            'favorite_count' => $recipe->fresh()->favorite_count
        ];
    }

    
    public function getUserFavorites(User $user, int $perPage = 12)
    {
        return Favorite::where('user_id', $user->id)
                      ->with(['recipe.user', 'recipe.categories', 'recipe.tags'])
                      ->orderBy('created_at', 'desc')
                      ->paginate($perPage);
    }

    
    public function isFavorited(Recipe $recipe, User $user): bool
    {
        return Favorite::where('user_id', $user->id)
                      ->where('recipe_id', $recipe->id)
                      ->exists();
    }

    
    public function getFavoriteCount(Recipe $recipe): int
    {
        return $recipe->favorites()->count();
    }

   
    public function getUserFavoriteCount(User $user): int
    {
        return Favorite::where('user_id', $user->id)->count();
    }

   
     public function removeFavorite(Recipe $recipe, User $user): bool
    {
        $favorite = Favorite::where('user_id', $user->id)
                           ->where('recipe_id', $recipe->id)
                           ->first();

        if ($favorite) {
            $favorite->delete();
            $recipe->updateFavoriteCount();
            return true;
        }

        return false;
    }
} 