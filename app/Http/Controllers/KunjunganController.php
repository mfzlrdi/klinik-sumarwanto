<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Enums\JenisNotifikasi;
use App\Enums\StatusKunjungan;
use App\Models\CatatanAktivitas;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Pengguna;
use App\Services\LayananAntrean;
use App\Services\LayananNotifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class KunjunganController extends Controller
{
    public function __construct(
        private LayananAntrean $antrean,
        private LayananNotifikasi $notifikasi,
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Kunjungan::class);

        $tanggal = ($request->date('tanggal') ?? today())->format('Y-m-d');
        $status = $request->string('status')->toString();

        $kunjungan = Kunjungan::query()
            ->with(['pasien', 'dokter.pengguna'])
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->when($status !== '', fn ($q) => $q->where('status_kunjungan', $status))
            ->orderBy('nomor_antrean')
            ->paginate(15)
            ->withQueryString();

        return view('kunjungan.index', [
            'kunjungan' => $kunjungan,
            'tanggal' => $tanggal,
            'status' => $status,
            'statusKunjungan' => StatusKunjungan::cases(),
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Kunjungan::class);

        $pasien = null;
        if ($request->filled('id_pasien')) {
            $pasien = Pasien::query()->find($request->integer('id_pasien'));
        }

        return view('kunjungan.create', [
            'pasien' => $pasien,
            'tanggal' => today()->format('Y-m-d'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Kunjungan::class);

        $data = $request->validate([
            'id_pasien' => ['required', 'integer', 'exists:pasien,id_pasien'],
            'tanggal_kunjungan' => ['required', 'date', 'after_or_equal:today'],
            'keluhan_awal' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'id_pasien' => 'pasien',
            'tanggal_kunjungan' => 'tanggal kunjungan',
            'keluhan_awal' => 'keluhan awal',
        ]);

        $pasien = Pasien::query()->findOrFail($data['id_pasien']);
        $kunjungan = $this->antrean->daftarkan($pasien, $data['tanggal_kunjungan'], $data['keluhan_awal'] ?? null, $request->user());

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'kunjungan', $kunjungan->id_kunjungan, ['nomor_antrean' => $kunjungan->nomor_antrean]);

        $this->beriTahuDokter($kunjungan);

        return redirect()->route('kunjungan.cetak', $kunjungan)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Pendaftaran berhasil', 'pesan' => 'Pasien terdaftar dengan antrean '.$kunjungan->kodeAntrean().'.']);
    }

    public function show(Kunjungan $kunjungan): View
    {
        Gate::authorize('view', $kunjungan);

        $kunjungan->load(['pasien', 'dokter.pengguna', 'pendaftar', 'rekamMedis']);

        return view('kunjungan.show', ['kunjungan' => $kunjungan]);
    }

    public function cetak(Kunjungan $kunjungan): View
    {
        Gate::authorize('view', $kunjungan);

        $kunjungan->load(['pasien', 'pendaftar']);

        return view('kunjungan.cetak', ['kunjungan' => $kunjungan]);
    }

    private function beriTahuDokter(Kunjungan $kunjungan): void
    {
        $penerima = Pengguna::query()
            ->where('status_aktif', true)
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'dokter'))
            ->get();

        foreach ($penerima as $dokter) {
            $this->notifikasi->kirim(
                $dokter,
                JenisNotifikasi::KunjunganBaru,
                'Kunjungan baru terdaftar',
                'Pasien '.$kunjungan->pasien?->nama_lengkap.' masuk antrean dengan nomor '.$kunjungan->kodeAntrean().'.',
                ['id_kunjungan' => $kunjungan->id_kunjungan],
            );
        }
    }
}
