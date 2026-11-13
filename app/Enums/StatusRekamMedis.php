<?php

namespace App\Enums;

enum StatusRekamMedis: string
{
    case Draf = 'draf';
    case Difinalisasi = 'difinalisasi';

    public function label(): string
    {
        return match ($this) {
            self::Draf => 'Draf',
            self::Difinalisasi => 'Difinalisasi',
        };
    }
}
