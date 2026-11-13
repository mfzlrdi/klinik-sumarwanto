<?php

namespace App\Models;

use App\Enums\StatusKunjungan;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['id_pasien', 'id_dokter', 'tanggal_kunjungan', 'nomor_antrean', 'keluhan_awal', 'status_kunjungan', 'id_pengguna_pendaftar', 'catatan_administratif'])]
class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $primaryKey = 'id_kunjungan';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected function casts(): array
    {
        return [
            'tanggal_kunjungan' => 'date',
            'nomor_antrean' => 'integer',
            'status_kunjungan' => StatusKunjungan::class,
        ];
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_pendaftar');
    }

    public function rekamMedis(): HasOne
    {
        return $this->hasOne(RekamMedis::class, 'id_kunjungan');
    }

    public function resep(): HasOne
    {
        return $this->hasOne(Resep::class, 'id_kunjungan');
    }

    public function suratKeteranganSakit(): HasMany
    {
        return $this->hasMany(SuratKeteranganSakit::class, 'id_kunjungan');
    }

    public function kodeAntrean(): string
    {
        return 'A-'.str_pad((string) $this->nomor_antrean, 3, '0', STR_PAD_LEFT);
    }
}
