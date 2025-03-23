<?php

namespace App\Models;

use App\Enums\AdStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'thumbnail_path', 'category_id', 'title','sub_title', 'slug', 'description',
      'price', 'quantity_available', 'quantity_sold', 'weight',
      'rating', 'views_count', 'reviews_count', 'status', 'approved_at', 'expires_at'
  ];

  protected $casts = [
      'approved_at' => 'datetime',
      'expires_at' => 'datetime',
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }

  public function category()
  {
      return $this->belongsTo(Category::class);
  }

  public function images()
  {
      return $this->hasMany(AdImage::class);
  }

  public function packagingOptions()
  {
      return $this->morphMany(AdPackagingOption::class, 'packageable');
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


    public function scopeBestSelling($query)
    {
        return $query->where('status', AdStatusEnum::AVAILABLE->value)
        ->orderByDesc('quantity_sold');
    }

    public function scopeHighestRated($query)
    {
        return $query->where('status', AdStatusEnum::AVAILABLE->value)
            ->orderByDesc('rating');
    }

    public function scopePriceHighToLow($query)
    {
        return $query->where('status', AdStatusEnum::AVAILABLE->value)
            ->orderByDesc('price');
    }

    public function scopePriceLowToHigh($query)
    {
        return $query->where('status', AdStatusEnum::AVAILABLE->value)
            ->orderBy('price');
    }

    public function scopeOurSuggestions($query)
    {
        return $query->where('status', AdStatusEnum::AVAILABLE->value)
            ->orderByDesc('created_at');
    }
}
