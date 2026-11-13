@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[.12em] text-teal-600">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1.5 text-[24px] font-semibold tracking-[-.03em] text-slate-800">{{ $sapaan }}, {{ Str::before($pengguna->nama_lengkap, ' ') }}</h1>
            <p class="mt-1 text-[12.5px] text-slate-500">Pantau antrean konsultasi dan proses pemeriksaan pasien.</p>
        </div>
        <x-peran-switcher :pengguna="$pengguna" :peran-aktif="$peranAktif" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-3 stagger sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card icon="clipboard-list" label="Menunggu dipanggil" :value="$antreanMenunggu" warna="amber" />
        <x-stat-card icon="users" label="Pasien hari ini" :value="$pasienHariIni" warna="biru" />
        <x-stat-card icon="heart-pulse" label="Sedang konsultasi" :value="$sedangKonsultasi" warna="teal" />
        <x-stat-card icon="check-circle-2" label="Selesai hari ini" :value="$selesaiHariIni" warna="ungu" />
    </div>

    <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="text-[13.5px] font-semibold text-slate-800">Antrean konsultasi</h2>
                <p class="mt-0.5 text-[11px] text-slate-400">Pasien yang menunggu layanan konsultasi hari ini.</p>
            </div>
            <a href="{{ route('kunjungan.index') }}" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 hover:underline">Semua kunjungan <i data-lucide="arrow-right" class="size-3.5"></i></a>
        </div>

        @if ($antrean->isEmpty())
            <x-empty-state icon="stethoscope" title="Tidak ada antrean" description="Belum ada pasien yang menunggu konsultasi hari ini." />
        @else
            <ul class="divide-y divide-slate-50">
                @foreach ($antrean as $item)
                    <li class="flex flex-col gap-3 px-5 py-4 transition-colors hover:bg-slate-50/60 sm:flex-row sm:items-center">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-teal-solid text-[12px] font-bold text-white">{{ $item->kodeAntrean() }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-[13px] font-semibold text-slate-800">{{ $item->pasien->nama_lengkap }}</span>
                            <span class="mt-0.5 block truncate text-[11px] text-slate-400">
                                {{ $item->pasien->jenis_kelamin?->label() ?? '-' }}
                                @if ($item->pasien->usia() !== null) · {{ $item->pasien->usia() }} tahun @endif
                                @if ($item->keluhan_awal) · {{ Str::limit($item->keluhan_awal, 60) }} @endif
                            </span>
                        </span>

                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                            <x-status-kunjungan :status="$item->status_kunjungan" />

                            @if ($item->status_kunjungan?->value === 'menunggu')
                                <button type="button" data-panggil-antrean data-url="{{ route('kunjungan.panggil', $item) }}" data-dokter="{{ $item->dokter?->pengguna?->nama_lengkap ?? 'dr. Sumarwanto' }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                                    <i data-lucide="phone-call" class="size-3.5"></i>Panggil
                                </button>
                            @elseif ($item->status_kunjungan?->value === 'dipanggil')
                                <form method="POST" action="{{ route('kunjungan.mulai-konsultasi', $item) }}">
                                    @csrf
                                    <input type="hidden" name="lanjut" value="1">
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                                        <i data-lucide="play" class="size-3.5"></i>Mulai Pemeriksaan
                                    </button>
                                </form>
                            @elseif ($item->status_kunjungan?->value === 'sedang_konsultasi')
                                <a href="{{ route('pemeriksaan.show', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep">
                                    <i data-lucide="stethoscope" class="size-3.5"></i>Lanjutkan Pemeriksaan
                                </a>
                            @endif

                            <a href="{{ route('kunjungan.show', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-[11.5px] font-semibold text-slate-600 transition-colors hover:border-teal-300 hover:text-teal-700">
                                <i data-lucide="arrow-right" class="size-3.5"></i>Buka
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
