<?php

namespace App\Enums;

enum OrderStatusEnum: int
{

    case PENDING     = 1; // قيد الانتظار
    case SLAUGHTERED = 2; // ذبح
    case PACKED      = 3; // تغليف
    case WAITING     = 4; // انتظار
    case ON_WAY      = 5; // خرج مع المندوب
    case DONE        = 6; // تم التسليم

    public function label(): string
    {
        return match ($this) {
            self::PENDING     => 'قيد الانتظار',
            self::SLAUGHTERED => 'تم الدبح والتقطيع',
            self::PACKED      => 'تم التغليف وتجهيز الدبيحة',
            self::WAITING     => 'بانتظار الشحن',
            self::ON_WAY      => 'خرج مع المندوب',
            self::DONE        => 'تم التسليم',

        };
    }
}
