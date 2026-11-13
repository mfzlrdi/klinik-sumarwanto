<?php

namespace App\Models;

use App\Enums\StatusRekamMedis;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['id_kunjungan', 'id_dokter', 'hasil_pemeriksaan', 'tanda_vital', 'catatan_klinis', 'status_rekam_medis', 'difinalisasi_pada'])]
class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $primaryKey = 'id_rekam_medis';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected function casts(): array
    {
        return [
            'tanda_vital' => 'array',
            'status_rekam_medis' => StatusRekamMedis::class,
            'difinalisasi_pada' => 'datetime',
        ];
    }

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function diagnosa(): HasMany
    {
        return $this->hasMany(Diagnosa::class, 'id_rekam_medis');
    }

    public function tindakanMedis(): HasMany
    {
        return $this->hasMany(TindakanMedis::class, 'id_rekam_medis');
    }

    public function resep(): HasOne
    {
        return $this->hasOne(Resep::class, 'id_rekam_medis');
    }
}
