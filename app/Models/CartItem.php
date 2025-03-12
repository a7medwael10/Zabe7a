<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  protected $fillable = [
      'cart_id', 'itemable_id',
      'itemable_type', 'unit_price', 'quantity', 'packaging_options',
      'special_instructions', 'subtotal'
  ];

  public function cart()
  {
      return $this->belongsTo(Cart::class);
  }

    public function itemable()
    {
        return $this->morphTo();
    }
}
