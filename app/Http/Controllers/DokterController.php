<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Models\CatatanAktivitas;
use App\Models\Dokter;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DokterController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Dokter::class);

        $dokter = Dokter::query()->with('pengguna')->latest('dibuat_pada')->paginate(12);

        return view('dokter.index', ['dokter' => $dokter]);
    }

    public function create(): View
    {
        Gate::authorize('create', Dokter::class);

        $daftarPengguna = Pengguna::query()
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'dokter'))
            ->get();

        return view('dokter.create', ['daftarPengguna' => $daftarPengguna]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Dokter::class);

        $data = $request->validate([
            'id_pengguna' => ['required', 'integer', 'unique:dokter,id_pengguna', 'exists:pengguna,id_pengguna'],
            'nomor_sip' => ['nullable', 'string', 'max:50'],
            'nomor_telepon_profesional' => ['nullable', 'string', 'max:30'],
            'status_aktif' => ['sometimes', 'boolean'],
        ], [], ['id_pengguna' => 'pengguna', 'nomor_sip' => 'nomor SIP']);

        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $dokter = Dokter::query()->create($data);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'dokter', $dokter->id_dokter);

        return redirect()->route('dokter.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Dokter ditambahkan', 'pesan' => 'Profil dokter baru berhasil ditambahkan.']);
    }

    public function edit(Dokter $dokter): View
    {
        Gate::authorize('update', $dokter);

        return view('dokter.edit', [
            'dokter' => $dokter->loadMissing('pengguna'),
            'daftarPengguna' => Pengguna::query()->whereHas('peran', fn ($q) => $q->where('nama_peran', 'dokter'))->get(),
        ]);
    }

    public function update(Request $request, Dokter $dokter): RedirectResponse
    {
        Gate::authorize('update', $dokter);

        $data = $request->validate([
            'id_pengguna' => ['required', 'integer', 'unique:dokter,id_pengguna,'.$dokter->id_dokter.',id_dokter', 'exists:pengguna,id_pengguna'],
            'nomor_sip' => ['nullable', 'string', 'max:50'],
            'nomor_telepon_profesional' => ['nullable', 'string', 'max:30'],
            'status_aktif' => ['sometimes', 'boolean'],
        ], [], ['id_pengguna' => 'pengguna', 'nomor_sip' => 'nomor SIP']);

        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $dokter->update($data);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Perbarui, 'dokter', $dokter->id_dokter);

        return redirect()->route('dokter.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Dokter diperbarui', 'pesan' => 'Perubahan profil dokter berhasil disimpan.']);
    }

    public function destroy(Request $request, Dokter $dokter): RedirectResponse
    {
        Gate::authorize('delete', $dokter);

        $dokter->delete();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Hapus, 'dokter', $dokter->id_dokter);

        return redirect()->route('dokter.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Dokter dihapus', 'pesan' => 'Profil dokter telah dihapus.']);
    }
}
