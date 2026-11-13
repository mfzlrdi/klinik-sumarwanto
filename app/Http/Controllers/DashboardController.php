<?php

namespace App\Http\Controllers;

use App\Models\CatatanAktivitas;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\PemenuhanResep;
use App\Models\Pengguna;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $pengguna = $request->user()->loadMissing('peran');

        abort_unless($pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator'), 403);

        $peranAktif = $this->peranAktif($request, $pengguna);

        return match ($peranAktif) {
            'dokter' => $this->dashboardDokter($pengguna, $peranAktif),
            'resepsionis' => $this->dashboardResepsionis($pengguna, $peranAktif),
            default => $this->dashboardAdministrator($pengguna, $peranAktif),
        };
    }

    private function peranAktif(Request $request, Pengguna $pengguna): string
    {
        $daftar = $pengguna->peran->pluck('nama_peran')->all();
        $urutan = ['administrator', 'dokter', 'resepsionis'];

        $dipilih = $request->string('sebagai')->toString();
        if ($dipilih !== '' && in_array($dipilih, $daftar, true)) {
            session(['peran_aktif' => $dipilih]);

            return $dipilih;
        }

        $tersimpan = session('peran_aktif');
        if ($tersimpan !== null && in_array($tersimpan, $daftar, true)) {
            return $tersimpan;
        }

        foreach ($urutan as $kandidat) {
            if (in_array($kandidat, $daftar, true)) {
                return $kandidat;
            }
        }

        return 'resepsionis';
    }

    private function dashboardResepsionis(Pengguna $pengguna, string $peranAktif): View
    {
        $hariIni = today();

        return view('dashboard.resepsionis', [
            'pengguna' => $pengguna,
            'peranAktif' => $peranAktif,
            'sapaan' => $this->sapaan(),
            'totalPasien' => Pasien::query()->count(),
            'antreanHariIni' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->count(),
            'sedangDilayani' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->whereIn('status_kunjungan', ['dipanggil', 'sedang_konsultasi'])->count(),
            'selesaiHariIni' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->where('status_kunjungan', 'selesai')->count(),
            'antreanTerbaru' => Kunjungan::query()->with('pasien')->whereDate('tanggal_kunjungan', $hariIni)->orderBy('nomor_antrean')->limit(8)->get(),
            'tindakLanjut' => PemenuhanResep::query()
                ->with(['resep.kunjungan.pasien', 'resep.rincianResep'])
                ->whereHas('resep', fn ($q) => $q->where('status_resep', 'difinalisasi'))
                ->whereIn('status_pemenuhan', ['menunggu', 'sedang_diproses', 'siap_diserahkan', 'diserahkan_sebagian'])
                ->latest('diperbarui_pada')
                ->limit(5)
                ->get(),
        ]);
    }

    private function dashboardDokter(Pengguna $pengguna, string $peranAktif): View
    {
        $hariIni = today();

        return view('dashboard.dokter', [
            'pengguna' => $pengguna,
            'peranAktif' => $peranAktif,
            'sapaan' => $this->sapaan(),
            'antreanMenunggu' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->whereIn('status_kunjungan', ['menunggu', 'dipanggil'])->count(),
            'pasienHariIni' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->count(),
            'sedangKonsultasi' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->where('status_kunjungan', 'sedang_konsultasi')->count(),
            'selesaiHariIni' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->where('status_kunjungan', 'selesai')->count(),
            'antrean' => Kunjungan::query()
                ->with(['pasien', 'rekamMedis'])
                ->whereDate('tanggal_kunjungan', $hariIni)
                ->whereIn('status_kunjungan', ['menunggu', 'dipanggil', 'sedang_konsultasi'])
                ->orderBy('nomor_antrean')
                ->limit(10)
                ->get(),
            'drafResep' => Resep::query()->where('status_resep', 'draf')->count(),
        ]);
    }

    private function dashboardAdministrator(Pengguna $pengguna, string $peranAktif): View
    {
        $hariIni = today();

        return view('dashboard.administrator', [
            'pengguna' => $pengguna,
            'peranAktif' => $peranAktif,
            'sapaan' => $this->sapaan(),
            'totalPasien' => Pasien::query()->count(),
            'kunjunganHariIni' => Kunjungan::query()->whereDate('tanggal_kunjungan', $hariIni)->count(),
            'totalPengguna' => Pengguna::query()->count(),
            'resepHariIni' => Resep::query()->whereDate('difinalisasi_pada', $hariIni)->count(),
            'grafikKunjungan' => $this->grafikKunjungan(),
            'aktivitasTerbaru' => CatatanAktivitas::query()->with('pengguna')->latest('dibuat_pada')->limit(8)->get(),
        ]);
    }

    /**
     * @return array{label: string[], jumlah: int[]}
     */
    private function grafikKunjungan(): array
    {
        $label = [];
        $jumlah = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = today()->subDays($i);
            $label[] = $tanggal->translatedFormat('D');
            $jumlah[] = Kunjungan::query()->whereDate('tanggal_kunjungan', $tanggal)->count();
        }

        return ['label' => $label, 'jumlah' => $jumlah];
    }

    private function sapaan(): string
    {
        return match (true) {
            now()->hour < 11 => 'Selamat pagi',
            now()->hour < 15 => 'Selamat siang',
            now()->hour < 19 => 'Selamat sore',
            default => 'Selamat malam',
        };
    }
}
