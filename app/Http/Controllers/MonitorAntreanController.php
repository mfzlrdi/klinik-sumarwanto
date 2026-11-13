<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MonitorAntreanController extends Controller
{
    public function index(): View
    {
        return view('antrean.monitor');
    }

    public function data(): JsonResponse
    {
        $dasar = Kunjungan::query()
            ->with(['pasien', 'dokter.pengguna'])
            ->whereDate('tanggal_kunjungan', today());

        $dipanggil = (clone $dasar)->where('status_kunjungan', 'dipanggil')->orderByDesc('diperbarui_pada')->first();
        $konsultasi = (clone $dasar)->where('status_kunjungan', 'sedang_konsultasi')->orderByDesc('diperbarui_pada')->first();
        $aktif = $dipanggil ?? $konsultasi;

        $kelompok = [
            'menunggu' => (clone $dasar)->where('status_kunjungan', 'menunggu')->orderBy('nomor_antrean')->limit(12)->get(),
            'dipanggil' => (clone $dasar)->where('status_kunjungan', 'dipanggil')->orderBy('nomor_antrean')->limit(6)->get(),
            'sedang_konsultasi' => (clone $dasar)->where('status_kunjungan', 'sedang_konsultasi')->orderBy('nomor_antrean')->limit(6)->get(),
            'selesai' => (clone $dasar)->where('status_kunjungan', 'selesai')->orderByDesc('diperbarui_pada')->limit(8)->get(),
        ];

        return response()->json([
            'aktif' => $aktif ? $this->ringkas($aktif) : null,
            'kelompok' => collect($kelompok)->map(fn ($daftar) => $daftar->map(fn (Kunjungan $k) => $this->ringkas($k))->values())->all(),
            'jumlah' => collect($kelompok)->map(fn ($daftar) => $daftar->count())->all(),
            'jam' => now()->format('H:i'),
            'tanggal' => now()->locale('id')->translatedFormat('l, j F Y'),
        ]);
    }

    private function ringkas(Kunjungan $kunjungan): array
    {
        $namaDokter = $kunjungan->dokter?->pengguna?->nama_lengkap;

        return [
            'id' => $kunjungan->id_kunjungan,
            'nomor' => $kunjungan->nomor_antrean,
            'kode' => $kunjungan->kodeAntrean(),
            'nama' => $this->samarkan($kunjungan->pasien?->nama_lengkap ?? '-'),
            'status' => $kunjungan->status_kunjungan?->value,
            'status_label' => $kunjungan->status_kunjungan?->label(),
            'dokter' => $namaDokter ? $this->namaDokter($namaDokter) : 'dr. Sumarwanto',
        ];
    }

    private function samarkan(string $nama): string
    {
        $bagian = preg_split('/\s+/', trim($nama)) ?: [];
        $depan = $bagian[0] ?? '';
        $inisial = count($bagian) > 1
            ? ' '.mb_strtoupper(mb_substr((string) end($bagian), 0, 1)).'.'
            : '';

        return $depan.$inisial;
    }

    private function namaDokter(string $nama): string
    {
        return str_starts_with($nama, 'dr.') ? $nama : 'dr. '.$nama;
    }
}
