<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  protected $fillable = [
      'user_id', 'coupon_id', 'delivery_company_id', 'subtotal', 'discount'
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }

  public function coupon()
  {
      return $this->belongsTo(Coupon::class);
  }

  public function deliveryCompany()
  {
      return $this->belongsTo(DeliveryCompany::class);
  }

  public function items()
  {
      return $this->hasMany(CartItem::class);
  }
}
