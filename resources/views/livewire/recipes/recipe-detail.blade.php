@php
    $user = $recipe->user;
    $profile = $user->profile ?? null;
    $primaryImage = $recipe->primary_image?->image_path ?? ($recipe->images->first()->image_path ?? null);
@endphp

@section('meta')
    <meta property="og:title" content="{{ $recipe->title }}" />
    <meta property="og:description" content="{{ $recipe->summary }}" />
    <meta property="og:image" content="{{ $primaryImage ? Storage::url($primaryImage) : asset('/default.jpg') }}" />
    <meta property="og:url" content="{{ request()->fullUrl() }}" />
    <meta property="og:type" content="article" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $recipe->title }}" />
    <meta name="twitter:description" content="{{ $recipe->summary }}" />
    <meta name="twitter:image" content="{{ $primaryImage ? Storage::url($primaryImage) : asset('/default.jpg') }}" />
@endsection


<div>
    <!-- Nút quay lại -->
    <div class="max-w-5xl mx-auto w-full" style="width:80%">
        <a href="{{ url()->previous() }}" class="flex items-center text-orange-500 hover:underline text-base font-medium py-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-8 max-w-5xl mx-auto w-full" style="width:80%">
        <!-- Header: Ảnh bìa + tên món + tác giả + lượt xem -->
        <div class="relative h-64 w-full">
            @if($primaryImage)
                <img src="{{ Storage::url($primaryImage) }}" alt="{{ $recipe->title }}" class="object-cover w-full h-full" />
            @else
                <div class="bg-gray-200 w-full h-full flex items-center justify-center text-gray-400 text-2xl">No Image</div>
            @endif
            <div class="absolute bottom-4 left-6 bg-white/80 px-6 py-3 rounded-xl flex flex-col md:flex-row md:items-center gap-2 shadow">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mr-4">{{ $recipe->title }}</h1>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    @if($profile && $profile->avatar)
                        <img src="{{ Storage::url($profile->avatar) }}" alt="{{ $user->name }}" class="w-7 h-7 rounded-full object-cover" />
                    @else
                        <span class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">{{ strtoupper(substr($user->name,0,1)) }}</span>
                    @endif
                    <span class="font-medium">{{ $user->name }}</span>
                    <span class="mx-1">•</span>
                    <span>{{ $recipe->view_count }} lượt xem</span>
                </div>
            </div>
        </div>
