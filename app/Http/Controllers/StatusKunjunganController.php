<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Enums\JenisNotifikasi;
use App\Enums\StatusKunjungan;
use App\Models\CatatanAktivitas;
use App\Models\Kunjungan;
use App\Models\Pengguna;
use App\Services\LayananNotifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StatusKunjunganController extends Controller
{
    public function __construct(private LayananNotifikasi $notifikasi) {}

    public function panggil(Request $request, Kunjungan $kunjungan): RedirectResponse|JsonResponse
    {
        $this->authorize('panggil', $kunjungan);

        return $this->ubahStatus($request, $kunjungan, StatusKunjungan::Dipanggil, 'Pasien dipanggil', 'Antrean '.$kunjungan->kodeAntrean().' telah dipanggil.');
    }

    public function mulaiKonsultasi(Request $request, Kunjungan $kunjungan): RedirectResponse|JsonResponse
    {
        $this->authorize('mulaiKonsultasi', $kunjungan);

        $respons = $this->ubahStatus($request, $kunjungan, StatusKunjungan::SedangKonsultasi, 'Konsultasi dimulai', 'Konsultasi untuk antrean '.$kunjungan->kodeAntrean().' dimulai.');

        if ($request->boolean('lanjut') && ! $request->expectsJson()) {
            return redirect()->route('pemeriksaan.show', $kunjungan)
                ->with('toast', ['jenis' => 'sukses', 'judul' => 'Konsultasi dimulai', 'pesan' => 'Silakan lakukan pemeriksaan pasien.']);
        }

        return $respons;
    }

    public function selesai(Request $request, Kunjungan $kunjungan): RedirectResponse|JsonResponse
    {
        $this->authorize('selesai', $kunjungan);

        return $this->ubahStatus($request, $kunjungan, StatusKunjungan::Selesai, 'Kunjungan selesai', 'Kunjungan antrean '.$kunjungan->kodeAntrean().' telah diselesaikan.');
    }

    public function batalkan(Request $request, Kunjungan $kunjungan): RedirectResponse|JsonResponse
    {
        $this->authorize('batalkan', $kunjungan);

        $alasan = $request->validate([
            'alasan_pembatalan' => ['required', 'string', 'max:500'],
        ])['alasan_pembatalan'];

        $kunjungan->forceFill([
            'status_kunjungan' => StatusKunjungan::Dibatalkan->value,
            'catatan_administratif' => $alasan,
        ])->save();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Pembatalan, 'kunjungan', $kunjungan->id_kunjungan, ['alasan' => $alasan]);

        $this->beriTahuResepsionis('Pendaftaran dibatalkan', 'Kunjungan antrean '.$kunjungan->kodeAntrean().' telah dibatalkan.');

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'status' => 'dibatalkan', 'pesan' => 'Pendaftaran dibatalkan.']);
        }

        return back()->with('toast', ['jenis' => 'peringatan', 'judul' => 'Kunjungan dibatalkan', 'pesan' => 'Pendaftaran telah dibatalkan.']);
    }

    private function ubahStatus(Request $request, Kunjungan $kunjungan, StatusKunjungan $target, string $judul, string $pesan): RedirectResponse|JsonResponse
    {
        $dari = $kunjungan->status_kunjungan?->value ?? 'menunggu';

        abort_unless(StatusKunjungan::bolehTransisi($dari, $target->value), 422, 'Transisi status tidak sah.');

        $kunjungan->forceFill(['status_kunjungan' => $target->value])->save();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Status, 'kunjungan', $kunjungan->id_kunjungan, ['dari' => $dari, 'ke' => $target->value]);

        $this->beriTahuResepsionis($judul, $pesan);

        if ($request->expectsJson()) {
            $kunjungan->loadMissing('dokter.pengguna');

            return response()->json([
                'ok' => true,
                'id_kunjungan' => $kunjungan->id_kunjungan,
                'kode_antrean' => $kunjungan->kodeAntrean(),
                'status' => $target->value,
                'status_label' => $target->label(),
                'nama_dokter' => $this->namaDokter($kunjungan),
                'pesan' => $pesan,
            ]);
        }

        return back()->with('toast', ['jenis' => 'sukses', 'judul' => $judul, 'pesan' => $pesan]);
    }

    private function namaDokter(Kunjungan $kunjungan): string
    {
        $nama = $kunjungan->dokter?->pengguna?->nama_lengkap;

        if ($nama === null) {
            return 'dr. Sumarwanto';
        }

        return str_starts_with($nama, 'dr.') ? $nama : 'dr. '.$nama;
    }

    private function beriTahuResepsionis(string $judul, string $pesan): void
    {
        Pengguna::query()
            ->where('status_aktif', true)
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'resepsionis'))
            ->get()
            ->each(fn (Pengguna $p) => $this->notifikasi->kirim($p, JenisNotifikasi::StatusAntrean, $judul, $pesan));
    }
}
