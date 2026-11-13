<?php

namespace App\Models;

use App\Enums\JenisDiagnosa;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_rekam_medis', 'kode_diagnosa', 'nama_diagnosa', 'jenis_diagnosa', 'catatan_diagnosa'])]
class Diagnosa extends Model
{
    use HasFactory;

    protected $table = 'diagnosa';

    protected $primaryKey = 'id_diagnosa';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected function casts(): array
    {
        return [
            'jenis_diagnosa' => JenisDiagnosa::class,
        ];
    }

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis');
    }
}
