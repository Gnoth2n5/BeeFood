<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\RatingController;

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminLogoutController;
use App\Http\Controllers\OpenAiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserShopController;
// use App\Http\Controllers\VipPostController;
use App\Livewire\HomePage;
use App\Livewire\Recipes\RecipeDetail;

Route::get('/', HomePage::class)->name('home');



// Post routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

// Comment routes
Route::get('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'index'])->name('comments.index');
Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/update', [App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('/comments/{comment}/reply', [App\Http\Controllers\CommentController::class, 'reply'])->name('comments.reply');
Route::post('/comments/{comment}/like', [App\Http\Controllers\CommentController::class, 'like'])->name('comments.like');
Route::post('/comments/{comment}/dislike', [App\Http\Controllers\CommentController::class, 'dislike'])->name('comments.dislike');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('profile', App\Livewire\Profile\ProfilePage::class)
    ->middleware(['auth'])
    ->name('profile');

// Recipe routes
Route::get('/recipes', App\Livewire\Recipes\RecipeList::class)->name('recipes.index');
Route::get('/recipes/create', App\Livewire\Recipes\CreateRecipe::class)->name('recipes.create')->middleware('auth');
Route::get('/recipes/{recipe:slug}/edit', App\Livewire\Recipes\EditRecipe::class)->name('recipes.edit')->middleware('auth');
Route::get('/recipes/{recipe}', RecipeDetail::class)->name('recipes.show');

// Advanced Search route
Route::get('/search', App\Livewire\AdvancedSearch::class)->name('search.advanced');

// Weather-based recipe suggestions
Route::get('/weather-suggestions', App\Livewire\WeatherRecipeSuggestions::class)->name('weather.suggestions');

// OpenAI Chat Assistant
Route::get('/ai-chat', [OpenAiController::class, 'index'])->name('openai.index');

// VIP Shops and Posts
Route::get('/shops', [UserShopController::class, 'index'])->name('shops.index');
Route::get('/shops/{slug}', App\Livewire\Shop\Show::class)->name('shops.show');
// OpenAI API routes
Route::prefix('api/openai')->name('api.openai.')->group(function () {
    Route::post('/send-message', [OpenAiController::class, 'sendMessage'])->name('send-message');
    Route::post('/recipe-suggestions', [OpenAiController::class, 'getRecipeSuggestions'])->name('recipe-suggestions');
    Route::post('/cooking-tips', [OpenAiController::class, 'getCookingTips'])->name('cooking-tips');
    Route::post('/analyze-recipe', [OpenAiController::class, 'analyzeRecipe'])->name('analyze-recipe');
    Route::post('/nutritional-info', [OpenAiController::class, 'getNutritionalInfo'])->name('nutritional-info');
    Route::get('/conversation-history', [OpenAiController::class, 'getConversationHistory'])->name('conversation-history');
    Route::delete('/conversation-history', [OpenAiController::class, 'clearConversationHistory'])->name('clear-conversation');
    Route::get('/quick-suggestions', [OpenAiController::class, 'getQuickSuggestions'])->name('quick-suggestions');
});

// Vietnam Provinces API
Route::prefix('api/vietnam-provinces')->name('api.vietnam-provinces.')->group(function () {
    Route::get('/', [App\Http\Controllers\VietnamProvinceController::class, 'index'])->name('index');
    Route::get('/stats', [App\Http\Controllers\VietnamProvinceController::class, 'stats'])->name('stats');
    Route::get('/health', [App\Http\Controllers\VietnamProvinceController::class, 'health'])->name('health');
    Route::get('/search', [App\Http\Controllers\VietnamProvinceController::class, 'search'])->name('search');
    Route::get('/{code}', [App\Http\Controllers\VietnamProvinceController::class, 'show'])->name('show');
    Route::get('/{provinceCode}/districts', [App\Http\Controllers\VietnamProvinceController::class, 'districts'])->name('districts');
    Route::get('/districts/{districtCode}/wards', [App\Http\Controllers\VietnamProvinceController::class, 'wards'])->name('wards');
    Route::delete('/cache', [App\Http\Controllers\VietnamProvinceController::class, 'clearCache'])->name('clear-cache');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Recipe management
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::put('/recipes/{recipe:slug}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe:slug}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // User recipes
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my');

    // Ratings
    Route::post('/recipes/{recipe}/rate', [RatingController::class, 'store'])->name('recipes.rate');
    Route::put('/recipes/{recipe}/rate', [RatingController::class, 'update'])->name('recipes.rate.update');
    Route::delete('/recipes/{recipe}/rate', [RatingController::class, 'destroy'])->name('recipes.rate.destroy');

    // Favorites
    Route::get('/favorites', App\Livewire\Favorites\FavoritesPage::class)->name('favorites.index');

    // Collections
    Route::get('/collections/{collection}', \App\Livewire\Collections\CollectionDetail::class)->name('collections.show');
    Route::resource('collections', CollectionController::class)->except(['show']);
    Route::post('/collections/{collection}/recipes/{recipe}', [CollectionController::class, 'addRecipe'])->name('collections.add-recipe');
    Route::delete('/collections/{collection}/recipes/{recipe}', [CollectionController::class, 'removeRecipe'])->name('collections.remove-recipe');

    // User upsert shop and vip posts
    Route::get('/me/shop', function () {
        session(['activeTab' => 'vip']);
        return redirect()->route('profile');
    })->name('me.shop');
    Route::post('/me/shop', [UserShopController::class, 'upsert'])->name('me.shop.upsert');
    // Route::post('/me/vip-posts', [VipPostController::class, 'upsert'])->name('me.vip-posts.upsert');
});

// Admin routes
Route::middleware(['auth', 'can:approve,App\Models\Recipe'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/recipes/pending', [RecipeController::class, 'pending'])->name('recipes.pending');
    Route::post('/recipes/{recipe}/approve', [RecipeController::class, 'approve'])->name('recipes.approve');
    Route::post('/recipes/{recipe}/reject', [RecipeController::class, 'reject'])->name('recipes.reject');
    Route::get('/moderation-test', \App\Livewire\Admin\ModerationTest::class)->name('moderation.test');
    Route::get('/scheduled-posts', \App\Livewire\Admin\ScheduledPosts::class)->name('scheduled-posts');
    Route::get('/pending-posts', \App\Livewire\Admin\PendingPosts::class)->name('pending-posts');
});

// Admin logout route
Route::post('/admin/logout', [AdminLogoutController::class, 'logout'])->name('admin.logout');

// Filament admin logout route
Route::post('/admin/logout', [AdminLogoutController::class, 'logout'])->name('filament.admin.auth.logout');

// Filament user logout route
Route::post('/user/logout', [AdminLogoutController::class, 'logout'])->name('filament.user.auth.logout');

// Payment webhook - excluded from CSRF protection
Route::post('/webhooks/payment', [PaymentController::class, 'webhook'])
    ->name('webhooks.payment')
    ->withoutMiddleware(['auth', 'web']);



require __DIR__ . '/auth.php';
