<?php

namespace App\Livewire\Auth;

use App\Services\AuthService;
use Livewire\Component;

#[Layout('layouts.guest')]
class Register extends Component
{
  public $name = '';
  public $email = '';
  public $password = '';
  public $password_confirmation = '';
  public $isLoading = false;

  protected $rules = [
    'name' => 'required|string|max:255',
    'email' => 'required|email|max:255|unique:users,email',
    'password' => 'required|min:6|confirmed',
  ];

  public function register()
  {
    $this->isLoading = true;

    try {
      $this->validate();

      $authService = app(AuthService::class);

      if ($authService->register($this->name, $this->email, $this->password)) {
        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
      }

      $this->addError('general', 'Có lỗi xảy ra. Vui lòng thử lại.');
    } catch (\Exception $e) {
      $this->addError('general', 'Có lỗi xảy ra. Vui lòng thử lại.');
    } finally {
      $this->isLoading = false;
    }
  }

  public function render()
  {
    return view('livewire.auth.register');
  }
}
