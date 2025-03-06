<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'user_id', 'label', 'country', 'city', 'district', 'street', 'postal_code',
      'building_description', 'latitude', 'longitude', 'is_primary'
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
