@extends('layouts.app')

@section('title', 'Edit Obat')

@section('content')
    <x-page-header judul="Edit Obat" deskripsi="Perbarui data obat {{ $obat->nama_obat }}." />

    <form method="POST" action="{{ route('obat.update', $obat) }}" class="max-w-xl rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="nama_obat" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nama obat <span class="text-rose-500">*</span></label>
                <input id="nama_obat" name="nama_obat" type="text" value="{{ old('nama_obat', $obat->nama_obat) }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @error('nama_obat')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="kekuatan_obat" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Kekuatan</label>
                <input id="kekuatan_obat" name="kekuatan_obat" type="text" value="{{ old('kekuatan_obat', $obat->kekuatan_obat) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label for="bentuk_sediaan" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Bentuk sediaan</label>
                <input id="bentuk_sediaan" name="bentuk_sediaan" type="text" value="{{ old('bentuk_sediaan', $obat->bentuk_sediaan) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div>
                <label for="satuan_obat" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Satuan</label>
                <input id="satuan_obat" name="satuan_obat" type="text" value="{{ old('satuan_obat', $obat->satuan_obat) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <label for="status_aktif" class="inline-flex cursor-pointer items-center gap-2.5 text-[12.5px] text-slate-600 sm:col-span-2">
                <input id="status_aktif" name="status_aktif" type="checkbox" value="1" @checked(old('status_aktif', $obat->status_aktif)) class="size-4 rounded border-slate-300 accent-teal-600">
                Obat aktif dan dapat digunakan
            </label>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('obat.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep"><i data-lucide="check" class="size-4"></i>Simpan</button>
        </div>
    </form>
@endsection
