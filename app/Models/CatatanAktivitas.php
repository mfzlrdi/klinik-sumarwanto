<?php

namespace App\Models;

use App\Enums\JenisAktivitas;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_pengguna', 'jenis_aktivitas', 'nama_entitas', 'id_data_terkait', 'ringkasan_perubahan', 'alamat_ip'])]
class CatatanAktivitas extends Model
{
    protected $table = 'catatan_aktivitas';

    protected $primaryKey = 'id_log_aktivitas';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'jenis_aktivitas' => JenisAktivitas::class,
            'ringkasan_perubahan' => 'array',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    /**
     * Mencatat aktivitas penting untuk keperluan audit tanpa menyalin data klinis sensitif.
     */
    public static function catat(Pengguna $pengguna, JenisAktivitas $jenis, string $entitas, int $idData, array $ringkasan = []): self
    {
        return static::query()->create([
            'id_pengguna' => $pengguna->id_pengguna,
            'jenis_aktivitas' => $jenis->value,
            'nama_entitas' => $entitas,
            'id_data_terkait' => $idData,
            'ringkasan_perubahan' => $ringkasan,
            'alamat_ip' => request()?->ip(),
        ]);
    }
}
