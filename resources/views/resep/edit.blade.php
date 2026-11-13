@extends('layouts.app')

@section('title', 'Edit Resep')

@section('content')
    <x-page-header judul="Edit Resep" deskripsi="Perbarui resep draf {{ $resep->nomor_resep }}." />

    <form method="POST" action="{{ route('resep.update', $resep) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        @csrf
        @method('PUT')
        @include('resep._form')

        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
            <a href="{{ route('resep.show', $resep) }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition-colors hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-violet-700">
                <i data-lucide="save" class="size-4"></i>Simpan Perubahan
            </button>
        </div>
    </form>
@endsection
