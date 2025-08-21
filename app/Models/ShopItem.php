<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_shop_id',
        'name',
        'description',
        'embedding',
        'price',
        'featured_image',
        'is_active',
        'stock_quantity',
        'sku',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'embedding' => 'array',
        'is_active' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the user shop that owns the shop item.
     */
    public function userShop()
    {
        return $this->belongsTo(UserShop::class);
    }

    /**
     * Scope a query to only include active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include items with stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }
}
