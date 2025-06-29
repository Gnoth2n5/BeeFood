<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function create(array $data): Category
    {
        $category = new Category($data);
        $category->slug = Str::slug($data['name']);
        $category->save();

        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        $category->slug = Str::slug($data['name']);
        $category->save();

        return $category;
    }

    public function delete(Category $category): bool
    {
        // Check if category has recipes
        if ($category->recipes()->count() > 0) {
            return false;
        }

        return $category->delete();
    }

    public function getAllWithRecipeCount()
    {
        return Category::withCount('recipes')
                      ->orderBy('name')
                      ->get();
    }

    public function getForSelect()
    {
        return Category::select('id', 'name')
                      ->orderBy('name')
                      ->get();
    }

    public function getWithRecipes(Category $category, int $perPage = 12)
    {
        return $category->load(['recipes' => function ($query) use ($perPage) {
            $query->with(['user', 'categories', 'tags'])
                  ->orderBy('created_at', 'desc')
                  ->paginate($perPage);
        }]);
    }

    public function search(string $search)
    {
        return Category::where('name', 'like', "%{$search}%")
                      ->withCount('recipes')
                      ->orderBy('name')
                      ->get();
    }

    public function getPopular(int $limit = 10)
    {
        return Category::withCount('recipes')
                      ->orderBy('recipes_count', 'desc')
                      ->limit($limit)
                      ->get();
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }
    
}




?>