<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  protected $fillable = [
      'cart_id', 'itemable_id','packaging_options',
      'itemable_type',  'quantity', 'total'
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
