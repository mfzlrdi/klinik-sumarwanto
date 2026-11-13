<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifikasi = $request->user()
            ->notifikasiTerbaru()
            ->limit(20)
            ->get()
            ->map(fn (Notifikasi $n): array => [
                'id' => $n->id_notifikasi,
                'jenis' => $n->jenis_notifikasi?->value,
                'judul' => $n->judul_notifikasi,
                'pesan' => $n->pesan_notifikasi,
                'data' => $n->data_tambahan,
                'dibaca' => $n->dibaca_pada !== null,
                'waktu' => $n->dibuat_pada?->diffForHumans(),
                'tautan' => $this->tautan($n),
            ]);

        return response()->json([
            'notifikasi' => $notifikasi,
            'belum_dibaca' => $request->user()->jumlahNotifikasiBelumDibaca(),
        ]);
    }

    public function tandaiDibaca(Request $request, Notifikasi $notifikasi): JsonResponse
    {
        abort_unless($notifikasi->id_pengguna_penerima === $request->user()->id_pengguna, 403);

        $notifikasi->tandaiDibaca();

        return response()->json(['belum_dibaca' => $request->user()->jumlahNotifikasiBelumDibaca()]);
    }

    public function tandaiSemuaDibaca(Request $request): JsonResponse
    {
        $request->user()
            ->notifikasiTerbaru()
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return response()->json(['belum_dibaca' => 0]);
    }

    private function tautan(Notifikasi $n): ?string
    {
        $data = $n->data_tambahan ?? [];

        return match ($n->jenis_notifikasi?->value) {
            'kunjungan_baru', 'status_antrean' => isset($data['id_kunjungan']) ? route('kunjungan.show', $data['id_kunjungan']) : null,
            'resep_difinalisasi', 'tindak_lanjut_obat' => route('pemenuhan.index'),
            'penyerahan_obat' => isset($data['id_resep']) ? route('resep.show', $data['id_resep']) : null,
            default => null,
        };
    }
}
