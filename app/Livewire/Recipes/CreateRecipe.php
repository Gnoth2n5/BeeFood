<?php

namespace App\Livewire\Recipes;

use App\Models\Category;
use App\Models\Tag;
use App\Services\RecipeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;


class CreateRecipe extends Component
{
    use WithFileUploads;

    // Form fields
    public $title = '';
    public $description = '';
    public $summary = '';
    public $cooking_time = 30;
    public $preparation_time = 15;
    public $difficulty = 'medium';
    public $servings = 4;
    public $calories_per_serving = null;
    public $ingredients = [];
    public $instructions = [];
    public $tips = '';
    public $notes = '';
    public $featured_image;
    public $video_url = '';
    public $category_ids = [];
    public $tag_ids = [];

    // Available options
    public $categories = [];
    public $tags = [];
    public $difficultyOptions = [
        'easy' => 'Dễ',
        'medium' => 'Trung bình',
        'hard' => 'Khó'
    ];

    // Component state
    public $isSubmitting = false;
    public $showSuccessMessage = false;

    public function mount()
    {
        // Check if user can create recipes
        $this->authorize('create', \App\Models\Recipe::class);
        
        $this->loadCategoriesAndTags();
        $this->initializeForm();
    }

    public function loadCategoriesAndTags()
    {
        $this->categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        $this->tags = Tag::orderBy('name')
            ->limit(50)
            ->get();
    }

    public function initializeForm()
    {
        // Initialize with default ingredients and instructions
        $this->ingredients = [
            ['name' => '', 'amount' => '', 'unit' => '']
        ];

        $this->instructions = [
            ['step' => 1, 'instruction' => '']
        ];
    }

    public function addIngredient()
    {
        $this->ingredients[] = ['name' => '', 'amount' => '', 'unit' => ''];
    }

    public function removeIngredient($index)
    {
        if (count($this->ingredients) > 1) {
            unset($this->ingredients[$index]);
            $this->ingredients = array_values($this->ingredients);
        }
    }

    public function addInstruction()
    {
        $nextStep = count($this->instructions) + 1;
        $this->instructions[] = ['step' => $nextStep, 'instruction' => ''];
    }

    public function removeInstruction($index)
    {
        if (count($this->instructions) > 1) {
            unset($this->instructions[$index]);
            $this->instructions = array_values($this->instructions);
            
            // Reorder steps
            foreach ($this->instructions as $key => $instruction) {
                $this->instructions[$key]['step'] = $key + 1;
            }
        }
    }

    public function updatedIngredients()
    {
        // Remove empty ingredients
        $this->ingredients = array_filter($this->ingredients, function ($ingredient) {
            return !empty($ingredient['name']) || !empty($ingredient['amount']);
        });
        
        // Ensure at least one ingredient
        if (empty($this->ingredients)) {
            $this->ingredients = [['name' => '', 'amount' => '', 'unit' => '']];
        }
    }

