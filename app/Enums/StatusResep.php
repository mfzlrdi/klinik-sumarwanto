<?php

namespace App\Enums;

enum StatusResep: string
{
    case Draf = 'draf';
    case Difinalisasi = 'difinalisasi';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::Draf => 'Draf',
            self::Difinalisasi => 'Difinalisasi',
            self::Dibatalkan => 'Dibatalkan',
        };
    }
}
