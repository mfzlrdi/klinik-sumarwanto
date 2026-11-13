@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('content')
    <x-page-header judul="Detail Pasien" deskripsi="Informasi biodata dan riwayat kunjungan pasien.">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('pasien.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="size-4"></i>Kembali</a>
            @if ($pengguna?->memilikiPeran('dokter', 'administrator'))
                <a href="{{ route('pasien.riwayat-medis', $pasien) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-teal-200 bg-teal-50 px-3.5 py-2 text-[12px] font-semibold text-teal-700 hover:bg-teal-100"><i data-lucide="file-heart" class="size-4"></i>Riwayat Medis</a>
            @endif
            @can('update', $pasien)
                <a href="{{ route('pasien.edit', $pasien) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-teal-deep"><i data-lucide="pencil" class="size-4"></i>Edit</a>
            @endcan
            @can('create', \App\Models\Kunjungan::class)
                <a href="{{ route('kunjungan.create', ['id_pasien' => $pasien->id_pasien]) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-teal-deep"><i data-lucide="user-plus" class="size-4"></i>Daftarkan</a>
            @endcan
        </div>
    </x-page-header>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.3fr)]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
            <div class="flex items-center gap-4">
                <span class="flex size-14 items-center justify-center rounded-2xl bg-teal-solid text-[16px] font-bold text-white">{{ collect(explode(' ', $pasien->nama_lengkap))->take(2)->map(fn ($b) => mb_strtoupper(mb_substr($b, 0, 1)))->implode('') }}</span>
                <div>
                    <h2 class="text-[16px] font-semibold text-slate-800">{{ $pasien->nama_lengkap }}</h2>
                    <span class="mt-1 inline-block rounded-md bg-teal-50 px-2 py-1 font-mono text-[11px] font-semibold text-teal-700">{{ $pasien->nomor_rekam_medis }}</span>
                </div>
            </div>

            <dl class="mt-6 space-y-4">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="id-card" class="size-4"></i></span>
                    <div><dt class="text-[10.5px] font-medium uppercase tracking-[.06em] text-slate-400">Nomor identitas</dt><dd class="mt-0.5 text-[12.5px] text-slate-700">{{ $pasien->nomor_identitas ?? '-' }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="calendar-days" class="size-4"></i></span>
                    <div><dt class="text-[10.5px] font-medium uppercase tracking-[.06em] text-slate-400">Tanggal lahir</dt><dd class="mt-0.5 text-[12.5px] text-slate-700">{{ $pasien->tanggal_lahir?->translatedFormat('j F Y') ?? '-' }} @if ($pasien->usia() !== null)({{ $pasien->usia() }} tahun)@endif</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="{{ $pasien->jenis_kelamin?->value === 'perempuan' ? 'venus' : 'mars' }}" class="size-4"></i></span>
                    <div><dt class="text-[10.5px] font-medium uppercase tracking-[.06em] text-slate-400">Jenis kelamin</dt><dd class="mt-0.5 text-[12.5px] text-slate-700">{{ $pasien->jenis_kelamin?->label() ?? '-' }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="phone" class="size-4"></i></span>
                    <div><dt class="text-[10.5px] font-medium uppercase tracking-[.06em] text-slate-400">Telepon</dt><dd class="mt-0.5 text-[12.5px] text-slate-700">{{ $pasien->nomor_telepon ?? '-' }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="mail" class="size-4"></i></span>
                    <div><dt class="text-[10.5px] font-medium uppercase tracking-[.06em] text-slate-400">Email</dt><dd class="mt-0.5 text-[12.5px] text-slate-700">{{ $pasien->alamat_email ?? '-' }}</dd></div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="map-pin" class="size-4"></i></span>
                    <div><dt class="text-[10.5px] font-medium uppercase tracking-[.06em] text-slate-400">Alamat</dt><dd class="mt-0.5 text-[12.5px] leading-5 text-slate-700">{{ $pasien->alamat ?? '-' }}</dd></div>
                </div>
                @if ($pasien->catatan_administratif)
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex size-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500"><i data-lucide="sticky-note" class="size-4"></i></span>
                        <div><dt class="text-[10.5px] font-medium uppercase tracking-[.06em] text-slate-400">Catatan</dt><dd class="mt-0.5 text-[12.5px] leading-5 text-slate-700">{{ $pasien->catatan_administratif }}</dd></div>
                    </div>
                @endif
            </dl>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-[13.5px] font-semibold text-slate-800">Riwayat kunjungan</h2>
                <p class="mt-0.5 text-[11px] text-slate-400">{{ $pasien->kunjungan_count }} kunjungan tercatat.</p>
            </div>

            @if ($riwayat->isEmpty())
                <x-empty-state icon="clipboard-list" title="Belum ada kunjungan" description="Pasien ini belum memiliki riwayat kunjungan." />
            @else
                <ul class="divide-y divide-slate-50">
                    @foreach ($riwayat as $kunjungan)
                        <li class="flex items-center gap-3 px-5 py-3.5 transition-colors hover:bg-slate-50/60">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-[11px] font-bold text-slate-600">{{ $kunjungan->kodeAntrean() }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[12.5px] font-semibold text-slate-700">{{ $kunjungan->tanggal_kunjungan->translatedFormat('j F Y') }}</span>
                                <span class="mt-0.5 block text-[11px] text-slate-400">dr. {{ $kunjungan->dokter?->pengguna?->nama_lengkap ?? '-' }}</span>
                            </span>
                            <x-status-kunjungan :status="$kunjungan->status_kunjungan" />
                            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-teal-50 hover:text-teal-700" title="Lihat"><i data-lucide="arrow-right" class="size-4"></i></a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
@endsection
