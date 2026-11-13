<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_resep', 'id_obat', 'nama_obat_saat_diresepkan', 'kekuatan_saat_diresepkan', 'bentuk_sediaan_saat_diresepkan', 'dosis', 'frekuensi_pemberian', 'cara_pemberian', 'lama_penggunaan_hari', 'jumlah_diresepkan', 'satuan_jumlah', 'petunjuk_penggunaan'])]
class RincianResep extends Model
{
    use HasFactory;

    protected $table = 'rincian_resep';

    protected $primaryKey = 'id_rincian_resep';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected function casts(): array
    {
        return [
            'lama_penggunaan_hari' => 'integer',
            'jumlah_diresepkan' => 'integer',
        ];
    }

    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class, 'id_resep');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function penyerahan(): HasMany
    {
        return $this->hasMany(RincianPenyerahanObat::class, 'id_rincian_resep');
    }

    public function totalTerserahkan(): int
    {
        return $this->penyerahan()->sum('jumlah_diserahkan');
    }
}
