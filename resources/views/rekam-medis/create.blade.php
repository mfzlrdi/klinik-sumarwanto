@extends('layouts.app')

@section('title', 'Catatan Pemeriksaan')

@section('content')
    <x-page-header judul="Catatan Pemeriksaan" deskripsi="Buat catatan medis untuk {{ $kunjungan->pasien->nama_lengkap }} ({{ $kunjungan->kodeAntrean() }})." />

    <form method="POST" action="{{ route('rekam-medis.store', $kunjungan) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf
        @php $rekamMedis = null; @endphp
        @include('rekam-medis._form')

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                <i data-lucide="save" class="size-4"></i>Simpan sebagai Draf
            </button>
        </div>
    </form>
@endsection
