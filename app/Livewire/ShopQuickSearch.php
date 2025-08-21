<?php

namespace App\Livewire;

use App\Models\ShopItem;
use App\Models\UserShop;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShopQuickSearch extends Component
{
    #[Url(as: 'q')]
    public $searchQuery = '';

    public $showSuggestions = false;
    public $suggestions = [];

    protected $listeners = ['searchUpdated' => 'updateSearch'];

    public function mount()
    {
        $this->suggestions = [
            'shops' => collect(),
            'items' => collect(),
        ];
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 1) {
            $this->performSearch();
            $this->showSuggestions = true;
        } else {
            $this->suggestions = [
                'shops' => collect(),
                'items' => collect(),
            ];
            $this->showSuggestions = false;
        }
    }

    public function performSearch()
    {
        if (empty($this->searchQuery)) {
            $this->suggestions = [
                'shops' => collect(),
                'items' => collect(),
            ];
            return;
        }

        $term = "%{$this->searchQuery}%";

        $shops = UserShop::query()
            ->with('shopItems')
            ->where('is_active', true)
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('address', 'like', $term)
                    ->orWhere('website', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhereHas('shopItems', function ($iq) use ($term) {
                        $iq->where('is_active', true)
                            ->where(function ($w) use ($term) {
                                $w->where('name', 'like', $term)
                                    ->orWhere('description', 'like', $term);
                            });
                    });
            })
            ->limit(5)
            ->get();

        $items = ShopItem::query()
            ->with('userShop')
            ->where('is_active', true)
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            })
            ->whereHas('userShop', function ($sq) {
                $sq->where('is_active', true);
            })
            ->limit(5)
            ->get();

        $this->suggestions = [
            'shops' => $shops,
            'items' => $items,
        ];
    }

    public function updateSearch($search)
    {
        $this->searchQuery = $search;
        $this->performSearch();
    }

    public function selectSuggestion($suggestion)
    {
        $this->searchQuery = $suggestion;
        $this->showSuggestions = false;
        $this->performSearch();
    }

    public function goToSearchPage()
    {
        if (!empty($this->searchQuery)) {
            $this->showSuggestions = false;
            if ($this->suggestions['shops']->count() > 0) {
                $firstShop = $this->suggestions['shops']->first();
                return redirect()->route('shops.show', $firstShop->slug);
            }
            return redirect()->route('shops.index', ['search' => $this->searchQuery]);
        }
    }

    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->suggestions = [
            'shops' => collect(),
            'items' => collect(),
        ];
        $this->showSuggestions = false;
    }

    public function render()
    {
        return view('livewire.shops.quick-search');
    }
}

 