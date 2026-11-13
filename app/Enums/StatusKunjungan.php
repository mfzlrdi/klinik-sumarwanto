<?php

namespace App\Enums;

enum StatusKunjungan: string
{
    case Menunggu = 'menunggu';
    case Dipanggil = 'dipanggil';
    case SedangKonsultasi = 'sedang_konsultasi';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::Menunggu => 'Menunggu',
            self::Dipanggil => 'Dipanggil',
            self::SedangKonsultasi => 'Sedang Konsultasi',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    /**
     * Peta transisi status yang sah dari satu keadaan ke keadaan berikutnya.
     *
     * @return array<string, string[]>
     */
    public static function transisi(): array
    {
        return [
            self::Menunggu->value => [self::Dipanggil->value, self::Dibatalkan->value],
            self::Dipanggil->value => [self::SedangKonsultasi->value, self::Menunggu->value, self::Dibatalkan->value],
            self::SedangKonsultasi->value => [self::Selesai->value, self::Dibatalkan->value],
            self::Selesai->value => [],
            self::Dibatalkan->value => [],
        ];
    }

    public static function bolehTransisi(string $dari, string $ke): bool
    {
        return in_array($ke, self::transisi()[$dari] ?? [], true);
    }
}
