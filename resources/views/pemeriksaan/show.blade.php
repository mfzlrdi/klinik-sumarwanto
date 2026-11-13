@extends('layouts.app')

@section('title', 'Pemeriksaan Pasien')

@section('content')
    @php
        $rm = $kunjungan->rekamMedis;
        $resep = $kunjungan->resep;
        $status = $kunjungan->status_kunjungan?->value;
        $dokterNama = $kunjungan->dokter?->pengguna?->nama_lengkap ?? 'dr. Sumarwanto';

        $tabAwal = 'rme';
        if ($errors->hasAny(['tanggal_mulai', 'tanggal_selesai', 'diagnosa_ringkas'])) {
            $tabAwal = 'surat';
        } elseif ($errors->hasAny(['rincian', 'rincian.*', 'catatan_resep'])) {
            $tabAwal = 'resep';
        }
    @endphp

    <x-page-header judul="Pemeriksaan · {{ $kunjungan->kodeAntrean() }}" deskripsi="Ruang kerja dokter: rekam medis, resep, tindak lanjut, dan surat keterangan.">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="arrow-left" class="size-4"></i>Kunjungan</a>
            <a href="{{ route('pasien.riwayat-medis', $kunjungan->pasien) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="history" class="size-4"></i>Riwayat Pasien</a>

            @if ($status === 'dipanggil')
                <form method="POST" action="{{ route('kunjungan.mulai-konsultasi', $kunjungan) }}">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-teal-deep"><i data-lucide="play" class="size-4"></i>Mulai Konsultasi</button>
                </form>
            @endif
            @if ($status === 'sedang_konsultasi')
                <form method="POST" action="{{ route('kunjungan.selesai', $kunjungan) }}" data-confirm data-confirm-title="Selesaikan kunjungan" data-confirm-message="Pastikan seluruh pencatatan telah selesai. Kunjungan akan ditandai selesai." data-confirm-label="Ya, selesaikan">
                    @csrf
                    <button class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm hover:bg-emerald-700"><i data-lucide="check-circle-2" class="size-4"></i>Selesaikan</button>
                </form>
            @endif
        </div>
    </x-page-header>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-teal-solid text-[14px] font-bold text-white">{{ collect(explode(' ', $kunjungan->pasien->nama_lengkap))->take(2)->map(fn ($b) => mb_strtoupper(mb_substr($b, 0, 1)))->implode('') }}</span>
                <div class="min-w-0">
                    <a href="{{ route('pasien.show', $kunjungan->pasien) }}" class="block truncate text-[15px] font-semibold text-slate-800 hover:text-teal-700 hover:underline">{{ $kunjungan->pasien->nama_lengkap }}</a>
                    <p class="mt-0.5 text-[11.5px] text-slate-400">
                        {{ $kunjungan->pasien->nomor_rekam_medis }}
                        · {{ $kunjungan->pasien->jenis_kelamin?->label() ?? '-' }}
                        @if ($kunjungan->pasien->usia() !== null) · {{ $kunjungan->pasien->usia() }} tahun @endif
                        · dr. {{ Str::before($dokterNama, 'dr. ') }}
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <x-status-kunjungan :status="$kunjungan->status_kunjungan" />
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700 ring-1 ring-inset ring-amber-200"><i data-lucide="sticky-note" class="size-3.5"></i>{{ Str::limit($kunjungan->keluhan_awal ?? 'Tanpa keluhan awal', 54) }}</span>
            </div>
        </div>
    </section>

    <div class="mt-4 flex flex-wrap items-center gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm" role="tablist" aria-label="Bagian pemeriksaan" data-pemeriksaan-tabs data-tab-awal="{{ $tabAwal }}">
        @foreach ([
            ['kunci' => 'riwayat', 'label' => 'Riwayat Medis', 'ikon' => 'history'],
            ['kunci' => 'rme', 'label' => 'Pemeriksaan (RME)', 'ikon' => 'heart-pulse'],
            ['kunci' => 'resep', 'label' => 'Resep Obat', 'ikon' => 'pill'],
            ['kunci' => 'surat', 'label' => 'Surat Sakit', 'ikon' => 'file-text'],
        ] as $tab)
            <button type="button" data-tab="{{ $tab['kunci'] }}" role="tab" class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-[12px] font-semibold text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 aria-selected:bg-teal-solid aria-selected:text-white" aria-selected="{{ $tabAwal === $tab['kunci'] ? 'true' : 'false' }}">
                <i data-lucide="{{ $tab['ikon'] }}" class="size-3.5"></i>{{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- ============ RIWAYAT ============ --}}
    <section data-panel="riwayat" class="mt-4 {{ $tabAwal === 'riwayat' ? '' : 'hidden' }}">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Riwayat pemeriksaan sebelumnya</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">Rekam medis dari kunjungan sebelumnya untuk pasien ini.</p>
                </div>
                <a href="{{ route('pasien.riwayat-medis', $kunjungan->pasien) }}" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 hover:underline">Lihat semua <i data-lucide="arrow-right" class="size-3.5"></i></a>
            </div>

            @if ($riwayat->isEmpty())
                <x-empty-state icon="history" title="Belum ada riwayat" description="Ini kunjungan pertama pasien atau belum ada catatan medis sebelumnya." />
            @else
                <ul class="divide-y divide-slate-50">
                    @foreach ($riwayat as $item)
                        @php $diagnosaUtama = $item->diagnosa->firstWhere('jenis_diagnosa', \App\Enums\JenisDiagnosa::Utama) ?? $item->diagnosa->first(); @endphp
                        <li class="flex flex-col gap-2 px-5 py-4 transition-colors hover:bg-slate-50/60 sm:flex-row sm:items-center">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-[11px] font-bold text-teal-700">{{ $item->kunjungan->tanggal_kunjungan->translatedFormat('d/m') }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[13px] font-semibold text-slate-800">{{ $diagnosaUtama?->nama_diagnosa ?? 'Tanpa diagnosa tercatat' }}</span>
                                <span class="mt-0.5 block text-[11px] text-slate-400">{{ $item->kunjungan->tanggal_kunjungan->translatedFormat('j F Y') }} · dr. {{ $item->dokter?->pengguna?->nama_lengkap ?? '-' }}</span>
                            </span>
                            <x-status-resep :status="$item->status_rekam_medis" />
                            <a href="{{ route('rekam-medis.show', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-[11.5px] font-semibold text-slate-600 transition-colors hover:border-teal-300 hover:text-teal-700"><i data-lucide="eye" class="size-3.5"></i>Lihat</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- ============ RME ============ --}}
    <section data-panel="rme" class="mt-4 {{ $tabAwal === 'rme' ? '' : 'hidden' }}">
        @if ($rm === null)
            <form method="POST" action="{{ route('rekam-medis.store', $kunjungan) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                @csrf
                <input type="hidden" name="konteks" value="pemeriksaan">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-teal-50 text-teal-600"><i data-lucide="heart-pulse" class="size-4"></i></span>
                    <div>
                        <h2 class="text-[13.5px] font-semibold text-slate-800">Catatan rekam medis</h2>
                        <p class="text-[11px] text-slate-400">Belum ada catatan. Isi data pemeriksaan pasien.</p>
                    </div>
                </div>
                @include('rekam-medis._form', ['rekamMedis' => null])
                <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep"><i data-lucide="save" class="size-4"></i>Simpan Draf Pemeriksaan</button>
                </div>
            </form>
        @elseif ($rm->status_rekam_medis?->value === 'draf')
            <form method="POST" action="{{ route('rekam-medis.update', $rm) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="konteks" value="pemeriksaan">
                <div class="mb-4 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i data-lucide="heart-pulse" class="size-4"></i></span>
                        <div>
                            <h2 class="text-[13.5px] font-semibold text-slate-800">Catatan rekam medis (draf)</h2>
                            <p class="text-[11px] text-slate-400">Perbarui lalu finalisasi setelah pemeriksaan selesai.</p>
                        </div>
                    </div>
                    <x-status-resep :status="$rm->status_rekam_medis" />
                </div>
                @include('rekam-medis._form', ['rekamMedis' => $rm])
                <div class="mt-6 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-5">
                    <button type="button" onclick="document.getElementById('form-finalisasi-rme').requestSubmit()" class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-[12.5px] font-semibold text-emerald-700 hover:bg-emerald-100"><i data-lucide="badge-check" class="size-4"></i>Finalisasi Catatan</button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep"><i data-lucide="save" class="size-4"></i>Simpan Perubahan</button>
                </div>
            </form>
            <form id="form-finalisasi-rme" method="POST" action="{{ route('rekam-medis.finalisasi', $rm) }}" class="hidden" data-confirm data-confirm-title="Finalisasi catatan" data-confirm-message="Catatan yang difinalisasi tidak dapat diubah lagi. Lanjutkan?" data-confirm-label="Ya, finalisasi">
                @csrf
            </form>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600"><i data-lucide="badge-check" class="size-4"></i></span>
                        <h2 class="text-[13.5px] font-semibold text-slate-800">Catatan rekam medis</h2>
                    </div>
                    <x-status-resep :status="$rm->status_rekam_medis" />
                </div>
                <div class="mt-4 space-y-4">
                    @if ($rm->tanda_vital)
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-6">
                            @foreach (['tekanan_darah' => 'TD', 'suhu' => 'Suhu', 'nadi' => 'Nadi', 'pernapasan' => 'RR', 'berat_badan' => 'BB', 'tinggi_badan' => 'TB'] as $kunci => $label)
                                @if (! empty($rm->tanda_vital[$kunci]))
                                    <div class="rounded-lg bg-slate-50 px-3 py-2"><p class="text-[10px] text-slate-400">{{ $label }}</p><p class="text-[12.5px] font-semibold text-slate-700">{{ $rm->tanda_vital[$kunci] }}</p></div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[.06em] text-slate-400">Hasil pemeriksaan</p>
                        <p class="mt-1 whitespace-pre-line text-[13px] leading-6 text-slate-600">{{ $rm->hasil_pemeriksaan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[.06em] text-slate-400">Diagnosa</p>
                        @forelse ($rm->diagnosa as $d)
                            <p class="mt-1 text-[13px] text-slate-700">{{ $d->nama_diagnosa }} @if ($d->kode_diagnosa)<span class="font-mono text-[11px] text-slate-400">({{ $d->kode_diagnosa }})</span>@endif</p>
                        @empty
                            <p class="mt-1 text-[12.5px] text-slate-400">Tidak ada diagnosa.</p>
                        @endforelse
                    </div>
                    @if ($rm->tindakanMedis->isNotEmpty())
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[.06em] text-slate-400">Tindakan</p>
                            @foreach ($rm->tindakanMedis as $t)
                                <p class="mt-1 text-[13px] text-slate-700">{{ $t->nama_tindakan }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex justify-end"><a href="{{ route('rekam-medis.show', $rm) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="eye" class="size-4"></i>Buka rekam medis</a></div>
                </div>
            </div>
        @endif
    </section>

    {{-- ============ RESEP ============ --}}
    <section data-panel="resep" class="mt-4 {{ $tabAwal === 'resep' ? '' : 'hidden' }}">
        @if ($resep === null)
            <form method="POST" action="{{ route('resep.store', $kunjungan) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                @csrf
                <input type="hidden" name="konteks" value="pemeriksaan">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="pill" class="size-4"></i></span>
                    <div>
                        <h2 class="text-[13.5px] font-semibold text-slate-800">Resep obat</h2>
                        <p class="text-[11px] text-slate-400">Tambahkan 1 atau beberapa obat dalam satu resep.</p>
                    </div>
                </div>
                @include('resep._form', ['resep' => null, 'obat' => $obat])
                <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-violet-700"><i data-lucide="save" class="size-4"></i>Simpan Draf Resep</button>
                </div>
            </form>
        @elseif ($resep->status_resep?->value === 'draf')
            <form method="POST" action="{{ route('resep.update', $resep) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="konteks" value="pemeriksaan">
                <div class="mb-4 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="pill" class="size-4"></i></span>
                        <div>
                            <h2 class="text-[13.5px] font-semibold text-slate-800">Resep {{ $resep->nomor_resep }} (draf)</h2>
                            <p class="text-[11px] text-slate-400">Perbarui item lalu finalisasi untuk memicu tindak lanjut.</p>
                        </div>
                    </div>
                    <x-status-resep :status="$resep->status_resep" />
                </div>
                @include('resep._form', ['resep' => $resep, 'obat' => $obat])
                <div class="mt-6 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-5">
                    <button type="button" onclick="document.getElementById('form-finalisasi-resep').requestSubmit()" class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-[12.5px] font-semibold text-emerald-700 hover:bg-emerald-100"><i data-lucide="check-circle-2" class="size-4"></i>Finalisasi Resep</button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-violet-700"><i data-lucide="save" class="size-4"></i>Simpan Perubahan</button>
                </div>
            </form>
            <form id="form-finalisasi-resep" method="POST" action="{{ route('resep.finalisasi', $resep) }}" class="hidden" data-confirm data-confirm-title="Finalisasi resep" data-confirm-message="Resep akan disahkan dan tugas tindak lanjut obat dikirim ke resepsionis. Lanjutkan?" data-confirm-label="Ya, finalisasi">
                @csrf
            </form>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex size-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="pill" class="size-4"></i></span>
                        <div>
                            <h2 class="text-[13.5px] font-semibold text-slate-800">Resep {{ $resep->nomor_resep }}</h2>
                            <p class="text-[11px] text-slate-400">{{ $resep->rincianResep->count() }} item obat</p>
                        </div>
                    </div>
                    <x-status-resep :status="$resep->status_resep" />
                </div>

                <ul class="mt-4 divide-y divide-slate-100 rounded-xl border border-slate-100">
                    @foreach ($resep->rincianResep as $r)
                        <li class="flex items-start gap-3 px-4 py-3">
                            <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="pill" class="size-4"></i></span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[12.5px] font-semibold text-slate-700">{{ $r->nama_obat_saat_diresepkan }}</span>
                                <span class="mt-0.5 block text-[11px] text-slate-400">{{ collect([$r->dosis, $r->frekuensi_pemberian, $r->cara_pemberian])->filter()->implode(' · ') ?: '-' }}</span>
                            </span>
                            <span class="shrink-0 rounded-md bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">{{ $r->jumlah_diresepkan }} {{ $r->satuan_jumlah }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-50 px-2.5 py-1 text-[11px] font-semibold text-teal-700 ring-1 ring-inset ring-teal-200"><i data-lucide="package-check" class="size-3.5"></i>Tindak lanjut: {{ $resep->pemenuhanResep?->status_pemenuhan?->label() ?? 'Belum dibuat' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('resep.show', $resep) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-[12px] font-semibold text-slate-600 hover:bg-slate-50"><i data-lucide="eye" class="size-4"></i>Detail</a>
                        <a href="{{ route('pemenuhan.show', $resep->pemenuhanResep) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-solid px-3.5 py-2 text-[12px] font-semibold text-white hover:bg-teal-deep"><i data-lucide="package-check" class="size-4"></i>Buka Tindak Lanjut</a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- ============ SURAT SAKIT ============ --}}
    <section data-panel="surat" class="mt-4 {{ $tabAwal === 'surat' ? '' : 'hidden' }}">
        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
            <form method="POST" action="{{ route('surat-sakit.store', $kunjungan) }}" class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                @csrf
                <input type="hidden" name="konteks" value="pemeriksaan">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600"><i data-lucide="file-text" class="size-4"></i></span>
                    <div>
                        <h2 class="text-[13.5px] font-semibold text-slate-800">Buat surat keterangan sakit</h2>
                        <p class="text-[11px] text-slate-400">Surat resmi untuk keperluan izin pasien.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tanggal_mulai" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Mulai sakit <span class="text-rose-500">*</span></label>
                        <input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai', today()->format('Y-m-d')) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        @error('tanggal_mulai')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Selesai sakit <span class="text-rose-500">*</span></label>
                        <input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai', today()->addDays(2)->format('Y-m-d')) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        @error('tanggal_selesai')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="diagnosa_ringkas" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Diagnosa ringkas</label>
                    <input id="diagnosa_ringkas" name="diagnosa_ringkas" type="text" value="{{ old('diagnosa_ringkas', $rm?->diagnosa->first()?->nama_diagnosa) }}" placeholder="Contoh: Demam akut" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    @error('diagnosa_ringkas')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label for="keterangan" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6 flex items-center justify-end border-t border-slate-100 pt-5">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-sky-700"><i data-lucide="printer" class="size-4"></i>Buat &amp; Cetak Surat</button>
                </div>
            </form>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-[13.5px] font-semibold text-slate-800">Surat terbit</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">{{ $kunjungan->suratKeteranganSakit->count() }} surat untuk kunjungan ini.</p>
                </div>
                @if ($kunjungan->suratKeteranganSakit->isEmpty())
                    <x-empty-state icon="file-text" title="Belum ada surat" description="Surat keterangan sakit yang dibuat akan tampil di sini." />
                @else
                    <ul class="divide-y divide-slate-50">
                        @foreach ($kunjungan->suratKeteranganSakit as $surat)
                            <li class="flex items-center gap-3 px-5 py-3.5">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-600"><i data-lucide="file-text" class="size-4"></i></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block font-mono text-[12px] font-semibold text-slate-700">{{ $surat->nomor_surat }}</span>
                                    <span class="mt-0.5 block text-[11px] text-slate-400">{{ $surat->tanggal_mulai->translatedFormat('j M') }} – {{ $surat->tanggal_selesai->translatedFormat('j M Y') }} · {{ $surat->jumlahHari() }} hari</span>
                                </span>
                                <a href="{{ route('surat-sakit.cetak', ['suratKeteranganSakit' => $surat, 'konteks' => 'pemeriksaan']) }}" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-sky-50 hover:text-sky-600" title="Cetak"><i data-lucide="printer" class="size-4"></i></a>
                                <form method="POST" action="{{ route('surat-sakit.destroy', $surat) }}" data-confirm data-confirm-title="Hapus surat" data-confirm-message="Surat {{ $surat->nomor_surat }} akan dihapus." data-confirm-label="Ya, hapus">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600" title="Hapus"><i data-lucide="trash-2" class="size-4"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const wadah = document.querySelector('[data-pemeriksaan-tabs]');
            if (!wadah) return;

            const tombol = wadah.querySelectorAll('[data-tab]');
            const panel = document.querySelectorAll('[data-panel]');

            function aktifkan(nama) {
                tombol.forEach((t) => t.setAttribute('aria-selected', String(t.dataset.tab === nama)));
                panel.forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== nama));
            }

            tombol.forEach((t) => t.addEventListener('click', () => aktifkan(t.dataset.tab)));
            aktifkan(wadah.dataset.tabAwal ?? 'rme');
        })();
    </script>
@endpush
