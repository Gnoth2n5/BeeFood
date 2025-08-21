<div class="comment-item border-b border-gray-200 pb-6 last:border-b-0" data-comment-id="{{ $comment->id }}">
    <div class="flex space-x-3">
        <!-- User Avatar -->
        <div class="flex-shrink-0">
            @if($comment->user->hasAvatar())
                <img src="{{ $comment->user->getAvatarUrl() }}" 
                     alt="{{ $comment->user->name }}" 
                     class="w-10 h-10 rounded-full object-cover">
            @else
                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Comment Content -->
        <div class="flex-1 min-w-0">
            <div class="bg-gray-50 rounded-lg p-4">
                <!-- Comment Header -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                        <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        @if($comment->is_edited)
                            <span class="text-xs text-gray-400 bg-gray-200 px-2 py-1 rounded">Đã chỉnh sửa</span>
                        @endif
                    </div>
                    
                    @auth
                        @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                            <div class="flex items-center space-x-2">
                                <button 
                                    onclick="editComment({{ $comment->id }})" 
                                    class="text-gray-400 hover:text-orange-600 transition-colors duration-200"
                                    title="Chỉnh sửa"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </button>
                                <button 
                                    onclick="deleteComment({{ $comment->id }})" 
                                    class="text-gray-400 hover:text-red-600 transition-colors duration-200"
                                    title="Xóa"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Comment Text -->
                <div class="comment-content text-gray-700 leading-relaxed">
                    {{ $comment->content }}
                </div>

                <!-- Comment Actions -->
                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center space-x-4">
                        <!-- Like Button -->
                        <button 
                            onclick="likeComment({{ $comment->id }})" 
                            class="flex items-center space-x-1 text-gray-500 hover:text-orange-600 transition-colors duration-200"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm">{{ $comment->like_count }}</span>
                        </button>

                        <!-- Dislike Button -->
                        <button 
                            onclick="dislikeComment({{ $comment->id }})" 
                            class="flex items-center space-x-1 text-gray-500 hover:text-red-600 transition-colors duration-200"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm">{{ $comment->dislike_count }}</span>
                        </button>

                        <!-- Reply Button -->
                        @auth
                            <button 
                                onclick="showReplyForm({{ $comment->id }})" 
                                class="flex items-center space-x-1 text-gray-500 hover:text-blue-600 transition-colors duration-200"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">Trả lời</span>
                            </button>
                        @endauth
                    </div>
                </div>

                <!-- Reply Form (Hidden by default) -->
                @auth
                    <div id="replyForm{{ $comment->id }}" class="hidden mt-4">
                        <form class="reply-form" data-comment-id="{{ $comment->id }}" onsubmit="submitReply({{ $comment->id }}, event)">
                            @csrf
                            <div class="flex space-x-3">
                                <textarea 
                                    name="content" 
                                    rows="2" 
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                                    placeholder="Viết trả lời của bạn..."
                                    required
                                ></textarea>
                                <button 
                                    type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                                >
                                    Gửi
                                </button>
                                <button 
                                    type="button" 
                                    onclick="hideReplyForm({{ $comment->id }})" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                                >
                                    Hủy
                                </button>
                            </div>
                        </form>
                    </div>
                @endauth
            </div>

            <!-- Replies -->
            @if($comment->replies->count() > 0)
                <div class="mt-4 ml-8 space-y-4">
                    @foreach($comment->replies as $reply)
                        <div class="reply-item border-l-2 border-gray-200 pl-4">
                            <div class="flex space-x-3">
                                <!-- User Avatar -->
                                <div class="flex-shrink-0">
                                    @if($reply->user->hasAvatar())
                                        <img src="{{ $reply->user->getAvatarUrl() }}" 
                                             alt="{{ $reply->user->name }}" 
                                             class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-red-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-xs">
                                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Reply Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                                        <!-- Reply Header -->
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-semibold text-gray-900 text-sm">{{ $reply->user->name }}</span>
                                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                @if($reply->is_edited)
                                                    <span class="text-xs text-gray-400 bg-gray-200 px-2 py-1 rounded">Đã chỉnh sửa</span>
                                                @endif
                                            </div>
                                            
                                            @auth
                                                @if(auth()->id() === $reply->user_id || auth()->user()->hasRole('admin'))
                                                    <div class="flex items-center space-x-2">
                                                        <button 
                                                            onclick="editComment({{ $reply->id }})" 
                                                            class="text-gray-400 hover:text-orange-600 transition-colors duration-200"
                                                            title="Chỉnh sửa"
                                                        >
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                            </svg>
                                                        </button>
                                                        <button 
                                                            onclick="deleteComment({{ $reply->id }})" 
                                                            class="text-gray-400 hover:text-red-600 transition-colors duration-200"
                                                            title="Xóa"
                                                        >
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>

                                        <!-- Reply Text -->
                                        <div class="reply-content text-gray-700 text-sm leading-relaxed">
                                            {{ $reply->content }}
                                        </div>

                                        <!-- Reply Actions -->
                                        <div class="flex items-center space-x-4 mt-2">
                                            <button 
                                                onclick="likeComment({{ $reply->id }})" 
                                                class="flex items-center space-x-1 text-gray-500 hover:text-orange-600 transition-colors duration-200"
                                            >
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs">{{ $reply->like_count }}</span>
                                            </button>

                                            <button 
                                                onclick="dislikeComment({{ $reply->id }})" 
                                                class="flex items-center space-x-1 text-gray-500 hover:text-red-600 transition-colors duration-200"
                                            >
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs">{{ $reply->dislike_count }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
