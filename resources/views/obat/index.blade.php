@extends('layouts.app')

@section('title', 'Katalog Obat')

@section('content')
    <x-page-header judul="Katalog Obat" deskripsi="Daftar obat yang tersedia untuk digunakan dalam resep.">
        @can('create', \App\Models\Obat::class)
            <a href="{{ route('obat.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-teal-deep">
                <i data-lucide="plus" class="size-4"></i>Tambah Obat
            </a>
        @endcan
    </x-page-header>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 p-4">
            <form method="GET" action="{{ route('obat.index') }}" class="relative w-full sm:max-w-xs">
                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400"></i>
                <input type="search" name="cari" value="{{ $cari }}" placeholder="Cari obat…" class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-[12.5px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </form>
            <span class="text-[11.5px] text-slate-400">{{ $obat->total() }} obat</span>
        </div>

        @if ($obat->isEmpty())
            <x-empty-state icon="pill-bottle" title="Belum ada obat" description="Tambahkan obat ke katalog untuk digunakan dalam resep." />
        @else
            <div class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($obat as $item)
                    <article class="rounded-xl border border-slate-200 p-4 transition-all hover:-translate-y-0.5 hover:border-teal-200 hover:shadow-[0_10px_24px_rgba(15,40,35,.08)]">
                        <div class="flex items-start justify-between">
                            <span class="flex size-9 items-center justify-center rounded-lg bg-teal-50 text-teal-600"><i data-lucide="pill-bottle" class="size-4"></i></span>
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $item->status_aktif ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <h3 class="mt-3 text-[13px] font-semibold text-slate-800">{{ $item->nama_obat }}</h3>
                        <p class="mt-0.5 text-[11.5px] text-slate-400">{{ collect([$item->kekuatan_obat, $item->bentuk_sediaan])->filter()->implode(' · ') ?: '—' }}</p>
                        <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">
                            <span class="text-[11px] text-slate-400">Satuan: {{ $item->satuan_obat }}</span>
                            @can('update', $item)
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('obat.edit', $item) }}" class="flex size-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-amber-50 hover:text-amber-600" title="Edit"><i data-lucide="pencil" class="size-3.5"></i></a>
                                    <form method="POST" action="{{ route('obat.destroy', $item) }}" data-confirm data-confirm-title="Hapus obat" data-confirm-message="Obat {{ $item->nama_obat }} akan dihapus dari katalog." data-confirm-label="Ya, hapus">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex size-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600" title="Hapus"><i data-lucide="trash-2" class="size-3.5"></i></button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $obat->links() }}</div>
        @endif
    </section>
@endsection
