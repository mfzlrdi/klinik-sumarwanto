<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Enums\StatusResep;
use App\Models\CatatanAktivitas;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Resep;
use App\Services\LayananResep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ResepController extends Controller
{
    public function __construct(private LayananResep $layananResep) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Resep::class);

        $status = $request->string('status')->toString();

        $resep = Resep::query()
            ->with(['kunjungan.pasien', 'dokter.pengguna', 'rincianResep'])
            ->when($status !== '', fn ($q) => $q->where('status_resep', $status))
            ->latest('dibuat_pada')
            ->paginate(15)
            ->withQueryString();

        return view('resep.index', ['resep' => $resep, 'status' => $status]);
    }

    public function create(Kunjungan $kunjungan): View
    {
        Gate::authorize('create', Resep::class);

        $kunjungan->load('pasien');

        return view('resep.create', [
            'kunjungan' => $kunjungan,
            'obat' => Obat::query()->where('status_aktif', true)->orderBy('nama_obat')->get(),
        ]);
    }

    public function store(Request $request, Kunjungan $kunjungan): RedirectResponse
    {
        Gate::authorize('create', Resep::class);

        $data = $this->validasi($request);

        $resep = DB::transaction(function () use ($kunjungan, $data, $request): Resep {
            $resep = Resep::query()->create([
                'id_kunjungan' => $kunjungan->id_kunjungan,
                'id_rekam_medis' => $kunjungan->rekamMedis?->id_rekam_medis,
                'id_dokter' => $this->dokterAktif($request),
                'nomor_resep' => $this->layananResep->nomorBaru(),
                'status_resep' => StatusResep::Draf->value,
                'catatan_resep' => $data['catatan_resep'] ?? null,
            ]);

            $this->simpanRincian($resep, $data['rincian']);

            return $resep;
        });

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'resep', $resep->id_resep, ['nomor_resep' => $resep->nomor_resep]);

        $tujuan = $request->string('konteks')->toString() === 'pemeriksaan'
            ? route('pemeriksaan.show', $kunjungan)
            : route('resep.show', $resep);

        return redirect($tujuan)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Resep dibuat', 'pesan' => 'Resep disimpan sebagai draf dan dapat difinalisasi.']);
    }

    public function show(Resep $resep): View
    {
        Gate::authorize('view', $resep);

        $resep->load(['kunjungan.pasien', 'dokter.pengguna', 'rekamMedis', 'rincianResep.obat', 'pemenuhanResep.penanggungJawab']);

        return view('resep.show', ['resep' => $resep]);
    }

    public function edit(Resep $resep): View
    {
        Gate::authorize('update', $resep);

        abort_if($resep->status_resep?->value !== 'draf', 403, 'Hanya resep berstatus draf yang dapat diubah.');

        $resep->load(['kunjungan.pasien', 'rincianResep']);

        return view('resep.edit', [
            'resep' => $resep,
            'obat' => Obat::query()->where('status_aktif', true)->orderBy('nama_obat')->get(),
        ]);
    }

    public function update(Request $request, Resep $resep): RedirectResponse
    {
        Gate::authorize('update', $resep);

        abort_if($resep->status_resep?->value !== 'draf', 403);

        $data = $this->validasi($request);

        DB::transaction(function () use ($resep, $data): void {
            $resep->update(['catatan_resep' => $data['catatan_resep'] ?? null]);
            $resep->rincianResep()->delete();
            $this->simpanRincian($resep, $data['rincian']);
        });

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Perbarui, 'resep', $resep->id_resep);

        $tujuan = $request->string('konteks')->toString() === 'pemeriksaan'
            ? route('pemeriksaan.show', $resep->id_kunjungan)
            : route('resep.show', $resep);

        return redirect($tujuan)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Resep diperbarui', 'pesan' => 'Perubahan resep berhasil disimpan.']);
    }

    public function finalisasi(Request $request, Resep $resep): RedirectResponse
    {
        Gate::authorize('finalisasi', $resep);

        abort_if($resep->status_resep?->value === 'difinalisasi', 422, 'Resep sudah difinalisasi.');

        $this->layananResep->finalisasi($resep);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Finalisasi, 'resep', $resep->id_resep, ['nomor_resep' => $resep->nomor_resep]);

        return back()->with('toast', ['jenis' => 'sukses', 'judul' => 'Resep difinalisasi', 'pesan' => 'Resep telah disahkan dan tugas tindak lanjut obat dibuat.']);
    }

    public function batalkan(Request $request, Resep $resep): RedirectResponse
    {
        Gate::authorize('batalkan', $resep);

        $alasan = $request->validate([
            'alasan_pembatalan' => ['required', 'string', 'max:500'],
        ])['alasan_pembatalan'];

        $this->layananResep->batalkan($resep, $alasan);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Pembatalan, 'resep', $resep->id_resep, ['alasan' => $alasan]);

        return back()->with('toast', ['jenis' => 'peringatan', 'judul' => 'Resep dibatalkan', 'pesan' => 'Resep telah dibatalkan dan tercatat dalam riwayat.']);
    }

    public function cetak(Resep $resep): View
    {
        Gate::authorize('view', $resep);

        $resep->load(['kunjungan.pasien', 'dokter.pengguna', 'rincianResep']);

        return view('resep.cetak', ['resep' => $resep]);
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'catatan_resep' => ['nullable', 'string'],
            'rincian' => ['required', 'array', 'min:1'],
            'rincian.*.id_obat' => ['required', 'integer', 'exists:obat,id_obat'],
            'rincian.*.dosis' => ['nullable', 'string', 'max:80'],
            'rincian.*.frekuensi_pemberian' => ['nullable', 'string', 'max:120'],
            'rincian.*.cara_pemberian' => ['nullable', 'string', 'max:120'],
            'rincian.*.lama_penggunaan_hari' => ['nullable', 'integer', 'min:1'],
            'rincian.*.jumlah_diresepkan' => ['required', 'integer', 'min:1'],
            'rincian.*.satuan_jumlah' => ['nullable', 'string', 'max:30'],
            'rincian.*.petunjuk_penggunaan' => ['nullable', 'string'],
        ], [], ['rincian' => 'rincian obat']);
    }

    private function simpanRincian(Resep $resep, array $daftar): void
    {
        foreach ($daftar as $item) {
            $obat = Obat::query()->find($item['id_obat']);

            $resep->rincianResep()->create([
                'id_obat' => $obat?->id_obat,
                'nama_obat_saat_diresepkan' => $obat?->nama_obat ?? '',
                'kekuatan_saat_diresepkan' => $obat?->kekuatan_obat,
                'bentuk_sediaan_saat_diresepkan' => $obat?->bentuk_sediaan,
                'dosis' => $item['dosis'] ?? null,
                'frekuensi_pemberian' => $item['frekuensi_pemberian'] ?? null,
                'cara_pemberian' => $item['cara_pemberian'] ?? null,
                'lama_penggunaan_hari' => $item['lama_penggunaan_hari'] ?? null,
                'jumlah_diresepkan' => $item['jumlah_diresepkan'],
                'satuan_jumlah' => $item['satuan_jumlah'] ?? $obat?->satuan_obat,
                'petunjuk_penggunaan' => $item['petunjuk_penggunaan'] ?? null,
            ]);
        }
    }

    private function dokterAktif(Request $request): ?int
    {
        return Dokter::query()->where('id_pengguna', $request->user()->id_pengguna)->value('id_dokter');
    }
}
