@extends('layouts.app')

@section('title', 'Rekam Medis')

@section('content')
    <x-page-header judul="Rekam Medis" deskripsi="Catatan pemeriksaan untuk {{ $rekamMedis->kunjungan->pasien->nama_lengkap }}.">
        <div class="flex items-center gap-2">
            <a href="{{ route('kunjungan.show', $rekamMedis->kunjungan) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="size-4"></i>Kunjungan</a>
            @if ($rekamMedis->status_rekam_medis?->value !== 'difinalisasi')
                <a href="{{ route('rekam-medis.edit', $rekamMedis) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-teal-deep"><i data-lucide="pencil" class="size-4"></i>Edit</a>
                <form method="POST" action="{{ route('rekam-medis.finalisasi', $rekamMedis) }}" data-confirm data-confirm-title="Finalisasi catatan" data-confirm-message="Catatan yang difinalisasi tidak dapat diubah lagi. Lanjutkan?" data-confirm-label="Ya, finalisasi">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-emerald-700"><i data-lucide="badge-check" class="size-4"></i>Finalisasi</button>
                </form>
            @endif
        </div>
    </x-page-header>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)]">
        <div class="space-y-4">
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Informasi</h2>
                    <x-status-resep :status="$rekamMedis->status_rekam_medis" />
                </div>
                <dl class="mt-4 space-y-3 text-[12.5px]">
                    <div class="flex justify-between"><dt class="text-slate-400">Pasien</dt><dd class="font-semibold text-slate-700">{{ $rekamMedis->kunjungan->pasien->nama_lengkap }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Antrean</dt><dd class="font-semibold text-slate-700">{{ $rekamMedis->kunjungan->kodeAntrean() }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Dokter</dt><dd class="font-semibold text-slate-700">dr. {{ $rekamMedis->dokter?->pengguna?->nama_lengkap ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-400">Tanggal</dt><dd class="font-semibold text-slate-700">{{ $rekamMedis->kunjungan->tanggal_kunjungan->translatedFormat('j F Y') }}</dd></div>
                </dl>
            </section>

            @if ($rekamMedis->tanda_vital)
                <section class="rounded-2xl border border-slate-200 bg-white p-5">
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Tanda vital</h2>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach ([
                            'tekanan_darah' => ['Tekanan darah', null, 'droplets'],
                            'suhu' => ['Suhu', '°C', 'thermometer'],
                            'nadi' => ['Nadi', '/mnt', 'heart'],
                            'pernapasan' => ['Pernapasan', '/mnt', 'wind'],
                            'berat_badan' => ['Berat badan', 'kg', 'scale'],
                            'tinggi_badan' => ['Tinggi badan', 'cm', 'ruler'],
                        ] as $kunci => [$label, $satuan, $ikon])
                            @if (! empty($rekamMedis->tanda_vital[$kunci]))
                                <div class="flex items-center gap-2.5 rounded-xl bg-slate-50 px-3.5 py-3">
                                    <i data-lucide="{{ $ikon }}" class="size-4 text-teal-600"></i>
                                    <span><span class="block text-[10.5px] text-slate-400">{{ $label }}</span><span class="block text-[13px] font-semibold text-slate-700">{{ $rekamMedis->tanda_vital[$kunci] }} {{ $satuan }}</span></span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <div class="space-y-4">
            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Hasil pemeriksaan</h2>
                <p class="mt-2 whitespace-pre-line text-[13px] leading-6 text-slate-600">{{ $rekamMedis->hasil_pemeriksaan ?? 'Tidak ada hasil pemeriksaan.' }}</p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Diagnosa</h2>
                @if ($rekamMedis->diagnosa->isEmpty())
                    <p class="mt-2 text-[12.5px] text-slate-400">Tidak ada diagnosa.</p>
                @else
                    <ul class="mt-3 space-y-2">
                        @foreach ($rekamMedis->diagnosa as $diagnosa)
                            <li class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 px-4 py-3">
                                <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-lg bg-teal-50 text-teal-600"><i data-lucide="flask-conical" class="size-3.5"></i></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-[12.5px] font-semibold text-slate-700">{{ $diagnosa->nama_diagnosa }} @if ($diagnosa->kode_diagnosa)<span class="font-mono text-[11px] text-slate-400">({{ $diagnosa->kode_diagnosa }})</span>@endif</span>
                                    @if ($diagnosa->catatan_diagnosa)<span class="mt-0.5 block text-[11.5px] text-slate-500">{{ $diagnosa->catatan_diagnosa }}</span>@endif
                                </span>
                                <span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500">{{ $diagnosa->jenis_diagnosa?->label() ?? 'Utama' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Tindakan medis</h2>
                @if ($rekamMedis->tindakanMedis->isEmpty())
                    <p class="mt-2 text-[12.5px] text-slate-400">Tidak ada tindakan.</p>
                @else
                    <ul class="mt-3 space-y-2">
                        @foreach ($rekamMedis->tindakanMedis as $tindakan)
                            <li class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 px-4 py-3">
                                <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="activity" class="size-3.5"></i></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-[12.5px] font-semibold text-slate-700">{{ $tindakan->nama_tindakan }}</span>
                                    @if ($tindakan->catatan_tindakan)<span class="mt-0.5 block text-[11.5px] text-slate-500">{{ $tindakan->catatan_tindakan }}</span>@endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            @if ($rekamMedis->catatan_klinis)
                <section class="rounded-2xl border border-slate-200 bg-white p-5">
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Catatan klinis</h2>
                    <p class="mt-2 whitespace-pre-line text-[13px] leading-6 text-slate-600">{{ $rekamMedis->catatan_klinis }}</p>
                </section>
            @endif
        </div>
    </div>
@endsection
