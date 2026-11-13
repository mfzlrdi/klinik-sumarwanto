<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_rekam_medis', 'nama_tindakan', 'catatan_tindakan'])]
class TindakanMedis extends Model
{
    use HasFactory;

    protected $table = 'tindakan_medis';

    protected $primaryKey = 'id_tindakan_medis';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }
}
