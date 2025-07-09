<?php

namespace App\Livewire\Collections;

use App\Models\Collection;
use App\Services\CollectionService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class CollectionDetail extends Component
{
    use WithPagination, WithFileUploads;

    public Collection $collection;
    public $isOwner = false;
    public $perPage = 12;
    public $isEditing = false;

    // Thuộc tính cho form chỉnh sửa
    public $editName;
    public $editDescription;
    public $editIsPublic;
    public $editCoverImage;
    public $editCoverImagePreview;

    public function mount(Collection $collection)
    {
        $this->collection = $collection->load(['user.profile', 'recipes.categories', 'recipes.images', 'recipes.user.profile']);
        $this->isOwner = Auth::check() && Auth::id() === $collection->user_id;
        $this->resetEditFields();
    }

    public function showEdit()
    {
        $this->isEditing = true;
        $this->resetEditFields();
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->resetEditFields();
        $this->resetValidation();
    }

    public function updatedEditCoverImage()
    {
        if ($this->editCoverImage) {
            $this->editCoverImagePreview = $this->editCoverImage->temporaryUrl();
        }
    }

    public function resetEditFields()
    {
        $this->editName = $this->collection->name;
        $this->editDescription = $this->collection->description;
        $this->editIsPublic = $this->collection->is_public;
        $this->editCoverImage = null;
        $this->editCoverImagePreview = $this->collection->cover_image ? Storage::url($this->collection->cover_image) : null;
    }

    public function resetEditCoverImage()
    {
        $this->editCoverImage = null;
        $this->editCoverImagePreview = $this->collection->cover_image ? Storage::url($this->collection->cover_image) : null;
        $this->resetErrorBag('editCoverImage');
    }

    public function updateCollection()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editDescription' => 'nullable|string|max:1000',
            'editIsPublic' => 'boolean',
            'editCoverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'editName.required' => 'Tên bộ sưu tập là bắt buộc.',
            'editName.max' => 'Tên bộ sưu tập không được vượt quá 255 ký tự.',
            'editDescription.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'editCoverImage.image' => 'File phải là hình ảnh.',
            'editCoverImage.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'editCoverImage.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ]);

        $data = [
            'name' => $this->editName,
            'description' => $this->editDescription,
            'is_public' => $this->editIsPublic,
        ];
        if ($this->editCoverImage) {
            $data['cover_image'] = $this->editCoverImage;
        }

        $collectionService = app(CollectionService::class);
        $collectionService->update($this->collection, $data);

        // Load lại collection mới nhất
        $this->collection = Collection::with(['user.profile', 'recipes.categories', 'recipes.images', 'recipes.user.profile'])
            ->find($this->collection->id);
        $this->isEditing = false;
        $this->resetEditFields();
        session()->flash('success', 'Cập nhật bộ sưu tập thành công!');
        $this->dispatch('flash-message', message: 'Cập nhật bộ sưu tập thành công!', type: 'success');
    }

} 