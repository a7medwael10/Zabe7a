<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'parent_id', 'name', 'slug', 'logo', 'description', 'sort_order', 'is_active'
  ];

  public function parent()
  {
      return $this->belongsTo(Category::class, 'parent_id');
  }

  public function children()
  {
      return $this->hasMany(Category::class, 'parent_id');
  }

  public function ads()
  {
      return $this->hasMany(Ad::class);
  }
}
