<?php

namespace App\Enums;

enum JenisNotifikasi: string
{
    case KunjunganBaru = 'kunjungan_baru';
    case StatusAntrean = 'status_antrean';
    case ResepDifinalisasi = 'resep_difinalisasi';
    case TindakLanjutObat = 'tindak_lanjut_obat';
    case PenyerahanObat = 'penyerahan_obat';
    case Administratif = 'administratif';

    public function label(): string
    {
        return match ($this) {
            self::KunjunganBaru => 'Kunjungan Baru',
            self::StatusAntrean => 'Status Antrean',
            self::ResepDifinalisasi => 'Resep Difinalisasi',
            self::TindakLanjutObat => 'Tindak Lanjut Obat',
            self::PenyerahanObat => 'Penyerahan Obat',
            self::Administratif => 'Administratif',
        };
    }
}
