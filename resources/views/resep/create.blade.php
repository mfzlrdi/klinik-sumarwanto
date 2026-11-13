@extends('layouts.app')

@section('title', 'Buat Resep')

@section('content')
    <x-page-header judul="Buat Resep" deskripsi="Buat resep obat untuk {{ $kunjungan->pasien->nama_lengkap }} ({{ $kunjungan->kodeAntrean() }})." />

    <form method="POST" action="{{ route('resep.store', $kunjungan) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf
        @php $resep = null; @endphp
        @include('resep._form')

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-violet-700">
                <i data-lucide="save" class="size-4"></i>Simpan sebagai Draf
            </button>
        </div>
    </form>
@endsection
