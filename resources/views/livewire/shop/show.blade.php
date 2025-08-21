<div>
    <style>
        .leaflet-container {
            height: 400px !important;
            width: 100% !important;
            border-radius: 0.5rem;
            z-index: 1;
        }
        
        #shop-map {
            min-height: 400px;
            background-color: #f3f4f6;
        }
        .map-container {
            position: relative;
        }
        .map-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            font-size: 12px;
            color: #666;
        }
    </style>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('shops.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-orange-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Cửa hàng
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $shop->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Main Shop Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <!-- Hero Section -->
        <div class="relative h-80 w-full overflow-hidden">
            @if($shop->featured_image)
                <img src="{{ Storage::url($shop->featured_image) }}" 
                     class="w-full h-full object-cover" 
                     alt="{{ $shop->name }}"/>
            @else
                <div class="w-full h-full bg-gradient-to-br from-orange-100 via-orange-200 to-orange-300 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 text-orange-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-orange-600 font-medium">Không có hình ảnh</p>
                    </div>
                </div>
            @endif
            
            <!-- Status Badge -->
            <div class="absolute top-4 right-4">
                @if($shop->is_active)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 shadow-sm">
                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3"/>
                        </svg>
                        Đang hoạt động
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 shadow-sm">
                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3"/>
                        </svg>
                        Tạm ngưng
                    </span>
                @endif
            </div>

            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
            
            <!-- Shop Info Overlay -->
            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">{{ $shop->name }}</h1>
                @if($shop->address)
                    <p class="text-lg text-gray-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $shop->address }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Shop Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Description -->
                    @if($shop->description)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Mô tả
                            </h2>
                            <div class="prose max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($shop->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Items Section -->
                    @if($shop->shopItems && $shop->shopItems->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Danh sách mặt hàng
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($shop->shopItems as $index => $item)
                                    <div class="bg-white rounded-lg border border-gray-200 hover:shadow-sm transition-shadow overflow-hidden">
                                        @if(!empty($item->featured_image))
                                            <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->name }}" class="w-full h-40 object-cover" />
                                        @endif
                                        <div class="p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="font-semibold text-gray-900">{{ $item->name ?? 'Tên không xác định' }}</h3>
                                                <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full border">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </div>
                                            @if(!is_null($item->price) && $item->price > 0)
                                                <div class="text-2xl font-bold text-orange-600">
                                                    {{ number_format((float)$item->price) }} đ
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500 italic">Giá liên hệ</div>
                                            @endif
                                            @if(!empty($item->description))
                                                <p class="text-sm text-gray-600 mt-2">{{ $item->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Contact Information -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Thông tin liên hệ
                        </h3>
                        
                        <div class="space-y-4">
                            @if($shop->phone)
                                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Điện thoại</p>
                                        <a href="tel:{{ $shop->phone }}" class="text-gray-900 font-medium hover:text-orange-600 transition-colors">
                                            {{ $shop->phone }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($shop->website)
                                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Website</p>
                                        <a href="{{ $shop->website }}" target="_blank" rel="noopener noreferrer" 
                                           class="text-orange-600 font-medium hover:text-orange-700 transition-colors break-all">
                                            {{ $shop->website }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($shop->address)
                                <div class="flex items-start gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Địa chỉ</p>
                                        <p class="text-gray-900 font-medium">{{ $shop->address }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shop Stats -->
                    <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
                        <h3 class="text-lg font-semibold text-orange-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Thống kê
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-orange-700">Trạng thái:</span>
                                <span class="text-sm font-medium text-orange-900">
                                    {{ $shop->is_active ? 'Hoạt động' : 'Tạm ngưng' }}
                                </span>
                            </div>
                            
                            @if($shop->shopItems && $shop->shopItems->count() > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-orange-700">Số mặt hàng:</span>
                                    <span class="text-sm font-medium text-orange-900">{{ $shop->shopItems->count() }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-orange-700">Ngày tạo:</span>
                                <span class="text-sm font-medium text-orange-900">{{ $shop->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    @if($shop->latitude && $shop->longitude)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"/>
                    </svg>
                    Vị trí cửa hàng
                </h2>
                
                <div class="map-container">
                    <div id="shop-map" class="leaflet-container"></div>
                    <div class="map-overlay">
                        <strong>Tọa độ:</strong><br>
                        {{ number_format($shop->latitude, 6) }}, {{ number_format($shop->longitude, 6) }}
                    </div>
                </div>
                
                <!-- Debug Information Display -->
                <!-- <div id="map-debug" class="mt-4 p-4 bg-gray-100 rounded-lg border border-gray-300 font-mono text-sm">
                    <h4 class="font-semibold text-gray-800 mb-2">Debug Information:</h4>
                    <div id="debug-content" class="space-y-2">
                        <div class="text-gray-600">Loading debug information...</div>
                    </div>
                </div> -->
                
                @if($shop->address)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Địa chỉ chi tiết:</p>
                                <p class="text-gray-900">{{ $shop->address }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('shops.index') }}" 
           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại danh sách
        </a>
        
        @if($shop->phone)
            <a href="tel:{{ $shop->phone }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Gọi ngay
            </a>
        @endif
        
        @if($shop->website)
            <a href="{{ $shop->website }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                Truy cập website
            </a>
        @endif
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Client-side JavaScript - this runs in the browser
document.addEventListener('livewire:init', function() {
    // Debug function to update the debug display
    function updateDebug(message, type = 'info') {
        const debugContent = document.getElementById('debug-content');
        if (debugContent) {
            const timestamp = new Date().toLocaleTimeString();
            const colorClass = type === 'error' ? 'text-red-600' : type === 'success' ? 'text-green-600' : 'text-blue-600';
            
            const debugLine = document.createElement('div');
            debugLine.className = `flex items-start gap-2 ${colorClass}`;
            debugLine.innerHTML = `
                <span class="text-gray-500 text-xs">[${timestamp}]</span>
                <span>${message}</span>
            `;
            
            debugContent.appendChild(debugLine);
            
            // Keep only last 20 debug lines
            const lines = debugContent.children;
            if (lines.length > 20) {
                debugContent.removeChild(lines[0]);
            }
        }
    }
    
    // Get shop data from PHP variables (processed server-side)
    updateDebug('Starting map initialization...', 'info');
    
    const shopData = {
        name: '{{ $shop->name }}',
        address: '{{ $shop->address ?? "" }}',
        phone: '{{ $shop->phone ?? "" }}',
        latitude: {{ $shop->latitude ?? 'null' }},
        longitude: {{ $shop->longitude ?? 'null' }}
    };
    
    updateDebug(`Shop data loaded: ${shopData.name}`, 'info');
    updateDebug(`Coordinates: ${shopData.latitude}, ${shopData.longitude}`, 'info');
    
    // Check if we have coordinates
    if (shopData.latitude && shopData.longitude) {
        updateDebug('Coordinates found, initializing map...', 'success');
        // Initialize map when DOM is ready
        initializeMap(shopData, updateDebug);
    } else {
        updateDebug('No coordinates available for shop', 'error');
        console.log('No coordinates available for shop');
    }
});

function initializeMap(shopData, updateDebug) {
    // Wait for both DOM and Leaflet to be ready
    function initMap() {
        const mapContainer = document.getElementById('shop-map');
        if (!mapContainer) {
            updateDebug('Map container not found, retrying...', 'info');
            setTimeout(initMap, 100);
            return;
        }
        
        updateDebug('Map container found', 'success');
        
        if (typeof L === 'undefined') {
            updateDebug('Leaflet not loaded, retrying...', 'info');
            setTimeout(initMap, 100);
            return;
        }
        
        updateDebug('Leaflet loaded, creating map...', 'success');
        
        try {
            // Create the map
            const map = L.map('shop-map').setView([shopData.latitude, shopData.longitude], 15);
            updateDebug('Map created successfully', 'success');
            
            // Add map tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);
            updateDebug('Map tiles added', 'success');
            
            // Add marker
            const marker = L.marker([shopData.latitude, shopData.longitude]).addTo(map);
            updateDebug('Marker added', 'success');
            
            // Add popup
            let popupHtml = '<div style="text-align: center;"><strong>' + shopData.name + '</strong>';
            if (shopData.address) {
                popupHtml += '<br><small>' + shopData.address + '</small>';
            }
            if (shopData.phone) {
                popupHtml += '<br><a href="tel:' + shopData.phone + '">📞 ' + shopData.phone + '</a>';
            }
            popupHtml += '</div>';
            
            marker.bindPopup(popupHtml);
            updateDebug('Popup added', 'success');
            
            // Add circle
            L.circle([shopData.latitude, shopData.longitude], {
                color: '#f97316',
                fillColor: '#f97316',
                fillOpacity: 0.1,
                radius: 500
            }).addTo(map);
            updateDebug('Circle added', 'success');
            
            // Add controls
            L.control.zoom().addTo(map);
            L.control.scale({metric: true, imperial: false}).addTo(map);
            updateDebug('Controls added', 'success');
            
            // Refresh map
            setTimeout(() => {
                map.invalidateSize();
                updateDebug('Map refresh completed - Map is ready!', 'success');
            }, 200);
            
        } catch (error) {
            updateDebug(`Error creating map: ${error.message}`, 'error');
            console.error('Error creating map:', error);
        }
    }
    
    // Start initialization
    updateDebug('Starting map initialization process...', 'info');
    initMap();
}
</script>


