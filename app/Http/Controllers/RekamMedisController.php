<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Enums\StatusRekamMedis;
use App\Models\CatatanAktivitas;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\RekamMedis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RekamMedisController extends Controller
{
    public function show(RekamMedis $rekamMedis): View
    {
        Gate::authorize('view', $rekamMedis);

        $rekamMedis->load(['kunjungan.pasien', 'dokter.pengguna', 'diagnosa', 'tindakanMedis', 'resep.rincianResep']);

        return view('rekam-medis.show', ['rekamMedis' => $rekamMedis]);
    }

    public function create(Kunjungan $kunjungan): View
    {
        Gate::authorize('create', RekamMedis::class);

        abort_if($kunjungan->rekamMedis()->exists(), 409, 'Catatan medis untuk kunjungan ini sudah dibuat.');

        $kunjungan->load('pasien');

        return view('rekam-medis.create', ['kunjungan' => $kunjungan]);
    }

    public function store(Request $request, Kunjungan $kunjungan): RedirectResponse
    {
        Gate::authorize('create', RekamMedis::class);

        abort_if($kunjungan->rekamMedis()->exists(), 409);

        $data = $this->validasi($request);

        $rekamMedis = DB::transaction(function () use ($kunjungan, $data, $request): RekamMedis {
            $rekamMedis = RekamMedis::query()->create([
                'id_kunjungan' => $kunjungan->id_kunjungan,
                'id_dokter' => $this->dokterAktif($request),
                'hasil_pemeriksaan' => $data['hasil_pemeriksaan'] ?? null,
                'tanda_vital' => $data['tanda_vital'] ?? null,
                'catatan_klinis' => $data['catatan_klinis'] ?? null,
                'status_rekam_medis' => StatusRekamMedis::Draf->value,
            ]);

            $this->simpanDiagnosa($rekamMedis, $data['diagnosa'] ?? []);
            $this->simpanTindakan($rekamMedis, $data['tindakan'] ?? []);

            return $rekamMedis;
        });

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'rekam_medis', $rekamMedis->id_rekam_medis);

        $tujuan = $request->string('konteks')->toString() === 'pemeriksaan'
            ? route('pemeriksaan.show', $kunjungan)
            : route('rekam-medis.show', $rekamMedis);

        return redirect($tujuan)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Catatan tersimpan', 'pesan' => 'Catatan pemeriksaan berhasil disimpan sebagai draf.']);
    }

    public function edit(RekamMedis $rekamMedis): View
    {
        Gate::authorize('update', $rekamMedis);

        abort_if($rekamMedis->status_rekam_medis?->value === 'difinalisasi', 403, 'Catatan yang sudah difinalisasi tidak dapat diubah.');

        $rekamMedis->load(['kunjungan.pasien', 'diagnosa', 'tindakanMedis']);

        return view('rekam-medis.edit', ['rekamMedis' => $rekamMedis]);
    }

    public function update(Request $request, RekamMedis $rekamMedis): RedirectResponse
    {
        Gate::authorize('update', $rekamMedis);

        abort_if($rekamMedis->status_rekam_medis?->value === 'difinalisasi', 403);

        $data = $this->validasi($request);

        DB::transaction(function () use ($rekamMedis, $data): void {
            $rekamMedis->update([
                'hasil_pemeriksaan' => $data['hasil_pemeriksaan'] ?? null,
                'tanda_vital' => $data['tanda_vital'] ?? null,
                'catatan_klinis' => $data['catatan_klinis'] ?? null,
            ]);

            $rekamMedis->diagnosa()->delete();
            $rekamMedis->tindakanMedis()->delete();

            $this->simpanDiagnosa($rekamMedis, $data['diagnosa'] ?? []);
            $this->simpanTindakan($rekamMedis, $data['tindakan'] ?? []);
        });

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Perbarui, 'rekam_medis', $rekamMedis->id_rekam_medis);

        $tujuan = $request->string('konteks')->toString() === 'pemeriksaan'
            ? route('pemeriksaan.show', $rekamMedis->id_kunjungan)
            : route('rekam-medis.show', $rekamMedis);

        return redirect($tujuan)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Catatan diperbarui', 'pesan' => 'Perubahan catatan pemeriksaan berhasil disimpan.']);
    }

    public function finalisasi(Request $request, RekamMedis $rekamMedis): RedirectResponse
    {
        Gate::authorize('finalisasi', $rekamMedis);

        $rekamMedis->forceFill([
            'status_rekam_medis' => StatusRekamMedis::Difinalisasi->value,
            'difinalisasi_pada' => now(),
        ])->save();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Finalisasi, 'rekam_medis', $rekamMedis->id_rekam_medis);

        return back()->with('toast', ['jenis' => 'sukses', 'judul' => 'Catatan difinalisasi', 'pesan' => 'Catatan medis telah difinalisasi dan tidak dapat diubah.']);
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'hasil_pemeriksaan' => ['nullable', 'string'],
            'catatan_klinis' => ['nullable', 'string'],
            'tanda_vital.tekanan_darah' => ['nullable', 'string', 'max:20'],
            'tanda_vital.suhu' => ['nullable', 'numeric'],
            'tanda_vital.nadi' => ['nullable', 'integer'],
            'tanda_vital.pernapasan' => ['nullable', 'integer'],
            'tanda_vital.berat_badan' => ['nullable', 'numeric'],
            'tanda_vital.tinggi_badan' => ['nullable', 'numeric'],
            'diagnosa' => ['nullable', 'array'],
            'diagnosa.*.nama_diagnosa' => ['required_with:diagnosa', 'string', 'max:255'],
            'diagnosa.*.jenis_diagnosa' => ['nullable', 'in:utama,tambahan'],
            'diagnosa.*.kode_diagnosa' => ['nullable', 'string', 'max:20'],
            'diagnosa.*.catatan_diagnosa' => ['nullable', 'string'],
            'tindakan' => ['nullable', 'array'],
            'tindakan.*.nama_tindakan' => ['required_with:tindakan', 'string', 'max:255'],
            'tindakan.*.catatan_tindakan' => ['nullable', 'string'],
        ]);
    }

    private function simpanDiagnosa(RekamMedis $rekamMedis, array $daftar): void
    {
        foreach ($daftar as $item) {
            $rekamMedis->diagnosa()->create([
                'nama_diagnosa' => $item['nama_diagnosa'],
                'jenis_diagnosa' => $item['jenis_diagnosa'] ?? 'utama',
                'kode_diagnosa' => $item['kode_diagnosa'] ?? null,
                'catatan_diagnosa' => $item['catatan_diagnosa'] ?? null,
            ]);
        }
    }

    private function simpanTindakan(RekamMedis $rekamMedis, array $daftar): void
    {
        foreach ($daftar as $item) {
            $rekamMedis->tindakanMedis()->create([
                'nama_tindakan' => $item['nama_tindakan'],
                'catatan_tindakan' => $item['catatan_tindakan'] ?? null,
            ]);
        }
    }

    private function dokterAktif(Request $request): ?int
    {
        return Dokter::query()->where('id_pengguna', $request->user()->id_pengguna)->value('id_dokter');
    }
}
