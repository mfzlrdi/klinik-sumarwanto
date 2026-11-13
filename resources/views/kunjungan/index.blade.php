@extends('layouts.app')

@section('title', 'Antrean & Kunjungan')

@section('content')
    <x-page-header judul="Antrean & Kunjungan" deskripsi="Pantau daftar antrean pasien berdasarkan tanggal dan status.">
        @can('create', \App\Models\Kunjungan::class)
            <a href="{{ route('kunjungan.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-teal-deep">
                <i data-lucide="user-plus" class="size-4"></i>Daftarkan Kunjungan
            </a>
        @endcan
    </x-page-header>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <form method="GET" action="{{ route('kunjungan.index') }}" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="tanggal" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" value="{{ $tanggal }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 sm:w-auto">
            </div>
            <div class="flex-1">
                <label for="status" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 sm:w-auto">
                    <option value="">Semua status</option>
                    @foreach ($statusKunjungan as $s)
                        <option value="{{ $s->value }}" @selected($status === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                <i data-lucide="filter" class="size-4"></i>Terapkan
            </button>
        </form>

        @if ($kunjungan->isEmpty())
            <x-empty-state icon="clipboard-list" title="Tidak ada kunjungan" description="Belum ada kunjungan pada tanggal yang dipilih." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-5 py-3">Antrean</th>
                            <th class="px-5 py-3">Pasien</th>
                            <th class="px-5 py-3">Keluhan</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($kunjungan as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-5 py-3.5"><span class="rounded-md bg-teal-50 px-2 py-1 font-mono text-[11px] font-bold text-teal-700">{{ $item->kodeAntrean() }}</span></td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('pasien.show', $item->pasien) }}" class="font-semibold text-slate-700 hover:text-teal-700 hover:underline">{{ $item->pasien->nama_lengkap }}</a>
                                    <span class="mt-0.5 block text-[10.5px] text-slate-400">{{ $item->pasien->nomor_rekam_medis }}</span>
                                </td>
                                <td class="max-w-[220px] px-5 py-3.5 text-[12px] text-slate-500">{{ $item->keluhan_awal ? Str::limit($item->keluhan_awal, 50) : '-' }}</td>
                                <td class="px-5 py-3.5"><x-status-kunjungan :status="$item->status_kunjungan" /></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('kunjungan.show', $item) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-teal-50 hover:text-teal-700" title="Detail"><i data-lucide="eye" class="size-4"></i></a>
                                        <a href="{{ route('kunjungan.cetak', $item) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-teal-50 hover:text-teal-700" title="Cetak antrean"><i data-lucide="printer" class="size-4"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $kunjungan->links() }}</div>
        @endif
    </section>
@endsection
