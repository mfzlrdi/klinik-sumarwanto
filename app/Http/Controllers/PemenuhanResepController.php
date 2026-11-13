<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Enums\JenisNotifikasi;
use App\Enums\StatusPemenuhan;
use App\Models\CatatanAktivitas;
use App\Models\PemenuhanResep;
use App\Models\Resep;
use App\Services\LayananNotifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PemenuhanResepController extends Controller
{
    public function __construct(private LayananNotifikasi $notifikasi) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', PemenuhanResep::class);

        $status = $request->string('status')->toString();

        $pemenuhan = PemenuhanResep::query()
            ->with(['resep.kunjungan.pasien', 'resep.rincianResep', 'penanggungJawab'])
            ->whereHas('resep', fn ($q) => $q->where('status_resep', 'difinalisasi'))
            ->when($status !== '', fn ($q) => $q->where('status_pemenuhan', $status))
            ->latest('diperbarui_pada')
            ->paginate(15)
            ->withQueryString();

        return view('pemenuhan.index', [
            'pemenuhan' => $pemenuhan,
            'status' => $status,
            'statusPemenuhan' => StatusPemenuhan::cases(),
        ]);
    }

    public function show(PemenuhanResep $pemenuhanResep): View
    {
        Gate::authorize('view', $pemenuhanResep);

        $pemenuhanResep->load([
            'resep.kunjungan.pasien',
            'resep.rincianResep.penyerahan.penyerah',
            'penanggungJawab',
        ]);

        return view('pemenuhan.show', ['pemenuhanResep' => $pemenuhanResep]);
    }

    public function perbaruiStatus(Request $request, PemenuhanResep $pemenuhanResep): RedirectResponse
    {
        Gate::authorize('proses', $pemenuhanResep);

        $target = StatusPemenuhan::tryFrom($request->validate([
            'status_pemenuhan' => ['required', 'string', 'in:menunggu,sedang_diproses,siap_diserahkan,dirujuk_ke_apotek_luar,terhambat'],
        ])['status_pemenuhan']);

        abort_unless($this->bolehTransisi($pemenuhanResep->status_pemenuhan, $target), 422, 'Transisi status tidak sah.');

        $pemenuhanResep->forceFill([
            'status_pemenuhan' => $target->value,
            'id_pengguna_penanggung_jawab' => $request->user()->id_pengguna,
            'mulai_diproses_pada' => $target === StatusPemenuhan::SedangDiproses ? now() : $pemenuhanResep->mulai_diproses_pada,
            'selesai_diproses_pada' => in_array($target, [StatusPemenuhan::SiapDiserahkan, StatusPemenuhan::SelesaiDiserahkan], true) ? now() : $pemenuhanResep->selesai_diproses_pada,
        ])->save();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Status, 'pemenuhan_resep', $pemenuhanResep->id_pemenuhan_resep, ['ke' => $target->value]);

        return back()->with('toast', ['jenis' => 'sukses', 'judul' => 'Status diperbarui', 'pesan' => 'Status tindak lanjut berubah menjadi '.$target->label().'.']);
    }

    public function serahkan(Request $request, PemenuhanResep $pemenuhanResep): RedirectResponse
    {
        Gate::authorize('serahkan', $pemenuhanResep);

        $data = $request->validate([
            'jumlah' => ['required', 'array'],
            'jumlah.*' => ['required', 'integer', 'min:0'],
            'catatan_penyerahan' => ['nullable', 'string', 'max:500'],
        ]);

        $resep = $pemenuhanResep->resep()->with('rincianResep')->first();

        DB::transaction(function () use ($pemenuhanResep, $resep, $data, $request): void {
            foreach ($resep->rincianResep as $rincian) {
                $jumlah = (int) ($data['jumlah'][$rincian->id_rincian_resep] ?? 0);
                if ($jumlah <= 0) {
                    continue;
                }

                $pemenuhanResep->penyerahan()->create([
                    'id_rincian_resep' => $rincian->id_rincian_resep,
                    'jumlah_diserahkan' => $jumlah,
                    'id_pengguna_penyerah' => $request->user()->id_pengguna,
                    'diserahkan_pada' => now(),
                    'catatan_penyerahan' => $data['catatan_penyerahan'] ?? null,
                ]);
            }

            $pemenuhanResep->forceFill([
                'status_pemenuhan' => $this->statusPenyerahan($resep),
                'id_pengguna_penanggung_jawab' => $request->user()->id_pengguna,
                'selesai_diproses_pada' => now(),
            ])->save();
        });

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Status, 'pemenuhan_resep', $pemenuhanResep->id_pemenuhan_resep, ['ke' => 'penyerahan']);

        $this->beriTahuDokter($pemenuhanResep);

        return redirect()->route('pemenuhan.show', $pemenuhanResep)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Penyerahan dicatat', 'pesan' => 'Catatan penyerahan obat berhasil disimpan.']);
    }

    private function statusPenyerahan(Resep $resep): string
    {
        foreach ($resep->rincianResep as $rincian) {
            if ($rincian->totalTerserahkan() < $rincian->jumlah_diresepkan) {
                return StatusPemenuhan::DiserahkanSebagian->value;
            }
        }

        return StatusPemenuhan::SelesaiDiserahkan->value;
    }

    private function bolehTransisi(?StatusPemenuhan $dari, StatusPemenuhan $ke): bool
    {
        return match ($ke) {
            StatusPemenuhan::Menunggu => in_array($dari, [StatusPemenuhan::Terhambat], true),
            StatusPemenuhan::SedangDiproses => in_array($dari, [StatusPemenuhan::Menunggu, StatusPemenuhan::Terhambat], true),
            StatusPemenuhan::SiapDiserahkan => $dari === StatusPemenuhan::SedangDiproses,
            StatusPemenuhan::DirujukKeApotekLuar, StatusPemenuhan::Terhambat => in_array($dari, [StatusPemenuhan::Menunggu, StatusPemenuhan::SedangDiproses, StatusPemenuhan::SiapDiserahkan], true),
            default => false,
        };
    }

    private function beriTahuDokter(PemenuhanResep $pemenuhanResep): void
    {
        $resep = $pemenuhanResep->resep()->with('dokter.pengguna')->first();
        $penerima = $resep->dokter?->pengguna;

        if ($penerima !== null) {
            $this->notifikasi->kirim(
                $penerima,
                JenisNotifikasi::PenyerahanObat,
                'Penyerahan obat tercatat',
                'Obat untuk resep '.$resep->nomor_resep.' telah dicatat penyerahannya.',
                ['id_resep' => $resep->id_resep],
            );
        }
    }
}
