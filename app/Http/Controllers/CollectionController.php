<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Recipe;
use App\Services\CollectionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CollectionController extends Controller
{
  use AuthorizesRequests;

  public function __construct(
    private CollectionService $collectionService
  ) {}

  public function index(Request $request): View
  {
    $collections = $this->collectionService->getUserCollections($request->user());

    return view('collections.index', compact('collections'));
  }

  public function create(): View
  {
    $this->authorize('create', Collection::class);

    return view('collections.create');
  }

  public function store(Request $request): RedirectResponse
  {
    $this->authorize('create', Collection::class);

    $collection = Collection::create([
      'name' => $request->name,
      'user_id' => Auth::user()->id,
    ]);

    return redirect()->route('collections.show', $collection);
  }

  public function edit(Collection $collection): View
  {
    $this->authorize('update', $collection);

    return view('collections.edit', compact('collection'));
  }

  public function update(UpdateCollectionRequest $request, Collection $collection): RedirectResponse
  {
    $this->collectionService->update($collection, $request->validated());

    return redirect()->route('collections.show', $collection)
      ->with('success', 'Bộ sưu tập đã được cập nhật thành công.');
  }
}
