@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <x-page-header judul="Pengaturan" deskripsi="Konfigurasi identitas praktik, logo sidebar, dan konten landing page." />

    <form method="POST" action="{{ route('pengaturan.update') }}" class="grid gap-4 lg:grid-cols-[minmax(0,1.7fr)_minmax(280px,1fr)]">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-teal-50 text-teal-600"><i data-lucide="building-2" class="size-4"></i></span>
                    <div>
                        <h2 class="text-[13.5px] font-semibold text-slate-800">Identitas praktik</h2>
                        <p class="text-[11px] text-slate-400">Ditampilkan pada landing page, tiket antrean, dan dokumen.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="nama_praktik" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nama praktik <span class="text-rose-500">*</span></label>
                        <input id="nama_praktik" name="nama_praktik" type="text" value="{{ old('nama_praktik', $pengaturan['nama_praktik']) }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        @error('nama_praktik')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="alamat_praktik" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Alamat praktik</label>
                        <textarea id="alamat_praktik" name="alamat_praktik" rows="2" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('alamat_praktik', $pengaturan['alamat_praktik']) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="nomor_telepon" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nomor telepon</label>
                            <input id="nomor_telepon" name="nomor_telepon" type="text" value="{{ old('nomor_telepon', $pengaturan['nomor_telepon']) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div>
                            <label for="jam_operasional" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Jam operasional</label>
                            <input id="jam_operasional" name="jam_operasional" type="text" value="{{ old('jam_operasional', $pengaturan['jam_operasional']) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i data-lucide="shapes" class="size-4"></i></span>
                    <div>
                        <h2 class="text-[13.5px] font-semibold text-slate-800">Logo sidebar</h2>
                        <p class="text-[11px] text-slate-400">Ikon dan nama yang tampil di pojok sidebar.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="logo_ikon" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Ikon logo</label>
                        <select id="logo_ikon" name="logo_ikon" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            @foreach ([
                                'activity' => 'Aktivitas (garis denyut)',
                                'stethoscope' => 'Stetoskop',
                                'heart-pulse' => 'Detak jantung',
                                'hospital' => 'Rumah sakit',
                                'microscope' => 'Mikroskop',
                                'shield-plus' => 'Perisai kesehatan',
                            ] as $nilai => $label)
                                <option value="{{ $nilai }}" @selected(old('logo_ikon', $pengaturan['logo_ikon']) === $nilai)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('logo_ikon')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="logo_nama" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nama logo <span class="text-rose-500">*</span></label>
                            <input id="logo_nama" name="logo_nama" type="text" value="{{ old('logo_nama', $pengaturan['logo_nama']) }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            @error('logo_nama')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="logo_subjudul" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Subjudul logo</label>
                            <input id="logo_subjudul" name="logo_subjudul" type="text" value="{{ old('logo_subjudul', $pengaturan['logo_subjudul']) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                <div class="mb-4 flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600"><i data-lucide="layout-template" class="size-4"></i></span>
                    <div>
                        <h2 class="text-[13.5px] font-semibold text-slate-800">Landing page</h2>
                        <p class="text-[11px] text-slate-400">Teks sambutan pada halaman company profile.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="tagline" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Tagline</label>
                        <input id="tagline" name="tagline" type="text" value="{{ old('tagline', $pengaturan['tagline']) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label for="deskripsi_singkat" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Deskripsi singkat</label>
                        <textarea id="deskripsi_singkat" name="deskripsi_singkat" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('deskripsi_singkat', $pengaturan['deskripsi_singkat']) }}</textarea>
                    </div>
                </div>
            </section>

            <div class="flex items-center justify-end">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-4 py-2.5 text-[12.5px] font-semibold text-white shadow-sm transition-colors hover:bg-teal-deep"><i data-lucide="save" class="size-4"></i>Simpan Pengaturan</button>
            </div>
        </div>

        <aside class="h-fit lg:sticky lg:top-24">
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="mb-3 text-[11px] font-semibold uppercase tracking-[.08em] text-slate-400">Pratinjau logo</p>
                <div class="flex items-center gap-3 rounded-xl bg-teal-solid px-4 py-4 text-white">
                    <span data-preview-ikon class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-teal-100 ring-1 ring-white/15">
                        <i data-lucide="{{ $pengaturan['logo_ikon'] }}" class="size-[21px]"></i>
                    </span>
                    <span class="min-w-0 leading-tight">
                        <span data-preview-nama class="block truncate text-[13px] font-bold text-white">{{ $pengaturan['logo_nama'] }}</span>
                        <span data-preview-subjudul class="mt-0.5 block truncate text-[10.5px] font-medium text-teal-200/80">{{ $pengaturan['logo_subjudul'] }}</span>
                    </span>
                </div>
                <p class="mt-3 text-[11px] leading-5 text-slate-400">Perubahan akan tampil di sidebar seluruh halaman setelah disimpan.</p>
            </div>

            <div class="mt-3 rounded-2xl border border-slate-200 bg-white p-4">
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[.08em] text-slate-400">Tautan cepat</p>
                <a href="{{ route('beranda') }}" target="_blank" rel="noopener" class="flex items-center justify-between rounded-lg border border-slate-200 px-3.5 py-2.5 text-[12px] font-semibold text-slate-600 hover:border-teal-300 hover:text-teal-700">
                    <span class="flex items-center gap-2"><i data-lucide="external-link" class="size-4"></i>Lihat landing page</span>
                    <i data-lucide="arrow-up-right" class="size-4"></i>
                </a>
            </div>
        </aside>
    </form>
@endsection

@push('scripts')
    <script>
        (() => {
            const pilihIkon = document.getElementById('logo_ikon');
            const inputNama = document.getElementById('logo_nama');
            const inputSubjudul = document.getElementById('logo_subjudul');
            const previewIkon = document.querySelector('[data-preview-ikon]');
            const previewNama = document.querySelector('[data-preview-nama]');
            const previewSubjudul = document.querySelector('[data-preview-subjudul]');

            pilihIkon?.addEventListener('change', () => {
                previewIkon.innerHTML = `<i data-lucide="${pilihIkon.value}" class="size-[21px]"></i>`;
                window.renderLucide(previewIkon);
            });

            inputNama?.addEventListener('input', () => {
                previewNama.textContent = inputNama.value || 'Praktik Dokter';
            });

            inputSubjudul?.addEventListener('input', () => {
                previewSubjudul.textContent = inputSubjudul.value || '';
            });
        })();
    </script>
@endpush
