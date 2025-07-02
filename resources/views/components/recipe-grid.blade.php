@props([
  'recipes',
  'title' => 'Công thức mới nhất',
  'subtitle' => null,
  'viewMode' => 'grid',
  'hasActiveFilters' => false,
  'difficulty' => '',
  'cookingTime' => ''
])

<section class="py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-gray-600 mt-2">{{ $subtitle }}</p>
                @endif
            </div>

            <!-- Filter Controls -->
            <div class="flex flex-wrap gap-4">
                <!-- Sort Dropdown -->
                <div class="relative">
                    <select 
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500"
                        wire:model="sortBy"
                    >
                        <option value="latest">Mới nhất</option>
                        <option value="popular">Phổ biến</option>
                        <option value="rating">Đánh giá cao</option>
                        <option value="oldest">Cũ nhất</option>
                    </select>

                    <!-- Difficulty Filter -->
                    <div>
                        <select 
                            class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500"
                            wire:model="difficulty"
                        >
                            <option value="">Tất cả độ khó</option>
                            <option value="easy">Dễ</option>
                            <option value="medium">Trung bình</option>
                            <option value="hard">Khó</option>
                        </select>
                    </div>
                <!-- Cooking Time Filter -->
                <select 
                    class="border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    wire:model.live="cookingTime"
                >
                    <option value="">Tất cả thời gian</option>
                    <option value="quick">Nhanh (< 30 phút)</option>
                    <option value="medium">Trung bình (30-60 phút)</option>
                    <option value="long">Lâu (> 60 phút)</option>
                </select>
                </div>

                <!-- Active Filters -->
                @if($hasActiveFilters)
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Lọc theo:</span>
                        @if($difficulty)
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs">
                                Độ khó: {{ ucfirst($difficulty) }}
                            </span>
                        @endif
                        @if($cookingTime)
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs">
                                Thời gian: {{ ucfirst($cookingTime) }}
                            </span>
                        @endif
                    </div>
                @endif

            </div>

        </div>
  </div>
</section>