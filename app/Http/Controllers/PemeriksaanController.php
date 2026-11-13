<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PemeriksaanController extends Controller
{
    public function show(Kunjungan $kunjungan): View
    {
        Gate::authorize('view', $kunjungan);

        $kunjungan->load([
            'pasien',
            'dokter.pengguna',
            'rekamMedis.diagnosa',
            'rekamMedis.tindakanMedis',
            'resep.rincianResep',
            'resep.pemenuhanResep',
            'suratKeteranganSakit.dokter.pengguna',
        ]);

        $riwayat = RekamMedis::query()
            ->with(['kunjungan.dokter.pengguna', 'diagnosa'])
            ->whereHas('kunjungan', fn ($q) => $q->where('id_pasien', $kunjungan->id_pasien)->where('id_kunjungan', '!=', $kunjungan->id_kunjungan))
            ->latest('dibuat_pada')
            ->limit(10)
            ->get();

        return view('pemeriksaan.show', [
            'kunjungan' => $kunjungan,
            'riwayat' => $riwayat,
            'obat' => Obat::query()->where('status_aktif', true)->orderBy('nama_obat')->get(),
        ]);
    }
}
