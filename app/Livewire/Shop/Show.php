<?php

namespace App\Livewire\Shop;

use App\Models\UserShop;
use Livewire\Component;

class Show extends Component
{
    public UserShop $shop;
    
    public function mount($slug)
    {
        $this->shop = UserShop::with('shopItems')
            ->where('slug', $slug)
            ->firstOrFail();
    }
    
    public function render()
    {
        return view('livewire.shop.show')
            ->layout('layouts.app');
    }
}
