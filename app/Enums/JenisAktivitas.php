<?php

namespace App\Enums;

enum JenisAktivitas: string
{
    case Buat = 'buat';
    case Perbarui = 'perbarui';
    case Hapus = 'hapus';
    case Status = 'status';
    case Finalisasi = 'finalisasi';
    case Koreksi = 'koreksi';
    case Pembatalan = 'pembatalan';

    public function label(): string
    {
        return match ($this) {
            self::Buat => 'Buat',
            self::Perbarui => 'Perbarui',
            self::Hapus => 'Hapus',
            self::Status => 'Perubahan Status',
            self::Finalisasi => 'Finalisasi',
            self::Koreksi => 'Koreksi',
            self::Pembatalan => 'Pembatalan',
        };
    }
}
