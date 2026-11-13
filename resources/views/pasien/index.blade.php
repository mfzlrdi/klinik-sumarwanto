@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
    <x-page-header judul="Data Pasien" deskripsi="Kelola data pasien dan cari berdasarkan nama, nomor rekam medis, atau identitas.">
        @can('create', \App\Models\Pasien::class)
            <a href="{{ route('pasien.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-teal-deep">
                <i data-lucide="plus" class="size-4"></i>Pasien Baru
            </a>
        @endcan
    </x-page-header>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('pasien.index') }}" class="relative w-full sm:max-w-xs">
                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400"></i>
                <input type="search" name="cari" value="{{ $cari }}" placeholder="Cari pasien…" class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-[12.5px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </form>
            <span class="text-[11.5px] text-slate-400">{{ $pasien->total() }} pasien</span>
        </div>

        @if ($pasien->isEmpty())
            <x-empty-state icon="users" title="Tidak ada data pasien" description="{{ $cari ? 'Tidak ada pasien yang cocok dengan pencarian Anda.' : 'Mulai tambahkan pasien pertama Anda.' }}">
                @if ($cari)
                    <a href="{{ route('pasien.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-[11.5px] font-semibold text-slate-600 hover:border-teal-300 hover:text-teal-700"><i data-lucide="x" class="size-3.5"></i>Hapus filter</a>
                @endif
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-5 py-3">No. RM</th>
                            <th class="px-5 py-3">Nama Lengkap</th>
                            <th class="px-5 py-3">Jenis Kelamin</th>
                            <th class="px-5 py-3">Usia</th>
                            <th class="px-5 py-3">Kunjungan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($pasien as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-5 py-3.5"><span class="rounded-md bg-teal-50 px-2 py-1 font-mono text-[11px] font-semibold text-teal-700">{{ $item->nomor_rekam_medis }}</span></td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('pasien.show', $item) }}" class="font-semibold text-slate-700 hover:text-teal-700 hover:underline">{{ $item->nama_lengkap }}</a>
                                </td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">{{ $item->jenis_kelamin?->label() ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">{{ $item->usia() !== null ? $item->usia().' thn' : '-' }}</td>
                                <td class="px-5 py-3.5"><span class="text-[12px] font-medium text-slate-600">{{ $item->kunjungan_count }}</span></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('pasien.show', $item) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-teal-50 hover:text-teal-700" title="Lihat detail"><i data-lucide="eye" class="size-4"></i></a>
                                        @can('update', $item)
                                            <a href="{{ route('pasien.edit', $item) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-amber-50 hover:text-amber-600" title="Edit"><i data-lucide="pencil" class="size-4"></i></a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $pasien->links() }}</div>
        @endif
    </section>
@endsection
