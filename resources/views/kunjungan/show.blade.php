@extends('layouts.app')

@section('title', 'Detail Kunjungan')

@section('content')
    @php
        $dokterAkses = $pengguna?->memilikiPeran('dokter', 'administrator') ?? false;
    @endphp

    <x-page-header judul="Kunjungan {{ $kunjungan->kodeAntrean() }}" deskripsi="Detail kunjungan dan alur layanan pasien.">
        <div class="flex items-center gap-2">
            <a href="{{ route('kunjungan.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="size-4"></i>Kembali</a>
            <a href="{{ route('kunjungan.cetak', $kunjungan) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="printer" class="size-4"></i>Cetak</a>
        </div>
    </x-page-header>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]">
        <div class="space-y-4">
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Status kunjungan</h2>
                    <x-status-kunjungan :status="$kunjungan->status_kunjungan" />
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @can('panggil', $kunjungan)
                        @if ($kunjungan->status_kunjungan?->value === 'menunggu')
                            <button type="button" data-panggil-antrean data-url="{{ route('kunjungan.panggil', $kunjungan) }}" data-dokter="{{ $kunjungan->dokter?->pengguna?->nama_lengkap ?? 'dr. Sumarwanto' }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white hover:bg-teal-deep"><i data-lucide="phone-call" class="size-3.5"></i>Panggil</button>
                        @endif
                    @endcan
                    @can('mulaiKonsultasi', $kunjungan)
                        @if ($kunjungan->status_kunjungan?->value === 'dipanggil')
                            <form method="POST" action="{{ route('kunjungan.mulai-konsultasi', $kunjungan) }}">@csrf<input type="hidden" name="lanjut" value="1"><button class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3 py-2 text-[11.5px] font-semibold text-white hover:bg-teal-deep"><i data-lucide="play" class="size-3.5"></i>Mulai Pemeriksaan</button></form>
                        @endif
                    @endcan
                    @can('selesai', $kunjungan)
                        @if ($kunjungan->status_kunjungan?->value === 'sedang_konsultasi')
                            <form method="POST" action="{{ route('kunjungan.selesai', $kunjungan) }}" data-confirm data-confirm-title="Selesaikan kunjungan" data-confirm-message="Pastikan pemeriksaan telah selesai. Kunjungan akan ditandai selesai." data-confirm-label="Ya, selesaikan">@csrf<button class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-2 text-[11.5px] font-semibold text-white hover:bg-emerald-700"><i data-lucide="check-circle-2" class="size-3.5"></i>Selesaikan</button></form>
                        @endif
                    @endcan
                    @if (($pengguna?->memilikiPeran('dokter', 'administrator') ?? false) && in_array($kunjungan->status_kunjungan?->value, ['dipanggil', 'sedang_konsultasi'], true))
                        <a href="{{ route('pemeriksaan.show', $kunjungan) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-teal-200 bg-teal-50 px-3 py-2 text-[11.5px] font-semibold text-teal-700 hover:bg-teal-100"><i data-lucide="stethoscope" class="size-3.5"></i>Halaman Pemeriksaan</a>
                    @endif
                    @can('batalkan', $kunjungan)
                        @if (in_array($kunjungan->status_kunjungan?->value, ['menunggu', 'dipanggil'], true))
                            <form method="POST" action="{{ route('kunjungan.batalkan', $kunjungan) }}" data-confirm data-confirm-title="Batalkan kunjungan" data-confirm-message="Kunjungan akan dibatalkan. Tindakan ini tidak dapat dibatalkan." data-confirm-label="Ya, batalkan">
                                @csrf
                                <input type="hidden" name="alasan_pembatalan" value="Dibatalkan oleh petugas.">
                                <button class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3 py-2 text-[11.5px] font-semibold text-white hover:bg-rose-700"><i data-lucide="x-circle" class="size-3.5"></i>Batalkan</button>
                            </form>
                        @endif
                    @endcan
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Informasi pasien</h2>
                <div class="mt-3 flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-xl bg-teal-solid text-[13px] font-bold text-white">{{ collect(explode(' ', $kunjungan->pasien->nama_lengkap))->take(2)->map(fn ($b) => mb_strtoupper(mb_substr($b, 0, 1)))->implode('') }}</span>
                    <div class="min-w-0">
                        <a href="{{ route('pasien.show', $kunjungan->pasien) }}" class="block truncate text-[14px] font-semibold text-slate-800 hover:text-teal-700 hover:underline">{{ $kunjungan->pasien->nama_lengkap }}</a>
                        <span class="mt-0.5 block text-[11.5px] text-slate-400">{{ $kunjungan->pasien->nomor_rekam_medis }} · {{ $kunjungan->pasien->jenis_kelamin?->label() ?? '-' }}</span>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Keluhan awal</h2>
                <p class="mt-2 text-[12.5px] leading-6 text-slate-600">{{ $kunjungan->keluhan_awal ?? 'Tidak ada keluhan yang dicatat.' }}</p>
                <div class="mt-3 border-t border-slate-100 pt-3 text-[11px] text-slate-400">Didaftarkan oleh {{ $kunjungan->pendaftar?->nama_lengkap ?? '-' }} · {{ $kunjungan->dibuat_pada?->translatedFormat('j F Y H:i') }}</div>
            </section>
        </div>

        <div class="space-y-4">
            @if ($dokterAkses)
                <section class="rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-[13.5px] font-semibold text-slate-800">Pemeriksaan medis</h2>
                            <p class="mt-0.5 text-[11px] text-slate-400">Catatan klinis dokter untuk kunjungan ini.</p>
                        </div>
                        @if ($kunjungan->rekamMedis)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200"><i data-lucide="check" class="size-3.5"></i>Tersedia</span>
                        @endif
                    </div>

                    @if ($kunjungan->rekamMedis)
                        <a href="{{ route('rekam-medis.show', $kunjungan->rekamMedis) }}" class="mt-4 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 transition-colors hover:border-teal-300 hover:bg-teal-50/40">
                            <span class="flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-lg bg-teal-solid text-white"><i data-lucide="heart-pulse" class="size-4"></i></span>
                                <span><span class="block text-[12.5px] font-semibold text-slate-700">Lihat catatan medis</span><span class="mt-0.5 block text-[11px] text-slate-400">{{ $kunjungan->rekamMedis->status_rekam_medis?->label() ?? 'Draf' }}</span></span>
                            </span>
                            <i data-lucide="arrow-right" class="size-4 text-slate-400"></i>
                        </a>
                    @else
                        <a href="{{ route('rekam-medis.create', $kunjungan) }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12px] font-semibold text-white shadow-sm hover:bg-teal-deep">
                            <i data-lucide="plus" class="size-4"></i>Buat Catatan Medis
                        </a>
                    @endif
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-[13.5px] font-semibold text-slate-800">Resep obat</h2>
                            <p class="mt-0.5 text-[11px] text-slate-400">Resep yang terhubung dengan kunjungan ini.</p>
                        </div>
                    </div>

                    @if ($kunjungan->resep)
                        <a href="{{ route('resep.show', $kunjungan->resep) }}" class="mt-4 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 transition-colors hover:border-teal-300 hover:bg-teal-50/40">
                            <span class="flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-lg bg-violet-500 text-white"><i data-lucide="pill" class="size-4"></i></span>
                                <span><span class="block text-[12.5px] font-semibold text-slate-700">{{ $kunjungan->resep->nomor_resep }}</span><span class="mt-0.5 block text-[11px] text-slate-400"><x-status-resep :status="$kunjungan->resep->status_resep" /></span></span>
                            </span>
                            <i data-lucide="arrow-right" class="size-4 text-slate-400"></i>
                        </a>
                    @else
                        <a href="{{ route('resep.create', $kunjungan) }}" class="mt-4 inline-flex items-center gap-2 rounded-lg border border-violet-200 bg-violet-50 px-4 py-2.5 text-[12px] font-semibold text-violet-700 hover:bg-violet-100">
                            <i data-lucide="pill" class="size-4"></i>Buat Resep
                        </a>
                    @endif
                </section>
            @else
                <section class="rounded-2xl border border-slate-200 bg-white p-5">
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Catatan administratif</h2>
                    <p class="mt-2 text-[12.5px] leading-6 text-slate-600">{{ $kunjungan->catatan_administratif ?? 'Tidak ada catatan administratif.' }}</p>
                </section>
            @endif
        </div>
    </div>
@endsection
