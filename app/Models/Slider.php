<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
  protected $fillable = [
      'image_path', 'title', 'description',
      'sliderable_type', 'sliderable_id', 'sort_order', 'is_active'
  ];



}
