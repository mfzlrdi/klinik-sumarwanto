@extends('layouts.app')

@section('title', 'Detail Resep')

@section('content')
    <x-page-header judul="Resep {{ $resep->nomor_resep }}" deskripsi="Detail resep obat untuk {{ $resep->kunjungan->pasien->nama_lengkap }}.">
        <div class="flex items-center gap-2">
            <a href="{{ route('resep.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="size-4"></i>Kembali</a>
            <a href="{{ route('resep.cetak', $resep) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="printer" class="size-4"></i>Cetak</a>
            @if ($resep->status_resep?->value === 'draf')
                <a href="{{ route('resep.edit', $resep) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-teal-deep"><i data-lucide="pencil" class="size-4"></i>Edit</a>
                <form method="POST" action="{{ route('resep.finalisasi', $resep) }}" data-confirm data-confirm-title="Finalisasi resep" data-confirm-message="Resep akan disahkan dan tugas tindak lanjut obat dikirim ke resepsionis. Lanjutkan?" data-confirm-label="Ya, finalisasi">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-emerald-700"><i data-lucide="check-circle-2" class="size-4"></i>Finalisasi</button>
                </form>
            @endif
            @if ($resep->status_resep?->value === 'difinalisasi')
                <form method="POST" action="{{ route('resep.batalkan', $resep) }}" data-confirm data-confirm-title="Batalkan resep" data-confirm-message="Resep yang difinalisasi akan dibatalkan dan tercatat dalam riwayat. Lanjutkan?" data-confirm-label="Ya, batalkan">
                    @csrf
                    <input type="hidden" name="alasan_pembatalan" value="Dibatalkan oleh dokter.">
                    <button class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-rose-700"><i data-lucide="x-circle" class="size-4"></i>Batalkan</button>
                </form>
            @endif
        </div>
    </x-page-header>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]">
        <section class="h-fit rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Informasi</h2>
                <x-status-resep :status="$resep->status_resep" />
            </div>
            <dl class="mt-4 space-y-3 text-[12.5px]">
                <div class="flex justify-between"><dt class="text-slate-400">Pasien</dt><dd class="font-semibold text-slate-700">{{ $resep->kunjungan->pasien->nama_lengkap }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Antrean</dt><dd class="font-semibold text-slate-700">{{ $resep->kunjungan->kodeAntrean() }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Dokter</dt><dd class="font-semibold text-slate-700">dr. {{ $resep->dokter?->pengguna?->nama_lengkap ?? '-' }}</dd></div>
                @if ($resep->difinalisasi_pada)
                    <div class="flex justify-between"><dt class="text-slate-400">Difinalisasi</dt><dd class="font-semibold text-slate-700">{{ $resep->difinalisasi_pada->translatedFormat('j F Y H:i') }}</dd></div>
                @endif
                @if ($resep->status_resep?->value === 'dibatalkan')
                    <div class="flex justify-between"><dt class="text-slate-400">Alasan pembatalan</dt><dd class="font-semibold text-rose-600">{{ $resep->alasan_pembatalan ?? '-' }}</dd></div>
                @endif
            </dl>
            @if ($resep->catatan_resep)
                <div class="mt-4 rounded-xl bg-slate-50 px-4 py-3 text-[12px] leading-5 text-slate-600">{{ $resep->catatan_resep }}</div>
            @endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Rincian obat</h2>
            </div>
            <ul class="divide-y divide-slate-50">
                @foreach ($resep->rincianResep as $rincian)
                    <li class="px-5 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="pill" class="size-4"></i></span>
                                <div>
                                    <p class="text-[13px] font-semibold text-slate-800">{{ $rincian->nama_obat_saat_diresepkan }}</p>
                                    <p class="mt-0.5 text-[11.5px] text-slate-400">
                                        {{ collect([$rincian->kekuatan_saat_diresepkan, $rincian->bentuk_sediaan_saat_diresepkan])->filter()->implode(' · ') ?: '-' }}
                                    </p>
                                </div>
                            </div>
                            <span class="shrink-0 rounded-md bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">{{ $rincian->jumlah_diresepkan }} {{ $rincian->satuan_jumlah ?? '' }}</span>
                        </div>
                        <div class="mt-3 grid grid-cols-1 gap-2 text-[12px] sm:grid-cols-2">
                            @if ($rincian->dosis)<div><span class="text-slate-400">Dosis:</span> <span class="font-medium text-slate-700">{{ $rincian->dosis }}</span></div>@endif
                            @if ($rincian->frekuensi_pemberian)<div><span class="text-slate-400">Frekuensi:</span> <span class="font-medium text-slate-700">{{ $rincian->frekuensi_pemberian }}</span></div>@endif
                            @if ($rincian->cara_pemberian)<div><span class="text-slate-400">Cara:</span> <span class="font-medium text-slate-700">{{ $rincian->cara_pemberian }}</span></div>@endif
                            @if ($rincian->lama_penggunaan_hari)<div><span class="text-slate-400">Lama:</span> <span class="font-medium text-slate-700">{{ $rincian->lama_penggunaan_hari }} hari</span></div>@endif
                        </div>
                        @if ($rincian->petunjuk_penggunaan)
                            <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-[11.5px] text-amber-700"><i data-lucide="info" class="mr-1 inline size-3.5"></i>{{ $rincian->petunjuk_penggunaan }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
@endsection
