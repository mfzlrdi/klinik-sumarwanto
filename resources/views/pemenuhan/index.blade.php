@extends('layouts.app')

@section('title', 'Tindak Lanjut Obat')

@section('content')
    <x-page-header judul="Tindak Lanjut Obat" deskripsi="Kelola penyerahan obat untuk resep yang telah difinalisasi." />

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <form method="GET" action="{{ route('pemenuhan.index') }}" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="status" class="mb-1.5 block text-[11px] font-semibold text-slate-500">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 sm:w-auto">
                    <option value="">Semua status</option>
                    @foreach ($statusPemenuhan as $s)
                        <option value="{{ $s->value }}" @selected($status === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                <i data-lucide="filter" class="size-4"></i>Terapkan
            </button>
        </form>

        @if ($pemenuhan->isEmpty())
            <x-empty-state icon="package-check" title="Tidak ada tugas" description="Tugas tindak lanjut obat akan muncul di sini." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-semibold uppercase tracking-[.06em] text-slate-400">
                            <th class="px-5 py-3">Resep</th>
                            <th class="px-5 py-3">Pasien</th>
                            <th class="px-5 py-3">Item</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($pemenuhan as $item)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-5 py-3.5"><span class="rounded-md bg-violet-50 px-2 py-1 font-mono text-[11px] font-bold text-violet-700">{{ $item->resep->nomor_resep }}</span></td>
                                <td class="px-5 py-3.5"><a href="{{ route('pasien.show', $item->resep->kunjungan->pasien) }}" class="font-semibold text-slate-700 hover:text-teal-700 hover:underline">{{ $item->resep->kunjungan->pasien->nama_lengkap }}</a></td>
                                <td class="px-5 py-3.5 text-[12px] text-slate-500">{{ $item->resep->rincianResep->count() }} obat</td>
                                <td class="px-5 py-3.5"><x-status-pemenuhan :status="$item->status_pemenuhan" /></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('pemenuhan.show', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white shadow-sm hover:bg-teal-deep"><i data-lucide="arrow-right" class="size-3.5"></i>Proses</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $pemenuhan->links() }}</div>
        @endif
    </section>
@endsection
