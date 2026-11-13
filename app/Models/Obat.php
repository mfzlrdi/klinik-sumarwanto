<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_obat', 'kekuatan_obat', 'bentuk_sediaan', 'satuan_obat', 'status_aktif'])]
class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';

    protected $primaryKey = 'id_obat';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function rincianResep(): HasMany
    {
        return $this->hasMany(RincianResep::class, 'id_obat');
    }

    public function namaLengkap(): string
    {
        return collect([$this->nama_obat, $this->kekuatan_obat, $this->bentuk_sediaan])
            ->filter()
            ->implode(' ');
    }
}
