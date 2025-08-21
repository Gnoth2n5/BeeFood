@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Cửa hàng & Nhà hàng của BeeFood</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Khám phá những địa điểm ẩm thực chất lượng cao với đánh giá từ cộng đồng</p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex-1 width-full">
                @livewire('shop-quick-search')
            </div>
            <div class="flex gap-3">
                
            </div>
        </div>
    </div>

    <!-- Shops Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($shops as $shop)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 overflow-hidden group">
                <!-- Image Section -->
                <div class="relative h-48 w-full overflow-hidden">
                    @if($shop->featured_image)
                        <img src="{{ Storage::url($shop->featured_image) }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                             alt="{{ $shop->name }}"/>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if($shop->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Tạm ngưng
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">
                            {{ $shop->name }}
                        </h3>
                        
                        <!-- Address -->
                        @if($shop->address)
                            <div class="flex items-start gap-2 text-gray-600 mb-2">
                                <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm">{{ $shop->address }}</span>
                            </div>
                        @endif

                        <!-- Phone -->
                        @if($shop->phone)
                            <div class="flex items-center gap-2 text-gray-600 mb-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-sm">{{ $shop->phone }}</span>
                            </div>
                        @endif

                        <!-- Website -->
                        @if($shop->website)
                            <div class="flex items-center gap-2 text-gray-600 mb-3">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <span class="text-sm text-orange-600 hover:text-orange-700 underline">{{ $shop->website }}</span>
                            </div>
                        @endif

                        <!-- Description Preview -->
                        @if($shop->description)
                            <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($shop->description, 120) }}</p>
                        @endif
                    </div>

                    <!-- Items Preview -->
                    @if($shop->shopItems && $shop->shopItems->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Mặt hàng nổi bật:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($shop->shopItems->take(3) as $item)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ $item->name ?? 'N/A' }}
                                    </span>
                                @endforeach
                                @if($shop->shopItems->count() > 3)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        +{{ $shop->shopItems->count() - 3 }} khác
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-gray-100">
                        <a href="{{ route('shops.show', $shop->slug) }}" 
                           class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 group-hover:shadow-md">
                            <span>Xem chi tiết</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($shops->hasPages())
        <div class="mt-12 flex justify-center">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-2">
                {{ $shops->links() }}
            </div>
        </div>
    @endif

    <!-- Empty State -->
    @if($shops->count() === 0)
        <div class="text-center py-16">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Chưa có cửa hàng nào</h3>
            <p class="mt-2 text-gray-500">Hãy quay lại sau để xem các cửa hàng mới.</p>
        </div>
    @endif
</div>
@endsection


