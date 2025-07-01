<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $searchQuery = '';
    public $showSearch = false;
    
    public function toggleSearch()
    {
        $this->showSearch = !$this->showSearch;
    }
    
    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="bg-white border-b border-gray-200 px-4 py-2.5 dark:bg-gray-900 dark:border-gray-700">
    <div class="flex flex-wrap justify-between items-center">
        <div class="flex justify-start items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center justify-center mr-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="self-center text-xl font-semibold whitespace-nowrap text-gray-900 dark:text-white">
                        BeeFood
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}" 
                   class="text-gray-900 hover:text-orange-600 dark:text-white dark:hover:text-orange-500 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('home') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20' : '' }}">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Trang chủ</span>
                    </div>
                </a>
                
                <a href="{{ route('recipes.index') }}" 
                   class="text-gray-900 hover:text-orange-600 dark:text-white dark:hover:text-orange-500 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('recipes.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20' : '' }}">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Công thức</span>
                    </div>
                </a>
                
                @auth
                    <a href="{{ route('recipes.my') }}" 
                       class="text-gray-900 hover:text-orange-600 dark:text-white dark:hover:text-orange-500 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('recipes.my') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20' : '' }}">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Công thức của tôi</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('favorites.index') }}" 
                       class="text-gray-900 hover:text-orange-600 dark:text-white dark:hover:text-orange-500 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('favorites.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20' : '' }}">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>Yêu thích</span>
                        </div>
                    </a>
                @endauth
            </div>
        </div>
        <!-- Search Bar -->
        <div class="hidden lg:flex items-center lg:order-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" 
                       wire:model.live="searchQuery"
                       class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-orange-500 dark:focus:border-orange-500" 
                       placeholder="Tìm kiếm công thức...">
            </div>
        </div>