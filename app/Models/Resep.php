<?php

namespace App\Models;

use App\Enums\StatusResep;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['id_kunjungan', 'id_rekam_medis', 'id_dokter', 'nomor_resep', 'status_resep', 'catatan_resep', 'difinalisasi_pada', 'dibatalkan_pada', 'alasan_pembatalan'])]
class Resep extends Model
{
    use HasFactory;

    protected $table = 'resep';

    protected $primaryKey = 'id_resep';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected function casts(): array
    {
        return [
            'status_resep' => StatusResep::class,
            'difinalisasi_pada' => 'datetime',
            'dibatalkan_pada' => 'datetime',
        ];
    }

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan');
    }

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function rincianResep(): HasMany
    {
        return $this->hasMany(RincianResep::class, 'id_resep');
    }

    public function pemenuhanResep(): HasOne
    {
        return $this->hasOne(PemenuhanResep::class, 'id_resep');
    }
}
