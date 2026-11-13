@extends('layouts.app')

@section('title', 'Tambah Pasien')

@section('content')
    <x-page-header judul="Tambah Pasien" deskripsi="Daftarkan pasien baru ke dalam sistem." />

    <form method="POST" action="{{ route('pasien.store') }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf
        @include('pasien._form')

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('pasien.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                <i data-lucide="check" class="size-4"></i>Simpan Pasien
            </button>
        </div>
    </form>
@endsection
