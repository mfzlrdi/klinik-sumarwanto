@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <x-page-header judul="Laporan" deskripsi="Rekap kunjungan dan pelayanan berdasarkan rentang tanggal.">
        <a href="{{ route('laporan.cetak', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-teal-deep">
            <i data-lucide="printer" class="size-4"></i>Cetak Laporan
        </a>
    </x-page-header>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="dari" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Dari tanggal</label>
                <input id="dari" name="dari" type="date" value="{{ $dari }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div class="flex-1">
                <label for="sampai" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Sampai tanggal</label>
                <input id="sampai" name="sampai" type="date" value="{{ $sampai }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            </div>
            <div class="flex-1">
                <label for="status" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    <option value="">Semua status</option>
                    @foreach (\App\Enums\StatusKunjungan::cases() as $s)
                        <option value="{{ $s->value }}" @selected($status === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                <i data-lucide="filter" class="size-4"></i>Terapkan
            </button>
        </form>

        <div class="grid grid-cols-1 gap-3 p-4 stagger sm:grid-cols-2 xl:grid-cols-4">
            <x-stat-card icon="clipboard-list" label="Total kunjungan" :value="$ringkasan['total_kunjungan']" warna="teal" />
            <x-stat-card icon="check-circle-2" label="Kunjungan selesai" :value="$ringkasan['selesai']" warna="ungu" />
            <x-stat-card icon="x-circle" label="Dibatalkan" :value="$ringkasan['dibatalkan']" warna="merah" />
            <x-stat-card icon="users" label="Total pasien" :value="$ringkasan['total_pasien']" warna="biru" />
        </div>

        @if ($kunjungan->isEmpty())
            <x-empty-state icon="bar-chart-3" title="Tidak ada data" description="Tidak ada kunjungan pada rentang tanggal yang dipilih." />
        @else
            <div class="overflow-x-auto border-t border-slate-100">
                <table class="w-full min-w-[760px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Antrean</th>
                            <th class="px-5 py-3">Pasien</th>
                            <th class="px-5 py-3">Dokter</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($kunjungan as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-5 py-3.5 text-[12px] text-slate-600">{{ $item->tanggal_kunjungan->translatedFormat('j F Y') }}</td>
                                <td class="px-5 py-3.5"><span class="rounded-md bg-teal-50 px-2 py-1 font-mono text-[11px] font-bold text-teal-700">{{ $item->kodeAntrean() }}</span></td>
                                <td class="px-5 py-3.5 font-semibold text-slate-700">{{ $item->pasien->nama_lengkap }}</td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">dr. {{ $item->dokter?->pengguna?->nama_lengkap ?? '-' }}</td>
                                <td class="px-5 py-3.5"><x-status-kunjungan :status="$item->status_kunjungan" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $kunjungan->links() }}</div>
        @endif
    </section>
@endsection
