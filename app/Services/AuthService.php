<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
  public function login(string $email, string $password, bool $remember = false): bool
  {
    if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
      return true;
    }

    return false;
  }

  public function register(array $data): User
  {
    DB::beginTransaction();

    try {
      // Create user
      $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'status' => 'active',
        'login_count' => 0
      ]);

      // Create user profile
      UserProfile::create([
        'user_id' => $user->id,
        'cooking_experience' => 'beginner',
        'dietary_preferences' => [],
        'allergies' => [],
        'health_conditions' => [],
        'timezone' => 'Asia/Ho_Chi_Minh',
        'language' => 'vi'
      ]);

      // Assign user role
      $user->assignRole('user');

      DB::commit();

      return $user;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }

  public function logout(): void
  {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
  }

  public function getCurrentUser(): ?User
  {
    if (Auth::check()) {
      return Auth::user()->load('profile');
    }
    return null;
  }

  public function canAccess(string $permission): bool
  {
    return Auth::check() && Auth::user()->can($permission);
  }
}
