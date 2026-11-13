<?php

namespace App\Http\Controllers;

use App\Enums\JenisAktivitas;
use App\Models\CatatanAktivitas;
use App\Models\PengaturanSistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->memilikiPeran('administrator'), 403);

        $pengaturan = collect(PengaturanSistem::bawaan())
            ->mapWithKeys(fn (string $bawaan, string $nama): array => [$nama => PengaturanSistem::nilai($nama, $bawaan)]);

        return view('pengaturan.index', ['pengaturan' => $pengaturan]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->memilikiPeran('administrator'), 403);

        $data = $request->validate([
            'nama_praktik' => ['required', 'string', 'max:120'],
            'alamat_praktik' => ['nullable', 'string', 'max:255'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'jam_operasional' => ['nullable', 'string', 'max:120'],
            'logo_ikon' => ['required', 'in:activity,stethoscope,heart-pulse,hospital,microscope,shield-plus'],
            'logo_nama' => ['required', 'string', 'max:60'],
            'logo_subjudul' => ['nullable', 'string', 'max:60'],
            'tagline' => ['nullable', 'string', 'max:160'],
            'deskripsi_singkat' => ['nullable', 'string', 'max:400'],
        ], [], [
            'nama_praktik' => 'nama praktik',
            'logo_ikon' => 'ikon logo',
            'logo_nama' => 'nama logo',
        ]);

        foreach ($data as $nama => $nilai) {
            PengaturanSistem::updateOrCreate(
                ['nama_pengaturan' => $nama],
                ['nilai_pengaturan' => $nilai, 'id_pengguna_pengubah' => $request->user()->id_pengguna],
            );
        }

        CatatanAktivitas::catat($request->user(), JenisAktivitas::Perbarui, 'pengaturan_sistem', 0);

        return back()->with('toast', ['jenis' => 'sukses', 'judul' => 'Pengaturan tersimpan', 'pesan' => 'Perubahan konfigurasi sistem berhasil disimpan.']);
    }
}
