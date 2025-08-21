<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'address',
        'phone',
        'latitude',
        'longitude',
        'website',
        'description',
        'embedding',
        'featured_image',
        'is_active',
    ];

    protected $casts = [
        'embedding' => 'array',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the shop items for the user shop.
     */
    public function shopItems()
    {
        return $this->hasMany(ShopItem::class);
    }
}


