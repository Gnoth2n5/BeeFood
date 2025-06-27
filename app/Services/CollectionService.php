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
}
