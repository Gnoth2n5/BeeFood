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
