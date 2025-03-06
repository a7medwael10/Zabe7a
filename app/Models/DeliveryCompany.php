<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
  protected $fillable = [
      'name', 'logo', 'description',
      'base_price', 'price_per_km', 'estimated_delivery_days', 'is_active'
  ];
}
