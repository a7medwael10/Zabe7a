<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'order_number', 'user_id', 'coupon_id', 'delivery_company_id', 'address_id',
      'subtotal', 'shipping_cost', 'discount', 'total', 'status', 'payment_method',
      'payment_status', 'paid_at', 'shipped_at', 'delivered_at', 'customer_notes'
  ];

  protected $casts = [
      'paid_at' => 'datetime',
      'shipped_at' => 'datetime',
      'delivered_at' => 'datetime',
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

  public function address()
  {
      return $this->belongsTo(Address::class);
  }

  public function items()
  {
      return $this->hasMany(OrderItem::class);
  }

  public function reviews()
  {
      return $this->hasMany(Review::class);
  }
}
