<?php

namespace App\Services;

use App\Enums\JenisNotifikasi;
use App\Enums\StatusPemenuhan;
use App\Enums\StatusResep;
use App\Models\Pengguna;
use App\Models\Resep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LayananResep
{
    public function __construct(private LayananNotifikasi $notifikasi) {}

    /**
     * Membuat nomor resep unik mengikuti pola "R-YYYYMMDD-XXXX".
     */
    public function nomorBaru(): string
    {
        return 'R-'.now()->format('Ymd').'-'.Str::upper(Str::random(4));
    }

    /**
     * Memfinalisasi resep: mengunci status, membuat tugas tindak lanjut obat,
     * dan mengirim notifikasi kepada petugas operasional yang berwenang.
     */
    public function finalisasi(Resep $resep): Resep
    {
        return DB::transaction(function () use ($resep): Resep {
            $resep->forceFill([
                'status_resep' => StatusResep::Difinalisasi->value,
                'difinalisasi_pada' => now(),
            ])->save();

            $resep->pemenuhanResep()->updateOrCreate(
                ['id_resep' => $resep->id_resep],
                ['status_pemenuhan' => StatusPemenuhan::Menunggu->value],
            );

            $this->beriTahuResepsionis($resep);

            return $resep->fresh();
        });
    }

    /**
     * Membatalkan resep yang sudah difinalisasi secara terlacak tanpa menimpa riwayat.
     */
    public function batalkan(Resep $resep, string $alasan): Resep
    {
        return DB::transaction(function () use ($resep, $alasan): Resep {
            $resep->forceFill([
                'status_resep' => StatusResep::Dibatalkan->value,
                'dibatalkan_pada' => now(),
                'alasan_pembatalan' => $alasan,
            ])->save();

            return $resep->fresh();
        });
    }

    private function beriTahuResepsionis(Resep $resep): void
    {
        $penerima = Pengguna::query()
            ->where('status_aktif', true)
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'resepsionis'))
            ->get();

        foreach ($penerima as $petugas) {
            $this->notifikasi->kirim(
                $petugas,
                JenisNotifikasi::ResepDifinalisasi,
                'Resep selesai — perlu tindak lanjut',
                'Resep untuk antrean '.$resep->kunjungan?->kodeAntrean().' telah difinalisasi. Silakan periksa tugas tindak lanjut obat.',
                ['id_kunjungan' => $resep->id_kunjungan, 'id_resep' => $resep->id_resep],
            );
        }
    }
}
