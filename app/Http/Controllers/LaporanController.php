<?php

namespace App\Http\Controllers;

use App\Enums\StatusKunjungan;
use App\Models\Kunjungan;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function __invoke(Request $request): View
    {
        Gate::authorize('viewAny', Pasien::class);

        $pengguna = $request->user();
        abort_unless($pengguna->memilikiPeran('administrator', 'dokter'), 403);

        $dari = ($request->date('dari') ?? today()->startOfMonth())->format('Y-m-d');
        $sampai = ($request->date('sampai') ?? today())->format('Y-m-d');
        $status = $request->string('status')->toString();

        $kunjungan = Kunjungan::query()
            ->with(['pasien', 'dokter.pengguna'])
            ->whereBetween('tanggal_kunjungan', [$dari, $sampai])
            ->when($status !== '', fn ($q) => $q->where('status_kunjungan', $status))
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('nomor_antrean')
            ->paginate(20)
            ->withQueryString();

        $ringkasan = [
            'total_kunjungan' => Kunjungan::query()->whereBetween('tanggal_kunjungan', [$dari, $sampai])->count(),
            'selesai' => Kunjungan::query()->whereBetween('tanggal_kunjungan', [$dari, $sampai])->where('status_kunjungan', 'selesai')->count(),
            'dibatalkan' => Kunjungan::query()->whereBetween('tanggal_kunjungan', [$dari, $sampai])->where('status_kunjungan', 'dibatalkan')->count(),
            'total_pasien' => Pasien::query()->count(),
        ];

        return view('laporan.index', [
            'kunjungan' => $kunjungan,
            'ringkasan' => $ringkasan,
            'dari' => $dari,
            'sampai' => $sampai,
            'status' => $status,
        ]);
    }

    public function cetak(Request $request): View
    {
        $pengguna = $request->user();
        abort_unless($pengguna->memilikiPeran('administrator', 'dokter'), 403);

        $dari = ($request->date('dari') ?? today()->startOfMonth())->format('Y-m-d');
        $sampai = ($request->date('sampai') ?? today())->format('Y-m-d');
        $status = $request->string('status')->toString();

        $kunjungan = Kunjungan::query()
            ->with(['pasien', 'dokter.pengguna'])
            ->whereBetween('tanggal_kunjungan', [$dari, $sampai])
            ->when($status !== '', fn ($q) => $q->where('status_kunjungan', $status))
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('nomor_antrean')
            ->get();

        $ringkasan = [
            'total_kunjungan' => $kunjungan->count(),
            'selesai' => $kunjungan->where('status_kunjungan', StatusKunjungan::Selesai)->count(),
            'dibatalkan' => $kunjungan->where('status_kunjungan', StatusKunjungan::Dibatalkan)->count(),
        ];

        return view('laporan.cetak', [
            'kunjungan' => $kunjungan,
            'ringkasan' => $ringkasan,
            'dari' => $dari,
            'sampai' => $sampai,
        ]);
    }
}
