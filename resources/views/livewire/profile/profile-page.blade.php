<div>
    <div class="min-h-screen bg-gray-50">
        <!-- Profile Info Section -->
        <div class="relative px-4 sm:px-6 lg:px-8 pt-8">
            <div class="max-w-10xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header Component -->
                    <x-profile.header :user="$user" :profile="$profile" :isEditing="$isEditing" :avatar="$avatar"
                        :experienceOptions="$experienceOptions" :nearestCity="$nearestCity" />

                    <!-- Action Buttons -->
                    <div class="px-6 sm:px-8 pb-6">
                        <div class="flex items-center space-x-3">
                            @if ($isEditing)
                                <button wire:click="saveProfile"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    Lưu thay đổi
                                </button>
                                <button wire:click="toggleEdit"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                                    Hủy
                                </button>
                            @else
                                <button wire:click="toggleEdit"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    Chỉnh sửa hồ sơ
                                </button>
                            @endif

                            <!-- Nút lấy vị trí -->
                            @if (!$nearestCity)
                                <button wire:click="getUserLocationFromBrowser"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Lấy vị trí của tôi
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Stats Component -->
                    <x-profile.stats :recipesCount="$recipesCount" :collectionsCount="$collectionsCount" :favoritesCount="$favoritesCount" />

                    <!-- Tabs Component -->
                    <x-profile.tabs :activeTab="$activeTab" :isEditing="$isEditing" />

                    <!-- Tab Content -->
                    <div class="p-6">
                        @if ($activeTab === 'recipes')
                            <x-profile.recipes-tab :recipes="$this->recipes" />
                        @endif

                        @if ($activeTab === 'collections')
                            <x-profile.collections-tab :collections="$this->collections" :showCreateModal="$showCreateModal" :newName="$newName"
                                :newDescription="$newDescription" :newIsPublic="$newIsPublic" :newCoverImage="$newCoverImage" :newCoverImagePreview="$newCoverImagePreview" />
                        @endif

                        @if ($activeTab === 'favorites')
                            <x-profile.favorites-tab :favorites="$this->favorites" />
                        @endif

                        @if ($activeTab === 'settings')
                            <x-profile.settings-tab :name="$name" :email="$email" :province="$province"
                                :bio="$bio" :phone="$phone" :address="$address" :city="$city"
                                :country="$country" :cooking_experience="$cooking_experience" :dietary_preferences="$dietary_preferences" :allergies="$allergies"
                                :health_conditions="$health_conditions" :experienceOptions="$experienceOptions" :dietaryOptions="$dietaryOptions" />
                        @endif

                        @if ($activeTab === 'vip')
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                                @if (!optional($profile)->isVipAccount)
                                    <div class="bg-white border rounded-lg p-4">
                                        <h3 class="font-semibold mb-3">Trạng thái VIP</h3>
                                        @if (optional($profile)->isVipAccount)
                                            <div class="p-3 bg-green-50 text-green-700 rounded">Tài khoản của bạn đang
                                                là VIP.</div>
                                        @else
                                            <div class="p-3 bg-yellow-50 text-yellow-700 rounded mb-3">Bạn chưa là VIP.
                                                Quét QR để thanh toán và nâng cấp.</div>
                                            <div class="border rounded p-3">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                                    <div class="text-center">
                                                        <img src="https://qr.sepay.vn/img?bank=MBBank&acc=0975821009&template=compact&amount=2000&des=DH<?= $profile->id ?>"
                                                            class="mx-auto w-[300px] opacity-80" alt="QR" />
                                                        <div class="text-xs text-gray-500 mt-2">Quét QR để thanh toán
                                                            nhanh</div>
                                                    </div>
                                                    <div
                                                        class="space-y-2 border-l pl-4 h-full flex flex-col justify-center items-left">
                                                        <h4 class="font-semibold">Thông tin chuyển khoản</h4>
                                                        <ul class="text-md text-gray-700 space-y-1 flex flex-col gap-2">
                                                            <li><span class="font-medium">Tên ngân hàng:</span> MBBank
                                                            </li>
                                                            <li><span class="font-medium">Số tài khoản:</span>
                                                                0975821009</li>
                                                            <li><span class="font-medium">Tên tài khoản:</span> PHAM
                                                                MINH THONG</li>
                                                            <li><span class="font-medium">Số tiền:</span> 2.000đ</li>
                                                            <li><span class="font-medium">Nội dung:</span>
                                                                DH<?= $profile->id ?></li>
                                                        </ul>
                                                        <div class="text-sm mt-2 text-gray-500">Sau khi thanh toán, hệ
                                                            thống sẽ tự động kích hoạt tài khoản qua webhook.</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-white border rounded-xl shadow-sm border-gray-200 overflow-hidden">
                                        <!-- Form Header -->
                                        <div
                                            class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-900">Quản lý cửa hàng</h3>
                                                    <p class="text-sm text-gray-600">Cập nhật thông tin cửa hàng của bạn
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Form Content -->
                                        <div class="p-6">
                                            <form method="POST" action="{{ route('me.shop.upsert') }}"
                                                class="space-y-6" enctype="multipart/form-data">
                                                @csrf

                                                <!-- Basic Information Section -->
                                                <div class="space-y-4">
                                                    <h4
                                                        class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-orange-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Thông tin cơ bản
                                                    </h4>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <!-- Shop Name -->
                                                        <div>
                                                            <label for="name"
                                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                                Tên cửa hàng <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="text" id="name" name="name"
                                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                                                                placeholder="Nhập tên cửa hàng"
                                                                value="{{ old('name', optional($user->shop ?? null)->name) }}"
                                                                required />
                                                            @error('name')
                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Phone Number -->
                                                        <div>
                                                            <label for="phone"
                                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                                Số điện thoại <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="tel" id="phone" name="phone"
                                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                                                                placeholder="Nhập số điện thoại"
                                                                value="{{ old('phone', optional($user->shop ?? null)->phone) }}"
                                                                required />
                                                            @error('phone')
                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- Address -->
                                                    <div>
                                                        <label for="address"
                                                            class="block text-sm font-medium text-gray-700 mb-2">
                                                            Địa chỉ <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="text" id="address" name="address"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                                                            placeholder="Nhập địa chỉ cửa hàng"
                                                            value="{{ old('address', optional($user->shop ?? null)->address) }}"
                                                            required />
                                                        @error('address')
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Website -->
                                                    <div>
                                                        <label for="website"
                                                            class="block text-sm font-medium text-gray-700 mb-2">
                                                            Website
                                                        </label>
                                                        <input type="url" id="website" name="website"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors"
                                                            placeholder="https://example.com"
                                                            value="{{ old('website', optional($user->shop ?? null)->website) }}" />
                                                        @error('website')
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Description -->
                                                    <div>
                                                        <label for="description"
                                                            class="block text-sm font-medium text-gray-700 mb-2">
                                                            Mô tả cửa hàng
                                                        </label>
                                                        <textarea id="description" name="description" rows="4"
                                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors resize-none"
                                                            placeholder="Mô tả chi tiết về cửa hàng, dịch vụ, đặc điểm nổi bật...">{{ old('description', optional($user->shop ?? null)->description) }}</textarea>
                                                        @error('description')
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Featured Image Section -->
                                                <div class="space-y-4">
                                                    <h4
                                                        class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-orange-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Hình ảnh đại diện
                                                    </h4>

                                                    <div class="flex items-center space-x-6">
                                                        <!-- Current Image Preview -->
                                                        <div class="flex-shrink-0">
                                                            @if (optional($user->shop ?? null)->featured_image)
                                                                <div
                                                                    class="w-24 h-24 rounded-lg overflow-hidden border-2 border-gray-200">
                                                                    <img src="{{ Storage::url(optional($user->shop ?? null)->featured_image) }}"
                                                                        class="w-full h-full object-cover"
                                                                        alt="Current featured image" />
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="w-24 h-24 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                                                    <svg class="w-8 h-8 text-gray-400" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- File Input -->
                                                        <div class="flex-1">
                                                            <label for="featured_image"
                                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                                Chọn hình ảnh mới
                                                            </label>
                                                            <input type="file" id="featured_image"
                                                                name="featured_image" accept="image/*"
                                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" />
                                                            <p class="mt-1 text-xs text-gray-500">Định dạng: JPG, PNG,
                                                                WEBP. Kích thước tối đa: 5MB</p>
                                                            @error('featured_image')
                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Shop Items Section -->
                                                <div class="space-y-4">
                                                    <h4
                                                        class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-orange-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                        </svg>
                                                        Mặt hàng bán
                                                    </h4>

                                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                        <p class="text-sm text-gray-600 mb-3">
                                                            Thêm các mặt hàng chính mà cửa hàng của bạn bán. Mỗi mặt
                                                            hàng cần có tên và giá.
                                                        </p>

                                                        <div id="shop-items-container" class="space-y-3">
                                                            @if (optional($user->shop ?? null)->shopItems && optional($user->shop ?? null)->shopItems->count() > 0)
                                                                @foreach (optional($user->shop ?? null)->shopItems as $index => $item)
                                                                    <div
                                                                        class="shop-item flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                                                        @if (isset($item->id))
                                                                            <input type="hidden"
                                                                                name="items[{{ $index }}][id]"
                                                                                value="{{ $item->id }}" />
                                                                        @endif
                                                                        <div class="flex-1">
                                                                            <input type="text"
                                                                                name="items[{{ $index }}][name]"
                                                                                class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                                                                placeholder="Tên mặt hàng"
                                                                                value="{{ $item->name ?? '' }}" />
                                                                        </div>
                                                                        <div class="w-32">
                                                                            <input type="number"
                                                                                name="items[{{ $index }}][price]"
                                                                                class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                                                                placeholder="Giá"
                                                                                value="{{ $item->price ?? '' }}" />
                                                                        </div>
                                                                        <div class="flex items-center gap-3">
                                                                            @if (!empty($item->featured_image))
                                                                                <img src="{{ Storage::url($item->featured_image) }}"
                                                                                    alt="Item image"
                                                                                    class="w-12 h-12 object-cover rounded border" />
                                                                            @endif
                                                                            <input type="file"
                                                                                name="items[{{ $index }}][featured_image]"
                                                                                accept="image/*" class="text-sm" />
                                                                        </div>
                                                                        <button type="button"
                                                                            class="remove-item text-red-500 hover:text-red-700 p-2">
                                                                            <svg class="w-5 h-5" fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>

                                                        <button type="button" id="add-item-btn"
                                                            class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                            </svg>
                                                            Thêm mặt hàng
                                                        </button>


                                                    </div>
                                                </div>

                                                <!-- Shop Status Section -->
                                                <div class="space-y-4">
                                                    <h4
                                                        class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-orange-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Trạng thái cửa hàng
                                                    </h4>

                                                    <div class="flex items-center">
                                                        <input type="checkbox" id="is_active" name="is_active"
                                                            value="1"
                                                            class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2"
                                                            {{ old('is_active', optional($user->shop ?? null)->is_active) ? 'checked' : '' }} />
                                                        <label for="is_active"
                                                            class="ml-3 text-sm font-medium text-gray-700">
                                                            Cửa hàng đang hoạt động
                                                        </label>
                                                    </div>
                                                    <p class="text-xs text-gray-500">Khi bỏ chọn, cửa hàng sẽ hiển thị
                                                        là "Tạm ngưng"</p>
                                                </div>

                                                <!-- Form Actions -->
                                                <div
                                                    class="flex items-center justify-between pt-6 border-t border-gray-200">
                                                    <div class="text-sm text-gray-500">
                                                        <span class="text-red-500">*</span> Thông tin bắt buộc
                                                    </div>
                                                    <div class="flex gap-3">
                                                        <button type="button"
                                                            class="px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                                            Hủy bỏ
                                                        </button>
                                                        <button type="submit" id="submit-shop-btn"
                                                            class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium flex items-center gap-2">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Lưu cửa hàng
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                            </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <x-flash-message />
    </div>

    <!-- Modal thông báo vị trí -->
    <div id="location-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Chia sẻ vị trí</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4">
                        Bạn có muốn chia sẻ vị trí hiện tại để nhận đề xuất món ăn phù hợp với thời tiết không?
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <button id="location-yes"
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-colors">
                            Có, chia sẻ
                        </button>
                        <button id="location-no"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                            Không, chọn ngẫu nhiên
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                // Hàm hiển thị modal
                function showLocationModal() {
                    const modal = document.getElementById('location-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                    }
                }

                // Hàm ẩn modal
                function hideLocationModal() {
                    const modal = document.getElementById('location-modal');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                }

                // Xử lý sự kiện click nút trong modal
                document.getElementById('location-yes')?.addEventListener('click', function() {
                    hideLocationModal();
                    // Lấy vị trí
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const latitude = position.coords.latitude;
                                const longitude = position.coords.longitude;
                                console.log('Location obtained:', latitude, longitude);

                                // Gửi tọa độ về Livewire component
                                @this.setUserLocation(latitude, longitude);
                            },
                            (error) => {
                                console.error('Geolocation error:', error);

                                // Khi người dùng từ chối vị trí, tự động chọn ngẫu nhiên
                                if (error.code === 1) { // PERMISSION_DENIED
                                    console.log('Người dùng từ chối vị trí, chọn ngẫu nhiên...');
                                    @this.randomCity();
                                } else {
                                    alert(
                                        'Không thể lấy vị trí của bạn. Vui lòng kiểm tra quyền truy cập vị trí.'
                                        );
                                    @this.randomCity();
                                }
                            }
                        );
                    } else {
                        alert('Trình duyệt của bạn không hỗ trợ định vị địa lý.');
                        @this.randomCity();
                    }
                });

                document.getElementById('location-no')?.addEventListener('click', function() {
                    hideLocationModal();
                    // Chọn ngẫu nhiên
                    console.log('Người dùng không muốn chia sẻ vị trí, chọn ngẫu nhiên...');
                    @this.randomCity();
                });

                // Tự động lấy vị trí khi component được load
                Livewire.on('auto-get-location', () => {
                    showLocationModal();
                });

                // Xử lý khi người dùng click nút lấy vị trí thủ công
                Livewire.on('get-user-location', () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const latitude = position.coords.latitude;
                                const longitude = position.coords.longitude;
                                console.log('Location obtained:', latitude, longitude);

                                // Gửi tọa độ về Livewire component
                                @this.setUserLocation(latitude, longitude);
                            },
                            (error) => {
                                console.error('Geolocation error:', error);

                                // Khi người dùng từ chối vị trí, tự động chọn ngẫu nhiên
                                if (error.code === 1) { // PERMISSION_DENIED
                                    console.log('Người dùng từ chối vị trí, chọn ngẫu nhiên...');
                                    @this.randomCity();
                                } else {
                                    alert(
                                        'Không thể lấy vị trí của bạn. Vui lòng kiểm tra quyền truy cập vị trí.'
                                        );
                                }
                            }
                        );
                    } else {
                        alert('Trình duyệt của bạn không hỗ trợ định vị địa lý.');
                    }
                });

                // Shop Items Management
                let itemIndex =
                    {{ optional($user->shop ?? null)->shopItems ? optional($user->shop ?? null)->shopItems->count() : 0 }};

                // Add new item (event delegation to survive Livewire DOM updates)
                document.addEventListener('click', function(e) {
                    if (e.target.closest('#add-item-btn')) {
                        console.log('Add item button clicked, current index:', itemIndex);
                        const container = document.getElementById('shop-items-container');
                        if (!container) {
                            console.error('Shop items container not found');
                            return;
                        }
                        const newItem = document.createElement('div');
                        newItem.className =
                            'shop-item flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200';
                        newItem.innerHTML = `
                    <div class="flex-1">
                        <input type="text" 
                               name="items[${itemIndex}][name]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent" 
                               placeholder="Tên mặt hàng" 
                               required/>
                    </div>
                    <div class="w-32">
                        <input type="number" 
                               name="items[${itemIndex}][price]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-orange-500 focus:border-transparent" 
                               placeholder="Giá" 
                               min="0" 
                               step="1000"/>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="file" name="items[${itemIndex}][featured_image]" accept="image/*" class="text-sm" />
                    </div>
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                `;
                        container.appendChild(newItem);
                        console.log('New item added with index:', itemIndex);
                        itemIndex++;
                    }
                });

                // Remove item (event delegation)
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-item')) {
                        const shopItem = e.target.closest('.shop-item');
                        if (shopItem) {
                            shopItem.remove();
                            console.log('Shop item removed');
                        }
                    }
                });

                // Initialize existing remove buttons
                document.addEventListener('DOMContentLoaded', function() {
                    const existingRemoveBtns = document.querySelectorAll('.remove-item');
                    existingRemoveBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const shopItem = this.closest('.shop-item');
                            if (shopItem) {
                                shopItem.remove();
                                console.log('Existing shop item removed');
                            }
                        });
                    });
                });

                // Form validation
                document.querySelector('form[action*="shop.upsert"]')?.addEventListener('submit', function(e) {
                    console.log('Form submission started');

                    const requiredFields = this.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                            field.classList.remove('border-gray-300');
                            console.log('Required field empty:', field.name);
                        } else {
                            field.classList.remove('border-red-500');
                            field.classList.add('border-gray-300');
                        }
                    });

                    // Validate shop items
                    const shopItems = this.querySelectorAll('.shop-item');
                    if (shopItems.length > 0) {
                        shopItems.forEach((item, index) => {
                            const nameInput = item.querySelector('input[name*="[name]"]');
                            if (nameInput && !nameInput.value.trim()) {
                                isValid = false;
                                nameInput.classList.add('border-red-500');
                                console.log('Shop item name empty at index:', index);
                            }
                        });
                    }

                    if (!isValid) {
                        e.preventDefault();
                        alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
                        console.log('Form validation failed');
                        return false;
                    }

                    console.log('Form validation passed, submitting...');
                    return true;
                });

                // Debug button functionality (event delegation)
                document.addEventListener('click', function(e) {
                    if (e.target.closest('#debug-btn')) {
                        console.log('=== DEBUG INFO ===');
                        console.log('Current item index:', itemIndex);
                        console.log('Shop items container:', document.getElementById('shop-items-container'));
                        console.log('Add item button:', document.getElementById('add-item-btn'));
                        console.log('Number of existing items:', document.querySelectorAll('.shop-item')
                            .length);
                        console.log('Form element:', document.querySelector('form[action*="shop.upsert"]'));
                        console.log('==================');
                    }
                });

                // Debug: Log when script loads
                console.log('Shop management script loaded');
                console.log('Initial item index:', itemIndex);
                console.log('Shop items container found:', !!document.getElementById('shop-items-container'));
                console.log('Add item button found:', !!document.getElementById('add-item-btn'));
            });
        </script>
    </div>
