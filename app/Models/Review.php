<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'user_id','reviewable_id',
      'reviewable_type', 'order_id', 'vendor_id', 'rating', 'comment', 'images',
      'is_approved', 'is_anonymous', 'helpful_votes', 'approved_at'
  ];

  protected $casts = [
      'approved_at' => 'datetime',
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }

    public function reviewable()
    {
        return $this->morphTo();
    }

  public function order()
  {
      return $this->belongsTo(Order::class);
  }

  public function vendor()
  {
      return $this->belongsTo(User::class, 'vendor_id');
  }
}
