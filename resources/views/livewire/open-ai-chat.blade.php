<div id="chat-container" class="max-w-4xl mx-auto">
    <!-- Chat Header -->
    <div class="bg-white rounded-t-lg border border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900">AI Cooking Assistant</h3>
                    <p class="text-sm text-gray-500">Sẵn sàng hỗ trợ bạn nấu ăn</p>
                </div>
            </div>
            
            <!-- Chat Actions -->
            <div class="flex items-center space-x-2">
                @if($hasConversation)
                    <button wire:click="toggleHistory" 
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                    <button wire:click="clearConversation" 
                            wire:confirm="Bạn có chắc muốn xóa toàn bộ lịch sử trò chuyện?"
                            class="p-2 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
        
        <!-- Conversation Stats -->
        @if($hasConversation && $showHistory)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>Tổng tin nhắn: {{ $conversationStats['total_messages'] }}</span>
                    <span>Bạn: {{ $conversationStats['user_messages'] }} | AI: {{ $conversationStats['ai_messages'] }}</span>
                </div>
            </div>
        @endif
    </div>

    

    <!-- Ingredients Section (shown when ingredients category is selected) -->
    @if($selectedCategory === 'ingredients')
        <div class="bg-white border-l border-r border-gray-200 px-6 py-4">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Nguyên liệu có sẵn:</h4>
            
            <!-- Add Ingredient -->
            <div class="flex gap-2 mb-3">
                <input type="text" 
                       wire:model="newIngredient"
                       wire:keydown.enter="addIngredient"
                       placeholder="Thêm nguyên liệu..."
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                <button wire:click="addIngredient" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
            </div>

            <!-- Ingredients List -->
            @if(count($ingredients) > 0)
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($ingredients as $index => $ingredient)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-orange-100 text-orange-800">
                            {{ $ingredient }}
                            <button wire:click="removeIngredient({{ $index }})" 
                                    class="ml-2 hover:text-orange-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                    @endforeach
                </div>
                <button wire:click="getRecipeSuggestions" 
                        class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                    Gợi ý món ăn từ nguyên liệu này
                </button>
            @else
                <p class="text-sm text-gray-500 italic">Chưa có nguyên liệu nào. Hãy thêm nguyên liệu để nhận gợi ý!</p>
            @endif
        </div>
    @endif

   

    <!-- Chat Messages -->
    <div class="bg-white border-l border-r border-gray-200">
        <div id="chat-messages" class="h-96 overflow-y-auto p-6 space-y-4">
            @if(count($conversation) === 0)
                <!-- Welcome Message -->
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Xin chào! Tôi là trợ lý AI nấu ăn</h3>
                    <p class="text-gray-600">Hãy hỏi tôi nếu bạn cần đề xuất món ăn dựa theo nhu cầu của bạn!</p>
                </div>
            @else
                @foreach($conversation as $message)
                    <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="flex max-w-xs lg:max-w-lg {{ $message['role'] === 'user' ? 'flex-row-reverse' : 'flex-row' }}">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full {{ $message['role'] === 'user' ? 'bg-gray-300 ml-3' : 'bg-orange-500 mr-3' }} flex items-center justify-center">
                                    @if($message['role'] === 'user')
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="px-4 py-2 rounded-lg {{ $message['role'] === 'user' ? 'bg-orange-600 text-white' : ($message['is_error'] ?? false ? 'bg-red-50 text-red-800 border border-red-200' : 'bg-gray-100 text-gray-900') }}">
                                    @if($message['role'] === 'assistant' && isset($message['content_html']))
                                        <div class="text-sm markdown-content">
                                            {!! $message['content_html'] !!}
                                        </div>
                                    @else
                                        <p class="text-sm whitespace-pre-wrap">{{ $message['content'] }}</p>
                                    @endif
                                    
                                    <!-- Recipe Suggestions -->
                                    @if($message['role'] === 'assistant' && isset($message['recipes']) && !empty($message['recipes']))
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Công thức được nhắc đến:</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach($message['recipes'] as $recipe)
                                                    <div class="recipe-card bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                                                        <div class="relative h-32 bg-gray-100">
                                                            @if($recipe['featured_image'] && file_exists(storage_path('app/public/' . $recipe['featured_image'])))
                                                                <img src="{{ asset('storage/' . $recipe['featured_image']) }}" 
                                                                     alt="{{ $recipe['title'] }}"
                                                                     class="w-full h-full object-cover">
                                                            @else
                                                                <div class="w-full h-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                                                                    <svg class="w-12 h-12 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 9.246 5 7.5 5s-3.332.477-4.5 1.253m9 0C18.168 5.477 19.754 5 21.5 5c1.747 0 3.332.477 4.5 1.253v13C18.168 18.523 19.754 19 21.5 19c1.747 0 3.332-.477 4.5-1.253"/>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="p-3">
                                                            <h5 class="font-medium text-gray-900 text-sm mb-2 line-clamp-2">
                                                                {{ $recipe['title'] }}
                                                            </h5>
                                                            @if($recipe['summary'])
                                                                <p class="text-xs text-gray-600 mb-2 line-clamp-2">
                                                                    {{ Str::limit($recipe['summary'], 80) }}
                                                                </p>
                                                            @endif
                                                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                                                @if($recipe['cooking_time'])
                                                                    <span class="flex items-center">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                        </svg>
                                                                        {{ $recipe['cooking_time'] }} phút
                                                                    </span>
                                                                @endif
                                                                @if($recipe['difficulty_level'])
                                                                    <span class="flex items-center">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                                        </svg>
                                                                        {{ $recipe['difficulty_level'] }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <a href="{{ route('recipes.show', $recipe['slug']) }}" 
                                                               class="block w-full text-center px-3 py-2 bg-orange-600 text-white text-xs font-medium rounded-md hover:bg-orange-700 transition-colors">
                                                                Xem công thức
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($message['role'] === 'assistant' && !isset($message['recipes']))
                                        <!-- No recipes suggested message -->
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="text-center py-4">
                                                <p class="text-sm text-gray-500 italic">AI đã đưa ra lời khuyên chung, không có công thức cụ thể nào được gợi ý.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1 {{ $message['role'] === 'user' ? 'text-right' : 'text-left' }}">
                                    {{ $message['timestamp'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Loading indicator -->
            @if($isLoading)
                <div class="flex justify-start">
                    <div class="flex items-center space-x-2 px-4 py-2 bg-gray-100 rounded-lg">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                        <span class="text-sm text-gray-600">AI đang suy nghĩ...</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Message Input -->
    <div class="bg-white rounded-b-lg border border-gray-200 p-6">
        <form wire:submit.prevent="sendMessage" class="flex space-x-4">
            <div class="flex-1">
                <input type="text" 
                       id="message-input"
                       wire:model="message"
                       wire:keydown.enter="sendMessage"
                       placeholder="Hỏi tôi về nấu ăn, công thức món ăn, mẹo hay..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       {{ $isLoading ? 'disabled' : '' }}>
            </div>
            <button type="submit" 
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<style>
.markdown-content h1, .markdown-content h2, .markdown-content h3, .markdown-content h4, .markdown-content h5, .markdown-content h6 {
    font-weight: bold;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
.markdown-content h1 { font-size: 1.25rem; }
.markdown-content h2 { font-size: 1.125rem; }
.markdown-content h3 { font-size: 1rem; }
.markdown-content p {
    margin-bottom: 0.75rem;
}
.markdown-content ul, .markdown-content ol {
    margin-bottom: 0.75rem;
    padding-left: 1.5rem;
}
.markdown-content ul {
    list-style-type: disc;
}
.markdown-content ol {
    list-style-type: decimal;
}
.markdown-content li {
    margin-bottom: 0.25rem;
}
.markdown-content strong {
    font-weight: bold;
}
.markdown-content em {
    font-style: italic;
}
.markdown-content code {
    background-color: #f3f4f6;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace;
    font-size: 0.875em;
}
.markdown-content pre {
    background-color: #1f2937;
    color: #f9fafb;
    padding: 0.75rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin-bottom: 0.75rem;
}
.markdown-content pre code {
    background-color: transparent;
    padding: 0;
    color: inherit;
}
.markdown-content blockquote {
    border-left: 4px solid #d1d5db;
    padding-left: 1rem;
    margin-bottom: 0.75rem;
    font-style: italic;
    color: #6b7280;
}

/* Recipe card styling */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.recipe-card {
    transition: all 0.2s ease-in-out;
}

.recipe-card:hover {
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('scroll-to-bottom', () => {
        setTimeout(() => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }, 100);
    });
});

// Auto-scroll to bottom when new messages are added
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        const observer = new MutationObserver(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
        observer.observe(chatMessages, { childList: true, subtree: true });
    }
});
</script>
@endpush
