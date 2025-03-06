<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
  protected $fillable = [
      'image_path', 'title', 'description',
      'target_type', 'target_id', 'target_url', 'sort_order', 'is_active'
  ];
}
