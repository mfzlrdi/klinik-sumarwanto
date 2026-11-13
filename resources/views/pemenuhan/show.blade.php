@extends('layouts.app')

@section('title', 'Detail Tindak Lanjut')

@section('content')
    <x-page-header judul="Tindak Lanjut {{ $pemenuhanResep->resep->nomor_resep }}" deskripsi="Proses penyerahan obat untuk {{ $pemenuhanResep->resep->kunjungan->pasien->nama_lengkap }}.">
        <a href="{{ route('pemenuhan.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="size-4"></i>Kembali</a>
    </x-page-header>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)]">
        <section class="h-fit rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Status penyerahan</h2>
                <x-status-pemenuhan :status="$pemenuhanResep->status_pemenuhan" />
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @if (in_array($pemenuhanResep->status_pemenuhan?->value, ['menunggu'], true))
                    <form method="POST" action="{{ route('pemenuhan.status', $pemenuhanResep) }}">@csrf<input type="hidden" name="status_pemenuhan" value="sedang_diproses"><button class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white hover:bg-teal-deep"><i data-lucide="play" class="size-3.5"></i>Mulai Proses</button></form>
                @endif
                @if ($pemenuhanResep->status_pemenuhan?->value === 'sedang_diproses')
                    <form method="POST" action="{{ route('pemenuhan.status', $pemenuhanResep) }}">@csrf<input type="hidden" name="status_pemenuhan" value="siap_diserahkan"><button class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white hover:bg-teal-deep"><i data-lucide="package-check" class="size-3.5"></i>Tandai Siap</button></form>
                @endif
                @if (in_array($pemenuhanResep->status_pemenuhan?->value, ['menunggu', 'sedang_diproses', 'siap_diserahkan'], true))
                    <form method="POST" action="{{ route('pemenuhan.status', $pemenuhanResep) }}">@csrf<input type="hidden" name="status_pemenuhan" value="dirujuk_ke_apotek_luar"><button class="inline-flex items-center gap-1.5 rounded-lg border border-violet-200 bg-violet-50 px-3 py-2 text-[11.5px] font-semibold text-violet-700 hover:bg-violet-100"><i data-lucide="truck" class="size-3.5"></i>Rujuk Apotek Luar</button></form>
                    <form method="POST" action="{{ route('pemenuhan.status', $pemenuhanResep) }}">@csrf<input type="hidden" name="status_pemenuhan" value="terhambat"><button class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[11.5px] font-semibold text-rose-700 hover:bg-rose-100"><i data-lucide="triangle-alert" class="size-3.5"></i>Terhambat</button></form>
                @endif
                @if ($pemenuhanResep->status_pemenuhan?->value === 'terhambat')
                    <form method="POST" action="{{ route('pemenuhan.status', $pemenuhanResep) }}">@csrf<input type="hidden" name="status_pemenuhan" value="menunggu"><button class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white hover:bg-teal-deep"><i data-lucide="refresh-cw" class="size-3.5"></i>Buka Kembali</button></form>
                @endif
            </div>

            <dl class="mt-5 space-y-3 border-t border-slate-100 pt-4 text-[12.5px]">
                <div class="flex justify-between"><dt class="text-slate-400">Pasien</dt><dd class="font-semibold text-slate-700">{{ $pemenuhanResep->resep->kunjungan->pasien->nama_lengkap }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Antrean</dt><dd class="font-semibold text-slate-700">{{ $pemenuhanResep->resep->kunjungan->kodeAntrean() }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Penanggung jawab</dt><dd class="font-semibold text-slate-700">{{ $pemenuhanResep->penanggungJawab?->nama_lengkap ?? '-' }}</dd></div>
            </dl>
        </section>

        <div class="space-y-4">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Rincian obat</h2>
                </div>

                @if (in_array($pemenuhanResep->status_pemenuhan?->value, ['siap_diserahkan', 'diserahkan_sebagian'], true))
                    <form method="POST" action="{{ route('pemenuhan.serahkan', $pemenuhanResep) }}" class="border-b border-slate-100">
                        @csrf
                        <ul class="divide-y divide-slate-50">
                            @foreach ($pemenuhanResep->resep->rincianResep as $rincian)
                                @php
                                    $terserahkan = $rincian->totalTerserahkan();
                                    $sisa = max(0, $rincian->jumlah_diresepkan - $terserahkan);
                                @endphp
                                <li class="flex items-center gap-4 px-5 py-3.5">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-[12.5px] font-semibold text-slate-700">{{ $rincian->nama_obat_saat_diresepkan }}</p>
                                        <p class="mt-0.5 text-[11px] text-slate-400">{{ $rincian->dosis ?? '-' }} · terserahkan {{ $terserahkan }}/{{ $rincian->jumlah_diresepkan }}</p>
                                    </div>
                                    <input type="number" name="jumlah[{{ $rincian->id_rincian_resep }}]" min="0" max="{{ $sisa }}" value="{{ $sisa }}" class="w-20 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20" aria-label="Jumlah diserahkan {{ $rincian->nama_obat_saat_diresepkan }}">
                                    <span class="text-[11px] text-slate-400">{{ $rincian->satuan_jumlah }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/60 px-5 py-4">
                            <input type="text" name="catatan_penyerahan" placeholder="Catatan penyerahan (opsional)" class="flex-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            <button type="submit" data-confirm data-confirm-title="Catat penyerahan" data-confirm-message="Jumlah yang dimasukkan akan dicatat sebagai penyerahan obat." data-confirm-label="Ya, catat" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-4 py-2.5 text-[12px] font-semibold text-white shadow-sm hover:bg-teal-deep"><i data-lucide="check" class="size-4"></i>Catat Penyerahan</button>
                        </div>
                    </form>
                @else
                    <ul class="divide-y divide-slate-50">
                        @foreach ($pemenuhanResep->resep->rincianResep as $rincian)
                            <li class="flex items-center gap-4 px-5 py-3.5">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="pill" class="size-4"></i></span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[12.5px] font-semibold text-slate-700">{{ $rincian->nama_obat_saat_diresepkan }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">{{ $rincian->dosis ?? '-' }} · {{ $rincian->jumlah_diresepkan }} {{ $rincian->satuan_jumlah }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            @if ($pemenuhanResep->penyerahan->isNotEmpty())
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 px-5 py-4"><h2 class="text-[13.5px] font-semibold text-slate-800">Riwayat penyerahan</h2></div>
                    <ul class="divide-y divide-slate-50">
                        @foreach ($pemenuhanResep->penyerahan as $serah)
                            <li class="flex items-center gap-3 px-5 py-3.5">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600"><i data-lucide="check" class="size-4"></i></span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[12.5px] font-semibold text-slate-700">{{ $serah->rincianResep->nama_obat_saat_diresepkan }} — {{ $serah->jumlah_diserahkan }} {{ $serah->rincianResep->satuan_jumlah }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">{{ $serah->penyerah?->nama_lengkap ?? '-' }} · {{ $serah->diserahkan_pada?->translatedFormat('j F Y H:i') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
@endsection
