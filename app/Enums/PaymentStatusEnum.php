<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case PENDING = 1;
    case PAID    = 2;
    case FAILED  = 3;
    case REFUNDED = 4;


    public function label(): string
    {
        return match ($this) {
            self::PENDING    => 'قيد الانتظار',
            self::PAID       => 'تم الدفع',
            self::FAILED     => 'فشل الدفع',
            self::REFUNDED   => 'تم الاسترجاع',
        };
    }
}
