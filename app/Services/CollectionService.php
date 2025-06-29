<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollectionService
{
  public function create(array $data, User $user): Collection
  {
    $collection = new Collection($data);
    $collection->user_id = $user->id;
    $collection->slug = Str::slug($data['name']);
    $collection->is_public = $data['is_public'] ?? false;
    $collection->save();

    return $collection;
  }

  public function update(Collection $collection, array $data): Collection
  {
    $collection->update($data);
    $collection->slug = Str::slug($data['name']);
    $collection->is_public = $data['is_public'] ?? false;

    $collection->save();

    return $collection;
  }

  public function delete(Collection $collection): bool
  {
    if ($collection->cover_image) {
      Storage::disk('public')->delete($collection->cover_image);
    }

    return $collection->delete();
  }

  public function getUserCollections(User $user, int $perPage = 12)
  {
    return Collection::where('user_id', $user->id)
      ->with(['recipes'])
      ->orderBy('created_at', 'desc')
      ->paginate($perPage);
  }

  /**
   * Get public collections.
   */
  public function getPublicCollections(int $perPage = 12)
  {
    return Collection::where('is_public', true)
      ->with(['user', 'recipes'])
      ->orderBy('created_at', 'desc')
      ->paginate($perPage);
  }

  /**
   * Add recipe to collection.
   */
  public function addRecipe(Collection $collection, Recipe $recipe): bool
  {
    // Check if recipe is already in collection
    if ($collection->recipes()->where('recipe_id', $recipe->id)->exists()) {
      return false;
    }

    $collection->recipes()->attach($recipe->id);
    $collection->increment('recipe_count');

    return true;
  }

  public function canView(Collection $collection, User $user): bool
  {
    return $collection->is_public || $collection->user_id === $user->id;
  }

  protected function handleCoverImage(Collection $collection, UploadedFile $image, bool $deleteOld = false): void
  {
    if ($deleteOld && $collection->cover_image) {
      Storage::disk('public')->delete($collection->cover_image);
    }

    $path = $image->store('collections', 'public');
    $collection->cover_image = $path;
  }
}
