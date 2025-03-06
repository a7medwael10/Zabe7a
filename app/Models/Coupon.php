<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
  protected $fillable = [
      'code', 'type', 'value', 'minimum_order_amount', 'max_usage_per_user',
      'total_usage_limit', 'used_count', 'valid_from', 'valid_to', 'is_active'
  ];

  protected $casts = [
      'valid_from' => 'datetime',
      'valid_to' => 'datetime',
  ];
}
