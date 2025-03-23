<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
  use SoftDeletes;

  protected $fillable = [
      'order_number', 'user_id', 'delivery_company_id', 'address_id',
      'subtotal', 'shipping_cost', 'discount', 'total', 'status', 'payment_method_id',
      'payment_status', 'paid_at', 'shipped_at', 'delivered_at', 'customer_notes'
  ];

  protected $casts = [
      'paid_at' => 'datetime',
      'shipped_at' => 'datetime',
      'delivered_at' => 'datetime',
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }

  public function deliveryCompany()
  {
      return $this->belongsTo(DeliveryCompany::class);
  }

  public function address()
  {
      return $this->belongsTo(Address::class);
  }

  public function orderItems()
  {
      return $this->hasMany(OrderItem::class);
  }

  public function reviews()
  {
      return $this->hasMany(Review::class);
  }

    public function getOrderStatusTimeline(): array
    {
        return [
            [
                'status'    => OrderStatusEnum::SLAUGHTERED->label(), // تم الدبح والتقطيع
                'completed' => $this->slaughtered_at !== null,
                'timestamp' => $this->slaughtered_at,
            ],
            [
                'status'    => OrderStatusEnum::PACKED->label(), // تم التغليف وتجهيز الدبيحة
                'completed' => $this->packed_at !== null,
                'timestamp' => $this->packed_at,
            ],
            [
                'status'    => OrderStatusEnum::WAITING->label(), // بانتظار الشحن
                'completed' => $this->waiting_at !== null,
                'timestamp' => $this->waiting_at,
            ],
            [
                'status'    => OrderStatusEnum::ON_WAY->label(), // خرج مع المندوب
                'completed' => $this->on_way_at !== null,
                'timestamp' => $this->on_way_at,
            ],
            [
                'status'    => OrderStatusEnum::DONE->label(), // تم التسليم
                'completed' => $this->delivered_at !== null,
                'timestamp' => $this->delivered_at,
            ],
        ];
    }



}
