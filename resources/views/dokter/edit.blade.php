@extends('layouts.app')

@section('title', 'Edit Dokter')

@section('content')
    <x-page-header judul="Edit Dokter" deskripsi="Perbarui profil dr. {{ $dokter->pengguna?->nama_lengkap }}." />

    <form method="POST" action="{{ route('dokter.update', $dokter) }}" class="max-w-xl rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf
        @method('PUT')
        <div class="space-y-5">
            <div>
                <label for="id_pengguna" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Pengguna (akun dokter) <span class="text-rose-500">*</span></label>
                <select id="id_pengguna" name="id_pengguna" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    @foreach ($daftarPengguna as $p)
                        <option value="{{ $p->id_pengguna }}" @selected(old('id_pengguna', $dokter->id_pengguna) == $p->id_pengguna)>{{ $p->nama_lengkap }} ({{ $p->alamat_email }})</option>
                    @endforeach
                </select>
                @error('id_pengguna')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="nomor_sip" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nomor SIP</label>
                <input id="nomor_sip" name="nomor_sip" type="text" value="{{ old('nomor_sip', $dokter->nomor_sip) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label for="nomor_telepon_profesional" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Telepon profesional</label>
                <input id="nomor_telepon_profesional" name="nomor_telepon_profesional" type="tel" value="{{ old('nomor_telepon_profesional', $dokter->nomor_telepon_profesional) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <label for="status_aktif" class="inline-flex cursor-pointer items-center gap-2.5 text-[12.5px] text-slate-600">
                <input id="status_aktif" name="status_aktif" type="checkbox" value="1" @checked(old('status_aktif', $dokter->status_aktif)) class="size-4 rounded border-slate-300 accent-teal-600">
                Dokter aktif
            </label>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('dokter.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep"><i data-lucide="check" class="size-4"></i>Simpan</button>
        </div>
    </form>
@endsection
