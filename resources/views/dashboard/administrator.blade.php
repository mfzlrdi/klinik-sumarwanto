@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[.12em] text-teal-600">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1.5 text-[24px] font-semibold tracking-[-.03em] text-slate-800">{{ $sapaan }}, {{ Str::before($pengguna->nama_lengkap, ' ') }}</h1>
            <p class="mt-1 text-[12.5px] text-slate-500">Ringkasan operasional dan aktivitas sistem praktik.</p>
        </div>
        <x-peran-switcher :pengguna="$pengguna" :peran-aktif="$peranAktif" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-3 stagger sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card icon="users" label="Total pasien" :value="$totalPasien" warna="biru" />
        <x-stat-card icon="clipboard-list" label="Kunjungan hari ini" :value="$kunjunganHariIni" warna="teal" />
        <x-stat-card icon="user-cog" label="Total pengguna" :value="$totalPengguna" warna="amber" />
        <x-stat-card icon="pill" label="Resep difinalisasi" :value="$resepHariIni" warna="ungu" />
    </div>

    <div class="mt-6 grid gap-4 xl:grid-cols-[minmax(0,1.7fr)_minmax(280px,1fr)]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Kunjungan 7 hari terakhir</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">Jumlah kunjungan per hari.</p>
                </div>
                <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 hover:underline">Laporan <i data-lucide="arrow-right" class="size-3.5"></i></a>
            </div>
            <div class="mt-4 h-[260px]">
                <canvas data-grafik-kunjungan data-label="{{ Js::from($grafikKunjungan['label']) }}" data-jumlah="{{ Js::from($grafikKunjungan['jumlah']) }}"></canvas>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Aktivitas terbaru</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">Jejak perubahan penting sistem.</p>
                </div>
                <a href="{{ route('catatan-aktivitas.index') }}" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 hover:underline">Semua</a>
            </div>

            @if ($aktivitasTerbaru->isEmpty())
                <x-empty-state icon="scroll-text" title="Belum ada aktivitas" description="Aktivitas penting akan tercatat di sini." />
            @else
                <ul class="divide-y divide-slate-50">
                    @foreach ($aktivitasTerbaru as $aktivitas)
                        <li class="flex items-start gap-3 px-5 py-3">
                            <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full bg-teal-50 text-teal-600"><i data-lucide="history" class="size-4"></i></span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[12px] font-medium text-slate-700">{{ $aktivitas->jenis_aktivitas?->label() ?? '-' }} · <span class="text-slate-500">{{ $aktivitas->nama_entitas }}</span></span>
                                <span class="mt-0.5 block text-[10.5px] text-slate-400">{{ $aktivitas->pengguna?->nama_lengkap ?? 'Sistem' }} · {{ $aktivitas->dibuat_pada?->diffForHumans() }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
