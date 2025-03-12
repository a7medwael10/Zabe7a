<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  protected $fillable = [
      'order_id', 'itemable_id',
      'itemable_type', 'title', 'unit_price',
      'quantity', 'packaging_options', 'subtotal', 'status'
  ];

  public function order()
  {
      return $this->belongsTo(Order::class);
  }

    public function itemable()
    {
        return $this->morphTo();
    }

  public function vendor()
  {
      return $this->belongsTo(User::class, 'vendor_id');
  }
}
