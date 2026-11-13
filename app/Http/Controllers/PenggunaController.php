<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Models\CatatanAktivitas;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Pengguna::class);

        $daftarPengguna = Pengguna::query()->with('peran')->latest('dibuat_pada')->paginate(12);

        return view('pengguna.index', ['daftarPengguna' => $daftarPengguna]);
    }

    public function create(): View
    {
        Gate::authorize('create', Pengguna::class);

        return view('pengguna.create', ['peran' => Peran::query()->orderBy('id_peran')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Pengguna::class);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'alamat_email' => ['required', 'email', 'max:190', 'unique:pengguna,alamat_email'],
            'kata_sandi' => ['required', 'string', 'min:8'],
            'status_aktif' => ['sometimes', 'boolean'],
            'peran' => ['required', 'array', 'min:1'],
            'peran.*' => ['required', 'integer', 'exists:peran,id_peran'],
        ], [], [
            'nama_lengkap' => 'nama lengkap',
            'alamat_email' => 'alamat email',
            'kata_sandi' => 'kata sandi',
            'peran' => 'peran',
        ]);

        $pengguna = Pengguna::query()->create([
            'nama_lengkap' => $data['nama_lengkap'],
            'alamat_email' => $data['alamat_email'],
            'kata_sandi' => Hash::make($data['kata_sandi']),
            'status_aktif' => $request->boolean('status_aktif', true),
        ]);
        $pengguna->peran()->sync($data['peran']);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'pengguna', $pengguna->id_pengguna, ['alamat_email' => $pengguna->alamat_email]);

        return redirect()->route('pengguna.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Akun dibuat', 'pesan' => 'Akun pengguna baru berhasil ditambahkan.']);
    }

    public function edit(Pengguna $pengguna): View
    {
        Gate::authorize('update', $pengguna);

        return view('pengguna.edit', [
            'akun' => $pengguna->loadMissing('peran'),
            'peran' => Peran::query()->orderBy('id_peran')->get(),
        ]);
    }

    public function update(Request $request, Pengguna $pengguna): RedirectResponse
    {
        Gate::authorize('update', $pengguna);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'alamat_email' => ['required', 'email', 'max:190', 'unique:pengguna,alamat_email,'.$pengguna->id_pengguna.',id_pengguna'],
            'kata_sandi' => ['nullable', 'string', 'min:8'],
            'status_aktif' => ['sometimes', 'boolean'],
            'peran' => ['required', 'array', 'min:1'],
            'peran.*' => ['required', 'integer', 'exists:peran,id_peran'],
        ], [], [
            'nama_lengkap' => 'nama lengkap',
            'alamat_email' => 'alamat email',
            'kata_sandi' => 'kata sandi',
            'peran' => 'peran',
        ]);

        $pengguna->update([
            'nama_lengkap' => $data['nama_lengkap'],
            'alamat_email' => $data['alamat_email'],
            'status_aktif' => $request->boolean('status_aktif', true),
        ]);

        if ($request->filled('kata_sandi')) {
            $pengguna->update(['kata_sandi' => Hash::make($data['kata_sandi'])]);
        }

        $pengguna->peran()->sync($data['peran']);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Perbarui, 'pengguna', $pengguna->id_pengguna);

        return redirect()->route('pengguna.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Akun diperbarui', 'pesan' => 'Perubahan data akun berhasil disimpan.']);
    }

    public function destroy(Request $request, Pengguna $pengguna): RedirectResponse
    {
        Gate::authorize('delete', $pengguna);

        $pengguna->delete();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Hapus, 'pengguna', $pengguna->id_pengguna);

        return redirect()->route('pengguna.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Akun dihapus', 'pesan' => 'Akun pengguna telah dihapus.']);
    }
}
