<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Models\CatatanAktivitas;
use App\Models\Obat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Obat::class);

        $cari = $request->string('cari')->trim()->toString();

        $obat = Obat::query()
            ->when($cari !== '', fn ($q) => $q->where('nama_obat', 'like', "%{$cari}%"))
            ->orderBy('nama_obat')
            ->paginate(12)
            ->withQueryString();

        return view('obat.index', ['obat' => $obat, 'cari' => $cari]);
    }

    public function create(): View
    {
        Gate::authorize('create', Obat::class);

        return view('obat.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Obat::class);

        $data = $request->validate([
            'nama_obat' => ['required', 'string', 'max:190'],
            'kekuatan_obat' => ['nullable', 'string', 'max:60'],
            'bentuk_sediaan' => ['nullable', 'string', 'max:60'],
            'satuan_obat' => ['nullable', 'string', 'max:30'],
            'status_aktif' => ['sometimes', 'boolean'],
        ], [], ['nama_obat' => 'nama obat', 'kekuatan_obat' => 'kekuatan obat', 'bentuk_sediaan' => 'bentuk sediaan', 'satuan_obat' => 'satuan obat']);

        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $obat = Obat::query()->create($data);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Buat, 'obat', $obat->id_obat, ['nama_obat' => $obat->nama_obat]);

        return redirect()->route('obat.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Obat ditambahkan', 'pesan' => 'Obat baru berhasil ditambahkan ke katalog.']);
    }

    public function edit(Obat $obat): View
    {
        Gate::authorize('update', $obat);

        return view('obat.edit', ['obat' => $obat]);
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        Gate::authorize('update', $obat);

        $data = $request->validate([
            'nama_obat' => ['required', 'string', 'max:190'],
            'kekuatan_obat' => ['nullable', 'string', 'max:60'],
            'bentuk_sediaan' => ['nullable', 'string', 'max:60'],
            'satuan_obat' => ['nullable', 'string', 'max:30'],
            'status_aktif' => ['sometimes', 'boolean'],
        ], [], ['nama_obat' => 'nama obat', 'kekuatan_obat' => 'kekuatan obat', 'bentuk_sediaan' => 'bentuk sediaan', 'satuan_obat' => 'satuan obat']);

        $data['status_aktif'] = $request->boolean('status_aktif', true);

        $obat->update($data);

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Perbarui, 'obat', $obat->id_obat);

        return redirect()->route('obat.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Obat diperbarui', 'pesan' => 'Perubahan data obat berhasil disimpan.']);
    }

    public function destroy(Request $request, Obat $obat): RedirectResponse
    {
        Gate::authorize('delete', $obat);

        $obat->delete();

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Hapus, 'obat', $obat->id_obat);

        return redirect()->route('obat.index')
            ->with('toast', ['jenis' => 'sukses', 'judul' => 'Obat dihapus', 'pesan' => 'Obat telah dihapus dari katalog.']);
    }
}
