@extends('layouts.app')

@section('title', 'Dashboard Resepsionis')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[.12em] text-teal-600">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1.5 text-[24px] font-semibold tracking-[-.03em] text-slate-800">{{ $sapaan }}, {{ Str::before($pengguna->nama_lengkap, ' ') }}</h1>
            <p class="mt-1 text-[12.5px] text-slate-500">Kelola pendaftaran pasien dan antrean hari ini.</p>
        </div>
        <x-peran-switcher :pengguna="$pengguna" :peran-aktif="$peranAktif" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-3 stagger sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card icon="users" label="Total pasien" :value="$totalPasien" warna="biru" />
        <x-stat-card icon="clipboard-list" label="Antrean hari ini" :value="$antreanHariIni" warna="teal" />
        <x-stat-card icon="heart-pulse" label="Sedang dilayani" :value="$sedangDilayani" warna="amber" />
        <x-stat-card icon="check-circle-2" label="Kunjungan selesai" :value="$selesaiHariIni" warna="ungu" />
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="{{ route('kunjungan.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-teal-deep">
            <i data-lucide="user-plus" class="size-4"></i>Daftarkan Kunjungan
        </a>
        <a href="{{ route('pasien.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-semibold text-slate-700 transition-all hover:-translate-y-0.5 hover:border-teal-300 hover:text-teal-700">
            <i data-lucide="plus" class="size-4"></i>Pasien Baru
        </a>
    </div>

    <div class="mt-6 grid gap-4 xl:grid-cols-[minmax(0,1.6fr)_minmax(280px,1fr)]">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Antrean kunjungan hari ini</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">Pasien yang telah terdaftar hari ini.</p>
                </div>
                <a href="{{ route('kunjungan.index') }}" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 hover:underline">Lihat semua <i data-lucide="arrow-right" class="size-3.5"></i></a>
            </div>

            @if ($antreanTerbaru->isEmpty())
                <x-empty-state icon="clipboard-list" title="Belum ada antrean" description="Kunjungan yang didaftarkan hari ini akan muncul di sini." />
            @else
                <ul class="divide-y divide-slate-50">
                    @foreach ($antreanTerbaru as $item)
                        <li class="flex items-center gap-3 px-5 py-3 transition-colors hover:bg-slate-50/60">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-[12px] font-bold text-teal-700">{{ $item->kodeAntrean() }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[12.5px] font-semibold text-slate-700">{{ $item->pasien->nama_lengkap }}</span>
                                <span class="mt-0.5 block truncate text-[11px] text-slate-400">{{ $item->pasien->nomor_rekam_medis }}</span>
                            </span>
                            <x-status-kunjungan :status="$item->status_kunjungan" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Tindak lanjut obat</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">Tugas yang menunggu penanganan.</p>
                </div>
                <a href="{{ route('pemenuhan.index') }}" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 hover:underline">Lihat semua <i data-lucide="arrow-right" class="size-3.5"></i></a>
            </div>

            @if ($tindakLanjut->isEmpty())
                <x-empty-state icon="package-check" title="Tidak ada tugas" description="Tugas tindak lanjut obat akan muncul di sini." />
            @else
                <ul class="divide-y divide-slate-50">
                    @foreach ($tindakLanjut as $tugas)
                        <li class="flex items-center gap-3 px-5 py-3 transition-colors hover:bg-slate-50/60">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i data-lucide="package-check" class="size-4"></i></span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[12.5px] font-semibold text-slate-700">{{ $tugas->resep->kunjungan->pasien->nama_lengkap }}</span>
                                <span class="mt-0.5 block truncate text-[11px] text-slate-400">Resep {{ $tugas->resep->nomor_resep }}</span>
                            </span>
                            <x-status-pemenuhan :status="$tugas->status_pemenuhan" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
