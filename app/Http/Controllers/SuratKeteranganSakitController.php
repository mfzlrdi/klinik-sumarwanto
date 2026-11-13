<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Models\CatatanAktivitas;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\SuratKeteranganSakit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SuratKeteranganSakitController extends Controller
{
    public function store(Request $request, Kunjungan $kunjungan): RedirectResponse
    {
        abort_unless($request->user()->memilikiPeran('dokter', 'administrator'), 403);

        $data = $request->validate([
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'diagnosa_ringkas' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
            'diagnosa_ringkas' => 'diagnosa ringkas',
        ]);

        $surat = DB::transaction(fn (): SuratKeteranganSakit => $kunjungan->suratKeteranganSakit()->create([
            'id_dokter' => Dokter::query()->where('id_pengguna', $request->user()->id_pengguna)->value('id_dokter'),
            'nomor_surat' => $this->nomorBaru(),
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'diagnosa_ringkas' => $data['diagnosa_ringkas'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
        ]));

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'surat_keterangan_sakit', $surat->id_surat_keterangan_sakit, ['nomor_surat' => $surat->nomor_surat]);

        $tujuan = $request->string('konteks')->toString() === 'pemeriksaan'
            ? route('surat-sakit.cetak', ['suratKeteranganSakit' => $surat, 'konteks' => 'pemeriksaan'])
            : route('surat-sakit.cetak', $surat);

        return redirect($tujuan)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Surat sakit dibuat', 'pesan' => 'Surat keterangan sakit berhasil dibuat dan siap dicetak.']);
    }

    public function cetak(Request $request, SuratKeteranganSakit $suratKeteranganSakit): View
    {
        abort_unless($request->user()->memilikiPeran('dokter', 'administrator'), 403);

        $suratKeteranganSakit->load(['kunjungan.pasien', 'dokter.pengguna']);

        return view('surat-sakit.cetak', [
            'surat' => $suratKeteranganSakit,
            'konteks' => $request->string('konteks')->toString(),
        ]);
    }

    public function destroy(Request $request, SuratKeteranganSakit $suratKeteranganSakit): RedirectResponse
    {
        abort_unless($request->user()->memilikiPeran('dokter', 'administrator'), 403);

        $idKunjungan = $suratKeteranganSakit->id_kunjungan;
        $suratKeteranganSakit->delete();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Hapus, 'surat_keterangan_sakit', $suratKeteranganSakit->id_surat_keterangan_sakit);

        return redirect()->route('pemeriksaan.show', $idKunjungan)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Surat dihapus', 'pesan' => 'Surat keterangan sakit telah dihapus.']);
    }

    private function nomorBaru(): string
    {
        return 'SKS-'.now()->format('Ymd').'-'.mb_strtoupper(mb_substr((string) Str::random(4), 0, 4));
    }
}
