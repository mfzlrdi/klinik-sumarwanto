<?php

namespace App\Models;

use Database\Factories\PenggunaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama_lengkap', 'alamat_email', 'kata_sandi', 'status_aktif', 'token_ingatan', 'terakhir_masuk_pada'])]
#[Hidden(['kata_sandi', 'token_ingatan'])]
class Pengguna extends Authenticatable
{
    /** @use HasFactory<PenggunaFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    protected $authPasswordName = 'kata_sandi';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    public function getRememberTokenName(): string
    {
        return 'token_ingatan';
    }

    protected function casts(): array
    {
        return [
            'kata_sandi' => 'hashed',
            'status_aktif' => 'boolean',
            'terakhir_masuk_pada' => 'datetime',
        ];
    }

    public function peran(): BelongsToMany
    {
        return $this->belongsToMany(Peran::class, 'pengguna_peran', 'id_pengguna', 'id_peran');
    }

    public function memilikiPeran(string ...$peran): bool
    {
        return $this->peran->contains(fn (Peran $item): bool => in_array($item->nama_peran, $peran, true));
    }

    public function namaPeran(): string
    {
        return $this->peran
            ->pluck('nama_peran')
            ->map(fn (string $nama): string => ucfirst($nama))
            ->implode(' · ');
    }
}
