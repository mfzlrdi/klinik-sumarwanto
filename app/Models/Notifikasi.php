<?php

namespace App\Models;

use App\Enums\JenisNotifikasi;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_pengguna_penerima', 'jenis_notifikasi', 'judul_notifikasi', 'pesan_notifikasi', 'data_tambahan', 'dibaca_pada'])]
class Notifikasi extends Model
{
    use HasUuids;

    protected $table = 'notifikasi';

    protected $primaryKey = 'id_notifikasi';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'jenis_notifikasi' => JenisNotifikasi::class,
            'data_tambahan' => 'array',
            'dibaca_pada' => 'datetime',
        ];
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_penerima');
    }

    public function tandaiDibaca(): void
    {
        if ($this->dibaca_pada === null) {
            $this->forceFill(['dibaca_pada' => now()])->save();
        }
    }
}
