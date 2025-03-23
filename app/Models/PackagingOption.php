<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagingOption extends Model
{
  protected $fillable = [
      'name', 'type', 'extra_price', 'is_active'
  ];


}
