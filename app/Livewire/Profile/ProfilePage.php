<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Recipe;
use App\Models\Collection;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProfilePage extends Component
{
    use WithFileUploads;

    public $user;
    public $profile;
    public $isEditing = false;
    public $avatar;
    
    // Profile fields
    public $name;
    public $email;
    public $bio;
    public $phone;
    public $address;
    public $city;
    public $country;
    public $cooking_experience;
    public $dietary_preferences = [];
    public $allergies = '';
    public $health_conditions = '';
    
    // Stats
    public $recipesCount = 0;
    public $collectionsCount = 0;
    public $favoritesCount = 0;
    
    // Tabs
    public $activeTab = 'recipes';
    
    // Dietary options
    public $dietaryOptions = [
        'vegan' => 'Thuần chay',
        'vegetarian' => 'Ăn chay',
        'pescatarian' => 'Ăn cá',
        'gluten_free' => 'Không gluten',
        'dairy_free' => 'Không sữa',
        'keto' => 'Keto',
        'paleo' => 'Paleo',
        'low_carb' => 'Ít carb',
        'low_sodium' => 'Ít muối',
        'halal' => 'Halal',
        'kosher' => 'Kosher'
    ];
    
    // Experience options
    public $experienceOptions = [
        'beginner' => 'Mới bắt đầu',
        'intermediate' => 'Trung bình',
        'advanced' => 'Nâng cao'
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->profile = $this->user->profile;
        $this->loadProfileData();
        $this->loadStats();
    }

    public function loadProfileData()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->bio = $this->user->bio ?? '';
        $this->phone = $this->profile->phone ?? '';
        $this->address = $this->profile->address ?? '';
        $this->city = $this->profile->city ?? '';
        $this->country = $this->profile->country ?? 'Vietnam';
        $this->cooking_experience = $this->profile->cooking_experience ?? 'beginner';
        $this->dietary_preferences = $this->profile->dietary_preferences ?? [];
        $this->allergies = is_array($this->profile->allergies) ? implode(', ', $this->profile->allergies) : ($this->profile->allergies ?? '');
        $this->health_conditions = is_array($this->profile->health_conditions) ? implode(', ', $this->profile->health_conditions) : ($this->profile->health_conditions ?? '');
    }

    public function loadStats()
    {
        $this->recipesCount = Recipe::where('user_id', $this->user->id)->count();
        $this->collectionsCount = Collection::where('user_id', $this->user->id)->count();
        $this->favoritesCount = Favorite::where('user_id', $this->user->id)->count();
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
        if (!$this->isEditing) {
            $this->loadProfileData(); // Reset form
            $this->avatar = null; // Reset avatar
        }
    }

} 