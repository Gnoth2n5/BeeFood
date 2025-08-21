<div class="relative">
	<div class="relative">
		<input
			type="text"
			wire:model.live.debounce.300ms="searchQuery"
			placeholder="Tìm cửa hàng hoặc mặt hàng..."
			class="w-full pl-12 pr-20 py-4 text-gray-900 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg shadow-sm"
			wire:focus="showSuggestions = true"
			wire:keydown.enter="goToSearchPage"
		>
		<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
			<svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
			</svg>
		</div>

		<button 
			wire:click="goToSearchPage"
			class="absolute inset-y-0 right-0 px-4 flex items-center bg-orange-500 hover:bg-orange-600 text-white rounded-r-lg transition-colors"
		>
			<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
			</svg>
		</button>

		@if($searchQuery)
			<button 
				wire:click="clearSearch"
				class="absolute inset-y-0 right-12 pr-2 flex items-center text-gray-400 hover:text-gray-600"
			>
				<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
				</svg>
			</button>
		@endif
	</div>

	@if($showSuggestions)
		<div 
			class="absolute z-50 w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto"
			wire:click.away="showSuggestions = false"
		>
			@if(!empty($searchQuery) && ($suggestions['shops']->count() > 0 || $suggestions['items']->count() > 0))
				<div class="p-4 space-y-4">
					@if($suggestions['shops']->count() > 0)
						<div>
							<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cửa hàng</h4>
							<div class="space-y-2">
								@foreach($suggestions['shops'] as $shop)
									<a href="{{ route('shops.show', $shop->slug) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors" wire:click="showSuggestions = false">
										@if($shop->featured_image)
											<img src="{{ Storage::url($shop->featured_image) }}" alt="{{ $shop->name }}" class="w-10 h-10 object-cover rounded-lg">
										@else
											<div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
												<svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l1.664 9.152A2 2 0 006.64 18h10.72a2 2 0 001.976-1.848L21 7m-9 4h.01M4 7h16l-2 4H6L4 7z" />
												</svg>
											</div>
										@endif
										<div class="min-w-0">
											<p class="text-sm font-medium text-gray-900 truncate">{{ $shop->name }}</p>
											<p class="text-xs text-gray-500 truncate">{{ $shop->address }}</p>
										</div>
									</a>
								@endforeach
							</div>
						</div>
					@endif

					@if($suggestions['items']->count() > 0)
						<div>
							<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mặt hàng</h4>
							<div class="space-y-2">
								@foreach($suggestions['items'] as $item)
									<a href="{{ route('shops.show', $item->userShop->slug) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors" wire:click="showSuggestions = false">
										@if($item->featured_image)
											<img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->name }}" class="w-10 h-10 object-cover rounded-lg">
										@else
											<div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
												<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
											</svg>
											</div>
										@endif
										<div class="min-w-0">
											<p class="text-sm font-medium text-gray-900 truncate">{{ $item->name }}</p>
											<p class="text-xs text-gray-500 truncate">{{ $item->userShop->name }}</p>
										</div>
									</a>
								@endforeach
							</div>
						</div>
					@endif
				</div>
			@elseif(!empty($searchQuery))
				<div class="p-6 text-center">
					<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
					<h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy kết quả</h3>
					<p class="mt-1 text-sm text-gray-500">Thử từ khóa khác</p>
				</div>
			@endif
		</div>
	@endif
</div>


