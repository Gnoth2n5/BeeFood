<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-gray-700">Trang chủ</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('recipes.index') }}" class="hover:text-gray-700">Công thức</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-gray-700">{{ Str::limit($recipe->title, 30) }}</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Chỉnh sửa</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Chỉnh Sửa Công Thức</h1>
                    <p class="mt-2 text-gray-600">Cập nhật thông tin công thức của bạn</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('recipes.show', $recipe) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Xem công thức
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if($showSuccessMessage)
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Thành công!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>Công thức của bạn đã được cập nhật thành công và đang chờ duyệt lại. Bạn sẽ được thông báo khi công thức được phê duyệt.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Lỗi!</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-8">
            <!-- Basic Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Thông Tin Cơ Bản</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tiêu đề công thức <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               wire:model="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Nhập tiêu đề công thức">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Summary -->
                    <div class="md:col-span-2">
                        <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">
                            Tóm tắt <span class="text-red-500">*</span>
                        </label>
                        <textarea id="summary" 
                                  wire:model="summary" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Mô tả ngắn gọn về công thức"></textarea>
                        @error('summary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Mô tả chi tiết <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" 
                                  wire:model="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Mô tả chi tiết về công thức, nguyên liệu chính, hương vị..."></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Cooking Time -->
                    <div>
                        <label for="cooking_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Thời gian nấu (phút) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="cooking_time" 
                               wire:model="cooking_time" 
                               min="5" 
                               max="1440"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('cooking_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Preparation Time -->
                    <div>
                        <label for="preparation_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Thời gian chuẩn bị (phút) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="preparation_time" 
                               wire:model="preparation_time" 
                               min="0" 
                               max="1440"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('preparation_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Difficulty -->
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                            Độ khó <span class="text-red-500">*</span>
                        </label>
                        <select id="difficulty" 
                                wire:model="difficulty"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($difficultyOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('difficulty') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Servings -->
                    <div>
                        <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">
                            Số khẩu phần <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="servings" 
                               wire:model="servings" 
                               min="1" 
                               max="50"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('servings') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Calories -->
                    <div>
                        <label for="calories_per_serving" class="block text-sm font-medium text-gray-700 mb-2">
                            Calo mỗi khẩu phần
                        </label>
                        <input type="number" 
                               id="calories_per_serving" 
                               wire:model="calories_per_serving" 
                               min="0" 
                               max="5000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Tùy chọn">
                        @error('calories_per_serving') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Categories and Tags -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Danh Mục và Tags</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Danh mục <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model="category_ids" 
                                           value="{{ $category->id }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tags -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($tags as $tag)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model="tag_ids" 
                                           value="{{ $tag->id }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('tag_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Ingredients -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Nguyên Liệu</h2>
                    <button type="button" 
                            wire:click="addIngredient"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Thêm nguyên liệu
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($ingredients as $index => $ingredient)
                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                            <div class="flex-1 grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên</label>
                                    <input type="text" 
                                           wire:model="ingredients.{{ $index }}.name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Tên nguyên liệu">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
                                    <input type="text" 
                                           wire:model="ingredients.{{ $index }}.amount"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Số lượng">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Đơn vị</label>
                                    <input type="text" 
                                           wire:model="ingredients.{{ $index }}.unit"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Đơn vị">
                                </div>
                            </div>
                            @if(count($ingredients) > 1)
                                <button type="button" 
                                        wire:click="removeIngredient({{ $index }})"
                                        class="text-red-600 hover:text-red-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                @error('ingredients') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Instructions -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Hướng Dẫn Nấu</h2>
                    <button type="button" 
                            wire:click="addInstruction"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Thêm bước
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($instructions as $index => $instruction)
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">
                                {{ $instruction['step'] }}
                            </div>
                            <div class="flex-1">
                                <textarea wire:model="instructions.{{ $index }}.instruction"
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Mô tả bước {{ $instruction['step'] }}"></textarea>
                            </div>
                            @if(count($instructions) > 1)
                                <button type="button" 
                                        wire:click="removeInstruction({{ $index }})"
                                        class="text-red-600 hover:text-red-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                @error('instructions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Additional Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Thông Tin Bổ Sung</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tips -->
                    <div>
                        <label for="tips" class="block text-sm font-medium text-gray-700 mb-2">
                            Mẹo nấu ăn
                        </label>
                        <textarea id="tips" 
                                  wire:model="tips" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Chia sẻ mẹo nấu ăn của bạn"></textarea>
                        @error('tips') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="notes" 
                                  wire:model="notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Ghi chú thêm về công thức"></textarea>
                        @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Video URL -->
                    <div>
                        <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Video (YouTube, Vimeo...)
                        </label>
                        <input type="url" 
                               id="video_url" 
                               wire:model="video_url" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://youtube.com/watch?v=...">
                        @error('video_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Featured Image -->
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Hình ảnh chính
                        </label>
                        
                        <!-- Current Image Display -->
                        @if($originalImage)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-2">Hình ảnh hiện tại:</p>
                                <img src="{{ asset('storage/' . $originalImage) }}" 
                                     alt="Current featured image" 
                                     class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            </div>
                        @endif
                        
                        <input type="file" 
                               id="featured_image" 
                               wire:model="featured_image"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('featured_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <p class="mt-1 text-sm text-gray-500">Định dạng: JPEG, PNG, JPG, GIF, WebP. Tối đa 2MB. Để trống nếu không muốn thay đổi.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6">
                <button type="button" 
                        wire:click="resetForm"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Khôi phục
                </button>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('recipes.show', $recipe) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Hủy
                    </a>

                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="save">Cập Nhật Công Thức</span>
                        <span wire:loading wire:target="save">Đang cập nhật...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript for redirect after update -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('recipe-updated', (event) => {
                setTimeout(() => {
                    window.location.href = `/recipes/${event.recipeId}`;
                }, 2000);
            });
        });
    </script>
</div>
