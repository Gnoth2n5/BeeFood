<?php

namespace App\Http\Controllers;

use App\Models\UserShop;
use App\Services\UserShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserShopController extends Controller
{
    public function index()
    {
        $query = request('search');

        $shopsQuery = UserShop::with('shopItems')
            ->where('is_active', true);

        if (!empty($query)) {
            $term = "%{$query}%";
            $shopsQuery->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('address', 'like', $term)
                    ->orWhere('website', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhereHas('shopItems', function ($iq) use ($term) {
                        $iq->where('is_active', true)
                            ->where(function ($w) use ($term) {
                                $w->where('name', 'like', $term)
                                    ->orWhere('description', 'like', $term);
                            });
                    });
            });
        }

        $shops = $shopsQuery->latest()->paginate(12)->withQueryString();

        return view('livewire.shops.index', compact('shops'));
    }

    public function show($slug)
    {
        $shop = UserShop::with('shopItems')
            ->where('slug', $slug)
            ->firstOrFail();
        return view('livewire.shops.show', compact('shop'));
    }

    public function upsert(Request $request, UserShopService $service)
    {
        try {
            Log::info('Upsert shop request data', $request->all());
            
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:500',
                'phone' => 'nullable|string|max:30',
                'website' => 'nullable|url|max:255',
                'description' => 'nullable|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
                'items' => 'nullable|array',
                'items.*.name' => 'nullable|string|max:255',
                'items.*.price' => 'nullable|numeric|min:0',
                'items.*.description' => 'nullable|string',
                'items.*.featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'is_active' => 'nullable|boolean',
            ]); 
    
            $shop = $service->createOrUpdate(Auth::user(), $data);
            
            Log::info('Shop upserted successfully', ['shop_id' => $shop->id, 'shop_name' => $shop->name]);
            
            return redirect()->back()->with('success', 'Đã lưu cửa hàng: ' . $shop->name);
        } catch (\Throwable $th) {
            Log::error('Error upsert shop', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu cửa hàng: ' . $th->getMessage());
        }
    }
}


