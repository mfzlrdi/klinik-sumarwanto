@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <x-page-header judul="Tambah Pengguna" deskripsi="Buat akun pengguna baru dan tetapkan peran." />

    <form method="POST" action="{{ route('pengguna.store') }}" class="max-w-xl rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf
        <div class="space-y-5">
            <div>
                <label for="nama_lengkap" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nama lengkap <span class="text-rose-500">*</span></label>
                <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @error('nama_lengkap')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="alamat_email" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Alamat email <span class="text-rose-500">*</span></label>
                <input id="alamat_email" name="alamat_email" type="email" value="{{ old('alamat_email') }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @error('alamat_email')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kata_sandi" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Kata sandi <span class="text-rose-500">*</span></label>
                <div data-password-toggle class="relative">
                    <input id="kata_sandi" name="kata_sandi" type="password" required placeholder="Minimal 8 karakter" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 pr-11 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    <button type="button" data-password-toggle="kata_sandi" aria-label="Tampilkan kata sandi" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-lg text-slate-400 hover:text-teal-700"><i data-lucide="eye" class="size-4"></i></button>
                </div>
                @error('kata_sandi')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <p class="mb-2 block text-[12px] font-semibold text-slate-700">Peran <span class="text-rose-500">*</span></p>
                <div class="space-y-2">
                    @foreach ($peran as $p)
                        <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 px-3.5 py-2.5 transition-colors hover:border-teal-300 hover:bg-teal-50/40">
                            <input type="checkbox" name="peran[]" value="{{ $p->id_peran }}" @checked(in_array($p->id_peran, old('peran', []))) class="size-4 rounded border-slate-300 accent-teal-600">
                            <span class="flex-1">
                                <span class="block text-[12.5px] font-semibold text-slate-700">{{ ucfirst($p->nama_peran) }}</span>
                                <span class="mt-0.5 block text-[11px] text-slate-400">{{ $p->keterangan_peran }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('peran')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <label for="status_aktif" class="inline-flex cursor-pointer items-center gap-2.5 text-[12.5px] text-slate-600">
                <input id="status_aktif" name="status_aktif" type="checkbox" value="1" checked class="size-4 rounded border-slate-300 accent-teal-600">
                Akun aktif
            </label>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('pengguna.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep"><i data-lucide="check" class="size-4"></i>Simpan</button>
        </div>
    </form>
@endsection
