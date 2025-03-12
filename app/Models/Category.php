<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'section_id', 'name', 'slug', 'logo', 'description', 'sort_order', 'is_active'
  ];


    public function section()
    {
        return $this->belongsTo(Section::class);
    }

  public function ads()
  {
      return $this->hasMany(Ad::class);
  }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function sliders()
    {
        return $this->morphMany(Slider::class, 'sliderable');
    }
}
