<div class="recipe-card">
  <div class="">
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

        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-2">
                @if($recipe->user->profile && $recipe->user->profile->avatar)
                    <img src="{{ Storage::url($recipe->user->profile->avatar) }}" 
                         alt="{{ $recipe->user->name }}" 
                         class="w-6 h-6 rounded-full object-cover" />
                @else
                    <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center">
                        <span class="text-xs font-medium text-orange-600">
                            {{ strtoupper(substr($recipe->user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <span class="text-sm text-gray-600">{{ $recipe->user->name }}</span>
            </div>

            <div class="flex items-center space-x-1">
                <svg class="h-4 w-4 text-yellow-400" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-sm text-gray-600">{{ number_format($recipe->average_rating, 1) }}</span>
                <span class="text-xs text-gray-400">({{ $recipe->rating_count }})</span>
            </div>
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