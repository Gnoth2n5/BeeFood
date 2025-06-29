<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user): bool
  {
    return true; // Anyone can view collections list
  }

  public function view(User $user, Collection $collection): bool
  {
    if ($collection->is_public) {
      return true;
    }
    return $user->id === $collection->user_id;
  }
  public function create(User $user): bool
  {
    return $user->hasPermissionTo('collection.create');
  }
  public function update(User $user, Collection $collection): bool
  {
    return $user->id === $collection->user_id &&
      $user->hasPermissionTo('collection.edit');
  }

  public function delete(User $user, Collection $collection): bool
  {
    return $user->id === $collection->user_id &&
      $user->hasPermissionTo('collection.delete');
  }
  public function restore(User $user, Collection $collection): bool
  {
    return $user->id === $collection->user_id &&
      $user->hasPermissionTo('collection.edit');
  }

  public function addRecipe(User $user, Collection $collection): bool
  {
    return $user->id === $collection->user_id &&
      $user->hasPermissionTo('collection.edit');
  }
  public function removeRecipe(User $user, Collection $collection): bool
  {
    return $user->id === $collection->user_id &&
      $user->hasPermissionTo('collection.edit');
  }
}
