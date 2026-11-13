<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_pemenuhan_resep', 'id_rincian_resep', 'jumlah_diserahkan', 'id_pengguna_penyerah', 'diserahkan_pada', 'catatan_penyerahan'])]
class RincianPenyerahanObat extends Model
{
    protected $table = 'rincian_penyerahan_obat';

    protected $primaryKey = 'id_rincian_penyerahan_obat';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'jumlah_diserahkan' => 'integer',
            'diserahkan_pada' => 'datetime',
        ];
    }

    public function pemenuhanResep(): BelongsTo
    {
        return $this->belongsTo(PemenuhanResep::class, 'id_pemenuhan_resep');
    }

    public function rincianResep(): BelongsTo
    {
        return $this->belongsTo(RincianResep::class, 'id_rincian_resep');
    }

    public function penyerah(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna_penyerah');
    }
}
