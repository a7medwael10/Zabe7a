<?php

namespace App\Enums;

enum AdStatusEnum: int
{
    case PENDING     = 1;
    case DRAFT       = 2;
    case AVAILABLE   = 3;
    case SOLD_OUT    = 4;

    public function label(): string
    {
        return match ($this) {
            self::PENDING     => 'قيد الانتظار',
            self::DRAFT       => 'مسودة',
            self::AVAILABLE   => 'متاح',
            self::SOLD_OUT    => 'نفذت الكمية',

        };
    }
}
