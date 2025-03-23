<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'sub_title',
        'slug',
        'category_id',
        'thumbnail_path',
        'description',
        'original_price',
        'discount_percentage',
        'offer_price',
        'gift',
        'rating',
        'quantity_available',
        'quantity_sold',
        'weight',
        'views_count', 'reviews_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    public function scopeActiveNow($query)
    {
        return $query->where('is_active', true)
        ->where(function ($query) {
            $query->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function cartItems()
    {
        return $this->morphMany(CartItem::class, 'itemable');
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'itemable');
    }

    public function favorites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class);
    }
}
