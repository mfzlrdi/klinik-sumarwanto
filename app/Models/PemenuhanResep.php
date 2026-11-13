<?php

namespace App\Models;

use App\Enums\StatusPemenuhan;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_resep', 'status_pemenuhan', 'id_pengguna_penanggung_jawab', 'catatan_pemenuhan', 'mulai_diproses_pada', 'selesai_diproses_pada'])]
class PemenuhanResep extends Model
{
    use HasFactory;

    protected $table = 'pemenuhan_resep';

    protected $primaryKey = 'id_pemenuhan_resep';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected function casts(): array
    {
        return [
            'status_pemenuhan' => StatusPemenuhan::class,
            'mulai_diproses_pada' => 'datetime',
            'selesai_diproses_pada' => 'datetime',
        ];
    }

    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class, 'id_resep');
    }

    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_penanggung_jawab');
    }

    public function penyerahan(): HasMany
    {
        return $this->hasMany(RincianPenyerahanObat::class, 'id_pemenuhan_resep');
    }
}
