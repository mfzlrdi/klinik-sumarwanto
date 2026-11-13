@extends('layouts.app')

@section('title', 'Daftar Resep')

@section('content')
    <x-page-header judul="Resep" deskripsi="Kelola resep yang dibuat oleh dokter." />

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <form method="GET" action="{{ route('resep.index') }}" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="status" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 sm:w-auto">
                    <option value="">Semua status</option>
                    <option value="draf" @selected($status === 'draf')>Draf</option>
                    <option value="difinalisasi" @selected($status === 'difinalisasi')>Difinalisasi</option>
                    <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                <i data-lucide="filter" class="size-4"></i>Terapkan
            </button>
        </form>

        @if ($resep->isEmpty())
            <x-empty-state icon="pill" title="Belum ada resep" description="Resep yang dibuat dokter akan muncul di sini." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-5 py-3">Nomor Resep</th>
                            <th class="px-5 py-3">Pasien</th>
                            <th class="px-5 py-3">Dokter</th>
                            <th class="px-5 py-3">Item</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($resep as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-5 py-3.5"><span class="rounded-md bg-violet-50 px-2 py-1 font-mono text-[11px] font-bold text-violet-700">{{ $item->nomor_resep }}</span></td>
                                <td class="px-5 py-3.5"><a href="{{ route('pasien.show', $item->kunjungan->pasien) }}" class="font-semibold text-slate-700 hover:text-teal-700 hover:underline">{{ $item->kunjungan->pasien->nama_lengkap }}</a></td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">dr. {{ $item->dokter?->pengguna?->nama_lengkap ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">{{ $item->rincianResep->count() }} obat</td>
                                <td class="px-5 py-3.5"><x-status-resep :status="$item->status_resep" /></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('resep.show', $item) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-violet-50 hover:text-violet-700" title="Detail"><i data-lucide="eye" class="size-4"></i></a>
                                        <a href="{{ route('resep.cetak', $item) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-violet-50 hover:text-violet-700" title="Cetak"><i data-lucide="printer" class="size-4"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $resep->links() }}</div>
        @endif
    </section>
@endsection
