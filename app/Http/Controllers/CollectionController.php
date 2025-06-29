<?php

namespace App\Http\Controllers;

use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Requests\Collection\UpdateCollectionRequest;
use App\Models\Collection;
use App\Models\Recipe;
use App\Services\CollectionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CollectionController extends Controller
{
  use AuthorizesRequests;

  public function __construct(
    private CollectionService $collectionService
  ) {}

  /**
   * Hiển thị danh sách tài nguyên.
   */
  public function index(Request $request): View
  {
    $collections = $this->collectionService->getUserCollections($request->user());

    return view('collections.index', compact('collections'));
  }

  /**
   * Hiển thị các bộ sưu tập công khai.
   */
  public function public(Request $request): View
  {
    $collections = $this->collectionService->getPublicCollections();

    return view('collections.public', compact('collections'));
  }

  /**
   * Hiển thị mẫu để tạo một tài nguyên mới.
   */
  public function create(): View
  {
    $this->authorize('create', Collection::class);

    return view('collections.create');
  }

  /**
   * Lưu một tài nguyên mới được tạo vào kho lưu trữ.
   */
  public function store(StoreCollectionRequest $request): RedirectResponse
  {
    $collection = $this->collectionService->create($request->validated(), $request->user());

    return redirect()->route('collections.show', $collection)
      ->with('success', 'Bộ sưu tập đã được tạo thành công.');
  }

  /**
   * Hiển thị tài nguyên 
   */
  public function show(Collection $collection): View
  {
    $this->authorize('view', $collection);

    $collection = $this->collectionService->getCollectionWithRecipes($collection);

    return view('collections.show', compact('collection'));
  }

  /**
   * Hiển thị mẫu để chỉnh sửa tài nguyên 
   */
  public function edit(Collection $collection): View
  {
    $this->authorize('update', $collection);

    return view('collections.edit', compact('collection'));
  }

  /**
   * Cập nhật tài nguyên đã chỉ định trong kho lưu trữ.
   */
  public function update(UpdateCollectionRequest $request, Collection $collection): RedirectResponse
  {
    $this->collectionService->update($collection, $request->validated());

    return redirect()->route('collections.show', $collection)
      ->with('success', 'Bộ sưu tập đã được cập nhật thành công.');
  }

  /**
   * Xóa tài nguyên đã chỉ định khỏi kho lưu trữ.
   */
  public function destroy(Collection $collection): RedirectResponse
  {
    $this->authorize('delete', $collection);

    $this->collectionService->delete($collection);

    return redirect()->route('collections.index')
      ->with('success', 'Bộ sưu tập đã được xóa thành công.');
  }

  /**
   * Thêm công thức vào bộ sưu tập.
   */
  public function addRecipe(Collection $collection, Recipe $recipe): JsonResponse
  {
    $this->authorize('addRecipe', $collection);

    $added = $this->collectionService->addRecipe($collection, $recipe);

    if ($added) {
      return response()->json([
        'success' => true,
        'message' => 'Đã thêm công thức vào bộ sưu tập.',
        'recipe_count' => $collection->fresh()->recipe_count
      ]);
    }

    return response()->json([
      'success' => false,
      'message' => 'Công thức đã có trong bộ sưu tập.'
    ], 400);
  }

  /**
   * Xóa công thức khỏi bộ sưu tập.
   */
  public function removeRecipe(Collection $collection, Recipe $recipe): JsonResponse
  {
    $this->authorize('removeRecipe', $collection);

    $removed = $this->collectionService->removeRecipe($collection, $recipe);

    if ($removed) {
      return response()->json([
        'success' => true,
        'message' => 'Đã xóa công thức khỏi bộ sưu tập.',
        'recipe_count' => $collection->fresh()->recipe_count
      ]);
    }

    return response()->json([
      'success' => false,
      'message' => 'Không tìm thấy công thức trong bộ sưu tập.'
    ], 404);
  }

  /**
   * Tìm kiếm bộ sưu tập.
   */
  public function search(Request $request): View
  {
    $search = $request->get('q', '');
    $collections = $this->collectionService->searchCollections($search);

    return view('collections.search', compact('collections', 'search'));
  }
}
