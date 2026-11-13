@extends('layouts.app')

@section('title', 'Pendaftaran Kunjungan')

@section('content')
    <x-page-header judul="Pendaftaran Kunjungan" deskripsi="Daftarkan pasien dan buat nomor antrean baru." />

    <form method="POST" action="{{ route('kunjungan.store') }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf

        <div>
            <label class="mb-1.5 block text-[12px] font-semibold text-slate-700">Pilih pasien <span class="text-rose-500">*</span></label>
            <div data-pasien-cari data-tambah-url="{{ route('pasien.create') }}" class="relative">
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="search" autocomplete="off" placeholder="Ketik nama atau nomor rekam medis…" value="{{ $pasien?->nama_lengkap }}" class="w-full rounded-lg border border-slate-200 bg-white py-2.5 pl-9 pr-3 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    <input type="hidden" name="id_pasien" value="{{ $pasien?->id_pasien }}">
                </div>
                <div data-pasien-hasil class="absolute left-0 right-0 top-[calc(100%+6px)] z-20 hidden overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_14px_40px_rgba(15,40,35,.16)]"></div>
            </div>

            <div data-pasien-terpilih class="mt-2 {{ $pasien ? '' : 'hidden' }}">
                <div class="flex items-center justify-between rounded-lg border border-teal-200 bg-teal-50/60 px-3.5 py-2.5">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="user-round" class="size-4 text-teal-600"></i>
                        <span class="text-[12.5px]"><span data-nama class="font-semibold text-slate-700">{{ $pasien?->nama_lengkap }}</span> <span data-rm class="ml-1 font-mono text-[11px] text-teal-700">{{ $pasien?->nomor_rekam_medis }}</span></span>
                    </div>
                    <button type="button" data-pasien-batal class="rounded-md p-1 text-slate-400 hover:text-rose-500" aria-label="Ganti pasien"><i data-lucide="x" class="size-4"></i></button>
                </div>
            </div>

            @error('id_pasien')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            <p class="mt-1.5 text-[11px] text-slate-400">Belum terdaftar? <a href="{{ route('pasien.create') }}" class="font-semibold text-teal-700 hover:underline">Daftarkan pasien baru</a>.</p>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="tanggal_kunjungan" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Tanggal kunjungan <span class="text-rose-500">*</span></label>
                <input id="tanggal_kunjungan" name="tanggal_kunjungan" type="date" value="{{ old('tanggal_kunjungan', $tanggal) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @error('tanggal_kunjungan')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-5">
            <label for="keluhan_awal" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Keluhan awal</label>
            <textarea id="keluhan_awal" name="keluhan_awal" rows="3" placeholder="Catat keluhan awal pasien (administratif)…" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('keluhan_awal') }}</textarea>
            @error('keluhan_awal')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('kunjungan.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                <i data-lucide="check" class="size-4"></i>Daftarkan & Cetak Antrean
            </button>
        </div>
    </form>
@endsection
