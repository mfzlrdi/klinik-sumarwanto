@extends('layouts.app')

@section('title', 'Riwayat Rekam Medis')

@section('content')
    <x-page-header judul="Riwayat Rekam Medis" deskripsi="Seluruh catatan pemeriksaan pasien {{ $pasien->nama_lengkap }}.">
        <div class="flex items-center gap-2">
            <a href="{{ route('pasien.show', $pasien) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="size-4"></i>Data Pasien</a>
        </div>
    </x-page-header>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
        <div class="flex items-center gap-3">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-teal-solid text-[14px] font-bold text-white">{{ collect(explode(' ', $pasien->nama_lengkap))->take(2)->map(fn ($b) => mb_strtoupper(mb_substr($b, 0, 1)))->implode('') }}</span>
            <div class="min-w-0">
                <p class="block truncate text-[15px] font-semibold text-slate-800">{{ $pasien->nama_lengkap }}</p>
                <p class="mt-0.5 text-[11.5px] text-slate-400">{{ $pasien->nomor_rekam_medis }} · {{ $pasien->jenis_kelamin?->label() ?? '-' }} @if ($pasien->usia() !== null) · {{ $pasien->usia() }} tahun @endif</p>
            </div>
        </div>
    </section>

    <section class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-[13.5px] font-semibold text-slate-800">Catatan medis</h2>
            <p class="mt-0.5 text-[11px] text-slate-400">{{ $riwayat->total() }} catatan pemeriksaan.</p>
        </div>

        @if ($riwayat->isEmpty())
            <x-empty-state icon="file-heart" title="Belum ada rekam medis" description="Pasien ini belum memiliki catatan pemeriksaan." />
        @else
            <ul class="divide-y divide-slate-50">
                @foreach ($riwayat as $item)
                    @php $diagnosaUtama = $item->diagnosa->firstWhere('jenis_diagnosa', \App\Enums\JenisDiagnosa::Utama) ?? $item->diagnosa->first(); @endphp
                    <li class="px-5 py-4 transition-colors hover:bg-slate-50/60">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex items-start gap-3">
                                <span class="flex size-11 shrink-0 flex-col items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                                    <span class="text-[12px] font-bold leading-none">{{ $item->kunjungan->tanggal_kunjungan->translatedFormat('d') }}</span>
                                    <span class="mt-0.5 text-[9px] font-semibold uppercase">{{ $item->kunjungan->tanggal_kunjungan->translatedFormat('M') }}</span>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[13.5px] font-semibold text-slate-800">{{ $diagnosaUtama?->nama_diagnosa ?? 'Tanpa diagnosa tercatat' }}</p>
                                    <p class="mt-0.5 text-[11.5px] text-slate-400">{{ $item->kunjungan->tanggal_kunjungan->translatedFormat('j F Y') }} · dr. {{ $item->dokter?->pengguna?->nama_lengkap ?? '-' }} · antrean {{ $item->kunjungan->kodeAntrean() }}</p>
                                    @if ($item->tindakanMedis->isNotEmpty())
                                        <p class="mt-1 text-[11.5px] text-slate-500">Tindakan: {{ $item->tindakanMedis->pluck('nama_tindakan')->implode(', ') }}</p>
                                    @endif
                                    @if ($item->catatan_klinis)
                                        <p class="mt-1 line-clamp-2 text-[11.5px] text-slate-500">{{ $item->catatan_klinis }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <x-status-resep :status="$item->status_rekam_medis" />
                                @if ($item->resep)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-0.5 text-[10px] font-semibold text-violet-700"><i data-lucide="pill" class="size-3"></i>{{ $item->resep->rincianResep->count() }} obat</span>
                                @endif
                                <a href="{{ route('rekam-medis.show', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-[11.5px] font-semibold text-slate-600 transition-colors hover:border-teal-300 hover:text-teal-700"><i data-lucide="eye" class="size-3.5"></i>Lihat</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="border-t border-slate-100 px-5 py-3.5">{{ $riwayat->links() }}</div>
        @endif
    </section>
@endsection
