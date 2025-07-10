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
        <h3 class="text-lg font-semibold text-gray-800 hover:text-gray-600 transition-colors duration-300">
            <a href="{{ route('recipes.show', $recipe->slug) }}">{{ $recipe->title }}</a>
        </h3>
        <p class="text-sm text-gray-600 mt-1">
            {{ Str::limit($recipe->description, 100) }}
        </p>  
        <div class="mt-2 flex items-center text-sm text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13H9v6h2V5zm0 8H9v2h2v-2z" />
            </svg>
            {{ $recipe->created_at->diffForHumans() }}
        </div>  
        <div class="mt-2 flex items-center text-sm text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13H9v6h2V5zm0 8H9v2h2v-2z" />
            </svg>
            {{ $recipe->views }} lượt xem
        </div>

        <!-- Categories -->
        @if($recipe->categories->count() > 0)
            <div class="mt-3 flex flex-wrap gap-1">
                @foreach($recipe->categories->take(2) as $category)
                    <span class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded-full">
                        {{ $category->name }}
                    </span>
                @endforeach
                @if($recipe->categories->count() > 2)
                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                        +{{ $recipe->categories->count() - 2 }}
                    </span>
                @endif
            </div>
        @endif
    </div>
</div> 