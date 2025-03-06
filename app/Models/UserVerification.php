<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
  protected $fillable = [
      'user_id', 'type', 'otp', 'expires_at', 'is_used', 'used_at', 'attempts'
  ];

  protected $casts = [
      'expires_at' => 'datetime',
      'used_at' => 'datetime',
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