    public function updatedInstructions()
    {
        // Remove empty instructions
        $this->instructions = array_filter($this->instructions, function ($instruction) {
            return !empty($instruction['instruction']);
        });
        
        // Ensure at least one instruction
        if (empty($this->instructions)) {
            $this->instructions = [['step' => 1, 'instruction' => '']];
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:20',
            'summary' => 'required|string|max:500',
            'cooking_time' => 'required|integer|min:5|max:1440',
            'preparation_time' => 'required|integer|min:0|max:1440',
            'difficulty' => 'required|in:easy,medium,hard',
            'servings' => 'required|integer|min:1|max:50',
            'calories_per_serving' => 'nullable|integer|min:0|max:5000',
            'ingredients' => 'required|array|min:2',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:50',
            'ingredients.*.unit' => 'required|string|max:50',
            'instructions' => 'required|array|min:2',
            'instructions.*.instruction' => 'required|string|min:5|max:1000',
            'tips' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_url' => 'nullable|url|max:500',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ], [
            'title.required' => 'Tiêu đề công thức là bắt buộc.',
            'title.min' => 'Tiêu đề phải có ít nhất 5 ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả công thức là bắt buộc.',
            'description.min' => 'Mô tả phải có ít nhất 20 ký tự.',
            'summary.required' => 'Tóm tắt công thức là bắt buộc.',
            'summary.max' => 'Tóm tắt không được vượt quá 500 ký tự.',
            'cooking_time.required' => 'Thời gian nấu là bắt buộc.',
            'cooking_time.min' => 'Thời gian nấu phải ít nhất 5 phút.',
            'cooking_time.max' => 'Thời gian nấu không được vượt quá 24 giờ.',
            'preparation_time.required' => 'Thời gian chuẩn bị là bắt buộc.',
            'preparation_time.min' => 'Thời gian chuẩn bị không được âm.',
            'preparation_time.max' => 'Thời gian chuẩn bị không được vượt quá 24 giờ.',
            'difficulty.required' => 'Độ khó là bắt buộc.',
            'difficulty.in' => 'Độ khó phải là: dễ, trung bình, hoặc khó.',
            'servings.required' => 'Số khẩu phần là bắt buộc.',
            'servings.min' => 'Số khẩu phần phải ít nhất 1.',
            'servings.max' => 'Số khẩu phần không được vượt quá 50.',
            'calories_per_serving.min' => 'Calo không được âm.',
            'calories_per_serving.max' => 'Calo không được vượt quá 5000.',
            'ingredients.required' => 'Danh sách nguyên liệu là bắt buộc.',
            'ingredients.min' => 'Phải có ít nhất 2 nguyên liệu.',
            'ingredients.*.name.required' => 'Tên nguyên liệu là bắt buộc.',
            'ingredients.*.amount.required' => 'Số lượng nguyên liệu là bắt buộc.',
            'ingredients.*.unit.required' => 'Đơn vị nguyên liệu là bắt buộc.',
            'instructions.required' => 'Hướng dẫn nấu là bắt buộc.',
            'instructions.min' => 'Phải có ít nhất 2 bước hướng dẫn.',
            'instructions.*.instruction.required' => 'Nội dung hướng dẫn là bắt buộc.',
            'instructions.*.instruction.min' => 'Hướng dẫn phải có ít nhất 5 ký tự.',
            'instructions.*.instruction.max' => 'Hướng dẫn không được vượt quá 1000 ký tự.',
            'tips.max' => 'Mẹo không được vượt quá 1000 ký tự.',
            'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự.',
            'featured_image.image' => 'File phải là hình ảnh.',
            'featured_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'featured_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'video_url.url' => 'URL video không hợp lệ.',
            'video_url.max' => 'URL video không được vượt quá 500 ký tự.',
            'category_ids.required' => 'Danh mục là bắt buộc.',
            'category_ids.min' => 'Phải chọn ít nhất 1 danh mục.',
            'category_ids.*.exists' => 'Danh mục không tồn tại.',
            'tag_ids.*.exists' => 'Tag không tồn tại.',
        ]);

        // Prepare data for service
        $recipeData = [
            'title' => $this->title,
            'description' => $this->description,
            'summary' => $this->summary,
            'cooking_time' => $this->cooking_time,
            'preparation_time' => $this->preparation_time,
            'difficulty' => $this->difficulty,
            'servings' => $this->servings,
            'calories_per_serving' => $this->calories_per_serving,
            'ingredients' => $this->ingredients,
            'instructions' => $this->instructions,
            'tips' => $this->tips,
            'notes' => $this->notes,
            'featured_image' => $this->featured_image,
            'video_url' => $this->video_url,
            'category_ids' => $this->category_ids,
            'tag_ids' => $this->tag_ids,
            'status' => 'pending'
        ];

        try {
            $this->isSubmitting = true;

            $recipeService = app(RecipeService::class);
            $recipe = $recipeService->create($recipeData, Auth::user());

            $this->showSuccessMessage = true;
            $this->resetForm();

            // Redirect to the created recipe after a short delay
            $this->dispatch('recipe-created', recipeId: $recipe->slug);
            
            // Also redirect using Livewire redirect
            $this->redirect(route('recipes.show', $recipe));
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi tạo công thức: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'description', 'summary', 'cooking_time', 'preparation_time',
            'difficulty', 'servings', 'calories_per_serving', 'ingredients',
            'instructions', 'tips', 'notes', 'featured_image', 'video_url',
            'category_ids', 'tag_ids'
        ]);
        $this->initializeForm();
    }

    public function render()
    {
        return view('livewire.recipes.create-recipe')
            ->layout('layouts.app');
    }
}
