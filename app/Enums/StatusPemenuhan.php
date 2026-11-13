<?php

namespace App\Enums;

enum StatusPemenuhan: string
{
    case Menunggu = 'menunggu';
    case SedangDiproses = 'sedang_diproses';
    case SiapDiserahkan = 'siap_diserahkan';
    case DiserahkanSebagian = 'diserahkan_sebagian';
    case SelesaiDiserahkan = 'selesai_diserahkan';
    case DirujukKeApotekLuar = 'dirujuk_ke_apotek_luar';
    case Terhambat = 'terhambat';

    public function label(): string
    {
        return match ($this) {
            self::Menunggu => 'Menunggu',
            self::SedangDiproses => 'Sedang Diproses',
            self::SiapDiserahkan => 'Siap Diserahkan',
            self::DiserahkanSebagian => 'Diserahkan Sebagian',
            self::SelesaiDiserahkan => 'Selesai Diserahkan',
            self::DirujukKeApotekLuar => 'Dirujuk ke Apotek Luar',
            self::Terhambat => 'Terhambat',
        };
    }
}
