@props(['recipe'])
<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group" data-recipe-slug="{{ $recipe->slug }}" data-recipe-id="{{ $recipe->id }}">

    <div class="aspect-video bg-gray-200 relative overflow-hidden">
        @if($recipe->featured_image)
            <img src="{{ Storage::url($recipe->featured_image) }}" 
                 alt="{{ $recipe->title }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-red-100">
                <svg class="w-12 h-12 text-orange-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h6" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
            </div>
        @endif

        <!-- Remove Favorite Button -->
        <div class="absolute top-3 right-3">
            @if (isset($removeButton))
                {{ $removeButton }}
            @else
                <button type="button" class="text-red-500 hover:text-red-700 transition-colors duration-200" 
                        wire:click.prevent="removeFromFavorites({{ $recipe->id }})">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>  
        
        <!-- Difficulty Badge -->
        <div class="absolute top-2 left-2">
            @php
                $difficultyColors = [
                    'easy' => 'bg-green-500',
                    'medium' => 'bg-yellow-500', 
                    'hard' => 'bg-red-500'
                ];
                $difficultyText = [
                    'easy' => 'Dễ',
                    'medium' => 'Trung bình',
                    'hard' => 'Khó'
                ];
            @endphp    
            <span class="px-3 py-1 text-xs font-medium text-white rounded-full {{ $difficultyColors[$recipe->difficulty] }}">
                {{ $difficultyText[$recipe->difficulty] }}
            </span>
        </div>
    </div>

    <div class="p-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">
            {{ $recipe->title }}
        </h2>
        <p class="text-gray-600 text-sm">
            {{ $recipe->description }}
        </p>
    </div>
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center text-sm text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-3H9V7h2v3z"/>
            </svg>
            <span>{{ $recipe->views }} lượt xem</span>    
        </div>
    </div>
</div>