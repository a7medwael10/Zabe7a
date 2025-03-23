<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
  protected $fillable = [
      'image_path', 'title', 'description','offer_id', 'sort_order', 'is_active'
  ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

}
