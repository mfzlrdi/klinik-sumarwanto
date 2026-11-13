@php
    $diagnosaDaftar = $rekamMedis?->diagnosa ?? [null];
    $tindakanDaftar = $rekamMedis?->tindakanMedis ?? [null];
@endphp

<div>
    <h3 class="mb-3 text-[12.5px] font-semibold uppercase tracking-[.06em] text-slate-500">Tanda vital</h3>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        @foreach ([
            'tekanan_darah' => ['Tekanan darah', 'Contoh: 120/80', 'text'],
            'suhu' => ['Suhu (°C)', 'Contoh: 36.5', 'number'],
            'nadi' => ['Nadi (/mnt)', 'Contoh: 80', 'number'],
            'pernapasan' => ['Pernapasan', 'Contoh: 20', 'number'],
            'berat_badan' => ['Berat (kg)', 'Contoh: 60', 'number'],
            'tinggi_badan' => ['Tinggi (cm)', 'Contoh: 165', 'number'],
        ] as $kunci => [$label, $placeholder, $tipe])
            <div>
                <label for="tv_{{ $kunci }}" class="mb-1.5 block text-[11.5px] font-semibold text-slate-600">{{ $label }}</label>
                <input id="tv_{{ $kunci }}" name="tanda_vital[{{ $kunci }}]" type="{{ $tipe }}" step="any" value="{{ old('tanda_vital.'.$kunci, $rekamMedis?->tanda_vital[$kunci] ?? '') }}" placeholder="{{ $placeholder }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[12.5px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                @error('tanda_vital.'.$kunci)<p class="mt-1 text-[10.5px] text-rose-600">{{ $message }}</p>@enderror
            </div>
        @endforeach
    </div>
</div>

<div class="mt-6">
    <label for="hasil_pemeriksaan" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Hasil pemeriksaan</label>
    <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="4" placeholder="Uraian hasil pemeriksaan fisik…" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('hasil_pemeriksaan', $rekamMedis?->hasil_pemeriksaan) }}</textarea>
    @error('hasil_pemeriksaan')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="mt-6">
    <h3 class="mb-3 flex items-center justify-between text-[12.5px] font-semibold uppercase tracking-[.06em] text-slate-500">
        <span>Diagnosa</span>
    </h3>
    <div data-repeater data-repeater-prefix="diagnosa">
        <div data-repeater-daftar class="space-y-3">
            @foreach ($diagnosaDaftar as $diagnosa)
                <div data-repeater-row class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 sm:grid-cols-[1fr_140px_120px]">
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Nama diagnosa</label>
                        <input type="text" data-repeater-field="nama_diagnosa" value="{{ $diagnosa?->nama_diagnosa }}" placeholder="Contoh: Hipertensi" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Jenis</label>
                        <select data-repeater-field="jenis_diagnosa" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            <option value="utama" @selected($diagnosa?->jenis_diagnosa?->value === 'utama')>Utama</option>
                            <option value="tambahan" @selected($diagnosa?->jenis_diagnosa?->value === 'tambahan')>Tambahan</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Kode (ICD)</label>
                            <input type="text" data-repeater-field="kode_diagnosa" value="{{ $diagnosa?->kode_diagnosa }}" placeholder="I10" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <button type="button" data-repeater-hapus class="flex size-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-500" title="Hapus diagnosa"><i data-lucide="trash-2" class="size-4"></i></button>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Catatan</label>
                        <input type="text" data-repeater-field="catatan_diagnosa" value="{{ $diagnosa?->catatan_diagnosa }}" placeholder="Catatan tambahan (opsional)" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                </div>
            @endforeach
        </div>

        <template>
            <div data-repeater-row class="grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 sm:grid-cols-[1fr_140px_120px]">
                <div>
                    <label class="mb-1 block text-[11px] font-semibold text-slate-500">Nama diagnosa</label>
                    <input type="text" data-repeater-field="nama_diagnosa" placeholder="Contoh: Hipertensi" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-[11px] font-semibold text-slate-500">Jenis</label>
                    <select data-repeater-field="jenis_diagnosa" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        <option value="utama">Utama</option>
                        <option value="tambahan">Tambahan</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Kode (ICD)</label>
                        <input type="text" data-repeater-field="kode_diagnosa" placeholder="I10" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <button type="button" data-repeater-hapus class="flex size-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-500" title="Hapus diagnosa"><i data-lucide="trash-2" class="size-4"></i></button>
                </div>
                <div class="sm:col-span-3">
                    <label class="mb-1 block text-[11px] font-semibold text-slate-500">Catatan</label>
                    <input type="text" data-repeater-field="catatan_diagnosa" placeholder="Catatan tambahan (opsional)" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
            </div>
        </template>

        <button type="button" data-repeater-tambah class="mt-3 inline-flex items-center gap-1.5 rounded-lg border border-dashed border-slate-300 px-3.5 py-2 text-[11.5px] font-semibold text-slate-500 transition-colors hover:border-teal-400 hover:text-teal-700">
            <i data-lucide="plus" class="size-3.5"></i>Tambah diagnosa
        </button>
    </div>
    @error('diagnosa')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="mt-6">
    <h3 class="mb-3 text-[12.5px] font-semibold uppercase tracking-[.06em] text-slate-500">Tindakan medis</h3>
    <div data-repeater data-repeater-prefix="tindakan">
        <div data-repeater-daftar class="space-y-3">
            @foreach ($tindakanDaftar as $tindakan)
                <div data-repeater-row class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 sm:flex-row sm:items-end">
                    <div class="flex-1">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Nama tindakan</label>
                        <input type="text" data-repeater-field="nama_tindakan" value="{{ $tindakan?->nama_tindakan }}" placeholder="Contoh: Pemberian obat injeksi" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div class="flex-1">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Catatan</label>
                        <input type="text" data-repeater-field="catatan_tindakan" value="{{ $tindakan?->catatan_tindakan }}" placeholder="Catatan tindakan (opsional)" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <button type="button" data-repeater-hapus class="flex size-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-500" title="Hapus tindakan"><i data-lucide="trash-2" class="size-4"></i></button>
                </div>
            @endforeach
        </div>

        <template>
            <div data-repeater-row class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="mb-1 block text-[11px] font-semibold text-slate-500">Nama tindakan</label>
                    <input type="text" data-repeater-field="nama_tindakan" placeholder="Contoh: Pemberian obat injeksi" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div class="flex-1">
                    <label class="mb-1 block text-[11px] font-semibold text-slate-500">Catatan</label>
                    <input type="text" data-repeater-field="catatan_tindakan" placeholder="Catatan tindakan (opsional)" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                </div>
                <button type="button" data-repeater-hapus class="flex size-9 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-500" title="Hapus tindakan"><i data-lucide="trash-2" class="size-4"></i></button>
            </div>
        </template>

        <button type="button" data-repeater-tambah class="mt-3 inline-flex items-center gap-1.5 rounded-lg border border-dashed border-slate-300 px-3.5 py-2 text-[11.5px] font-semibold text-slate-500 transition-colors hover:border-teal-400 hover:text-teal-700">
            <i data-lucide="plus" class="size-3.5"></i>Tambah tindakan
        </button>
    </div>
</div>

<div class="mt-6">
    <label for="catatan_klinis" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Catatan klinis</label>
    <textarea id="catatan_klinis" name="catatan_klinis" rows="3" placeholder="Catatan klinis tambahan…" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('catatan_klinis', $rekamMedis?->catatan_klinis) }}</textarea>
    @error('catatan_klinis')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
</div>
