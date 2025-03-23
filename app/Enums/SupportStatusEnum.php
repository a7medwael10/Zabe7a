<?php

namespace App\Enums;

enum SupportStatusEnum: int
{
    case PENDING = 1;    // قيد الانتظار
    case REVIEWED = 2;   // تم المراجعة
    case RESOLVED = 3;   // تم الحل

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'قيد الانتظار',
            self::REVIEWED => 'تم المراجعة',
            self::RESOLVED => 'تم الحل',
        };
    }
}
