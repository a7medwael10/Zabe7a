<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'user_id', 'category_id', 'title', 'slug', 'description',
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
      return $this->hasMany(AdPackagingOption::class);
  }

  public function cartItems()
  {
      return $this->hasMany(CartItem::class);
  }

  public function orderItems()
  {
      return $this->hasMany(OrderItem::class);
  }

  public function favorites()
  {
      return $this->hasMany(Favorite::class);
  }

  public function reviews()
  {
      return $this->hasMany(Review::class);
  }
}
