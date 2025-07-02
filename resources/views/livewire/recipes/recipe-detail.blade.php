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
