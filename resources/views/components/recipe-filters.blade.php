@props(['categories', 'tags', 'filters'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
  <div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
    <div class="relative">
      <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Tìm kiếm công thức...">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2.25-4.5a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
        </svg>
      </div>
    </div>

    <!-- Sort Options -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Sắp xếp</label>
        <select wire:model.live="sort" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            <option value="latest">Mới nhất</option>
            <option value="popular">Phổ biến</option>
            <option value="rating">Đánh giá cao</option>
            <option value="cooking_time">Thời gian nấu</option>
            <option value="title">Tên A-Z</option>
        </select>
    </div>

    <!-- Category Filter -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
        <select wire:model.live="category" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                @foreach($cat->children as $child)
                    <option value="{{ $child->slug }}">— {{ $child->name }}</option>
                @endforeach
            @endforeach
        </select>
    </div>

    <!-- Difficulty Filter -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Độ khó</label>
        <select wire:model.live="difficulty" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            <option value="">Tất cả độ khó</option>
            <option value="easy">Dễ</option>
            <option value="medium">Trung bình</option>
            <option value="hard">Khó</option>
        </select>
    </div>

    <!-- Advanced Filters -->
    @if($filters['showAdvancedFilters'] ?? false)
        <div class="space-y-4 border-t border-gray-200 pt-4">
            <!-- Min Rating -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Đánh giá tối thiểu</label>
                <select wire:model.live="minRating" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Tất cả</option>
                    <option value="4">4+ sao</option>
                    <option value="3">3+ sao</option>
                    <option value="2">2+ sao</option>
                </select>
            </div>
          </div>
    @endif

    <!-- Tags Filter -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Tags phổ biến</label>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach($tags as $tag)
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model.live="selectedTags" 
                        value="{{ $tag->id }}"
                        class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

  </div>


</div>