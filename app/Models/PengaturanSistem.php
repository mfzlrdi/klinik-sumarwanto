<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nama_pengaturan', 'nilai_pengaturan', 'id_pengguna_pengubah'])]
class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    protected $primaryKey = 'id_pengaturan';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_pengubah');
    }

    public static function nilai(string $nama, ?string $bawaan = null): ?string
    {
        return static::query()->where('nama_pengaturan', $nama)->value('nilai_pengaturan') ?? $bawaan;
    }

    /**
     * Nilai bawaan konfigurasi sistem.
     *
     * @return array<string, string>
     */
    public static function bawaan(): array
    {
        return [
            'nama_praktik' => 'Praktik Dokter Umum dr. Sumarwanto',
            'alamat_praktik' => 'Jl. Kesehatan No. 10, Yogyakarta',
            'nomor_telepon' => '(0274) 123456',
            'jam_operasional' => 'Senin–Sabtu, 08.00–20.00 WIB',
            'logo_ikon' => 'activity',
            'logo_nama' => 'Praktik Dokter',
            'logo_subjudul' => 'dr. Sumarwanto',
            'tagline' => 'Administrasi lebih teratur, pelayanan terasa lebih dekat.',
            'deskripsi_singkat' => 'Praktik dokter umum yang melayani pemeriksaan, konsultasi, dan tindak lanjut kesehatan keluarga dengan pencatatan yang rapi.',
        ];
    }
}
