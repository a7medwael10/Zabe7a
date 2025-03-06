<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPackagingOption extends Model
{
  protected $fillable = [
      'ad_id', 'type', 'additional_price', 'is_available', 'sort_order'
  ];

  public function ad()
  {
      return $this->belongsTo(Ad::class);
  }
}
