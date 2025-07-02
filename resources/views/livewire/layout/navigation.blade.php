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
