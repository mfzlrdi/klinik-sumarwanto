<?php

namespace App\Enums;

enum JenisDiagnosa: string
{
    case Utama = 'utama';
    case Tambahan = 'tambahan';

    public function label(): string
    {
        return match ($this) {
            self::Utama => 'Utama',
            self::Tambahan => 'Tambahan',
        };
    }
}
