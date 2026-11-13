<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Models\CatatanAktivitas;
use App\Models\Pasien;
use App\Models\RekamMedis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Pasien::class);

        $cari = $request->string('cari')->trim()->toString();

        $pasien = Pasien::query()
            ->when($cari !== '', function ($query) use ($cari): void {
                $query->where(function ($q) use ($cari): void {
                    $q->where('nama_lengkap', 'like', "%{$cari}%")
                        ->orWhere('nomor_rekam_medis', 'like', "%{$cari}%")
                        ->orWhere('nomor_identitas', 'like', "%{$cari}%")
                        ->orWhere('nomor_telepon', 'like', "%{$cari}%");
                });
            })
            ->withCount('kunjungan')
            ->latest('diperbarui_pada')
            ->paginate(10)
            ->withQueryString();

        return view('pasien.index', ['pasien' => $pasien, 'cari' => $cari]);
    }

    public function cari(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Pasien::class);

        $cari = $request->string('q')->trim()->toString();

        $hasil = Pasien::query()
            ->when($cari !== '', function ($query) use ($cari): void {
                $query->where(function ($q) use ($cari): void {
                    $q->where('nama_lengkap', 'like', "%{$cari}%")
                        ->orWhere('nomor_rekam_medis', 'like', "%{$cari}%")
                        ->orWhere('nomor_identitas', 'like', "%{$cari}%");
                });
            })
            ->orderBy('nama_lengkap')
            ->limit(8)
            ->get()
            ->map(fn (Pasien $p): array => [
                'id' => $p->id_pasien,
                'nama' => $p->nama_lengkap,
                'nomor_rekam_medis' => $p->nomor_rekam_medis,
                'jenis_kelamin' => $p->jenis_kelamin?->label(),
                'tanggal_lahir' => $p->tanggal_lahir?->format('d/m/Y'),
            ]);

        return response()->json(['hasil' => $hasil]);
    }

    public function create(): View
    {
        Gate::authorize('create', Pasien::class);

        return view('pasien.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Pasien::class);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'nomor_identitas' => ['nullable', 'string', 'max:40'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', 'in:laki_laki,perempuan'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'alamat_email' => ['nullable', 'email', 'max:190'],
            'catatan_administratif' => ['nullable', 'string'],
        ], [], [
            'nama_lengkap' => 'nama lengkap',
            'nomor_identitas' => 'nomor identitas',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'alamat_email' => 'alamat email',
        ]);

        $data['nomor_rekam_medis'] = $this->nomorRekamMedisBaru();

        $pasien = Pasien::query()->create($data);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'pasien', $pasien->id_pasien, ['nama_lengkap' => $pasien->nama_lengkap]);

        return redirect()->route('pasien.show', $pasien)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Pasien ditambahkan', 'pesan' => 'Data pasien berhasil disimpan.']);
    }

    public function show(Pasien $pasien): View
    {
        Gate::authorize('view', $pasien);

        $pasien->loadCount('kunjungan');
        $riwayat = $pasien->kunjungan()->with('dokter.pengguna')->latest('tanggal_kunjungan')->latest('nomor_antrean')->limit(10)->get();

        return view('pasien.show', ['pasien' => $pasien, 'riwayat' => $riwayat]);
    }

    public function edit(Pasien $pasien): View
    {
        Gate::authorize('update', $pasien);

        return view('pasien.edit', ['pasien' => $pasien]);
    }

    public function riwayatMedis(Request $request, Pasien $pasien): View
    {
        abort_unless($request->user()->memilikiPeran('dokter', 'administrator'), 403);

        $riwayat = RekamMedis::query()
            ->with(['kunjungan.dokter.pengguna', 'diagnosa', 'tindakanMedis', 'resep'])
            ->whereHas('kunjungan', fn ($q) => $q->where('id_pasien', $pasien->id_pasien))
            ->latest('dibuat_pada')
            ->paginate(10);

        return view('pasien.riwayat-medis', ['pasien' => $pasien, 'riwayat' => $riwayat]);
    }

    public function update(Request $request, Pasien $pasien): RedirectResponse
    {
        Gate::authorize('update', $pasien);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'nomor_identitas' => ['nullable', 'string', 'max:40'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', 'in:laki_laki,perempuan'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'alamat_email' => ['nullable', 'email', 'max:190'],
            'catatan_administratif' => ['nullable', 'string'],
        ], [], [
            'nama_lengkap' => 'nama lengkap',
            'nomor_identitas' => 'nomor identitas',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'alamat_email' => 'alamat email',
        ]);

        $pasien->update($data);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Perbarui, 'pasien', $pasien->id_pasien);

        return redirect()->route('pasien.show', $pasien)
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Data diperbarui', 'pesan' => 'Perubahan data pasien berhasil disimpan.']);
    }

    public function destroy(Request $request, Pasien $pasien): RedirectResponse
    {
        Gate::authorize('delete', $pasien);

        $pasien->delete();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Hapus, 'pasien', $pasien->id_pasien);

        return redirect()->route('pasien.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Pasien dihapus', 'pesan' => 'Data pasien telah dihapus dari sistem.']);
    }

    private function nomorRekamMedisBaru(): string
    {
        return Cache::lock('pasien:nomor', 10)->get(function (): string {
            $tahun = now()->format('Y');
            $terakhir = Pasien::query()->where('nomor_rekam_medis', 'like', 'RM-'.$tahun.'%')->count();

            return 'RM-'.$tahun.str_pad((string) ($terakhir + 1), 5, '0', STR_PAD_LEFT);
        });
    }
}
