@php
    $rincianDaftar = $resep?->rincianResep ?? [null];
@endphp

<div>
    <label for="catatan_resep" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Catatan resep</label>
    <textarea id="catatan_resep" name="catatan_resep" rows="2" placeholder="Catatan resep (opsional)…" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('catatan_resep', $resep?->catatan_resep) }}</textarea>
    @error('catatan_resep')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="mt-6">
    <h3 class="mb-3 text-[12.5px] font-semibold uppercase tracking-[.06em] text-slate-500">Rincian obat</h3>
    <div data-repeater data-repeater-prefix="rincian">
        <div data-repeater-daftar class="space-y-3">
            @foreach ($rincianDaftar as $rincian)
                <div data-repeater-row class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Obat</label>
                            <select data-repeater-field="id_obat" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                <x-pilihan-obat :obat="$obat" :terpilih="$rincian?->id_obat" />
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Dosis</label>
                            <input type="text" data-repeater-field="dosis" value="{{ $rincian?->dosis }}" placeholder="Contoh: 1 x 500 mg" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Jumlah</label>
                            <input type="number" min="1" data-repeater-field="jumlah_diresepkan" value="{{ $rincian?->jumlah_diresepkan ?? 1 }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Frekuensi</label>
                            <input type="text" data-repeater-field="frekuensi_pemberian" value="{{ $rincian?->frekuensi_pemberian }}" placeholder="Contoh: 3x sehari" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Cara pemberian</label>
                            <input type="text" data-repeater-field="cara_pemberian" value="{{ $rincian?->cara_pemberian }}" placeholder="Contoh: diminum" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Lama (hari)</label>
                            <input type="number" min="1" data-repeater-field="lama_penggunaan_hari" value="{{ $rincian?->lama_penggunaan_hari }}" placeholder="Contoh: 5" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Satuan</label>
                            <input type="text" data-repeater-field="satuan_jumlah" value="{{ $rincian?->satuan_jumlah }}" placeholder="tablet / kapsul" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-[11px] font-semibold text-slate-500">Petunjuk penggunaan</label>
                            <input type="text" data-repeater-field="petunjuk_penggunaan" value="{{ $rincian?->petunjuk_penggunaan }}" placeholder="Contoh: diminum setelah makan" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div class="flex items-end justify-end sm:col-span-2 lg:col-span-4">
                            <button type="button" data-repeater-hapus class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-[11.5px] font-semibold text-rose-500 transition-colors hover:bg-rose-50"><i data-lucide="trash-2" class="size-3.5"></i>Hapus obat</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <template>
            <div data-repeater-row class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Obat</label>
                        <select data-repeater-field="id_obat" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            <x-pilihan-obat :obat="$obat" />
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Dosis</label>
                        <input type="text" data-repeater-field="dosis" placeholder="Contoh: 1 x 500 mg" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Jumlah</label>
                        <input type="number" min="1" data-repeater-field="jumlah_diresepkan" value="1" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Frekuensi</label>
                        <input type="text" data-repeater-field="frekuensi_pemberian" placeholder="Contoh: 3x sehari" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Cara pemberian</label>
                        <input type="text" data-repeater-field="cara_pemberian" placeholder="Contoh: diminum" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Lama (hari)</label>
                        <input type="number" min="1" data-repeater-field="lama_penggunaan_hari" placeholder="Contoh: 5" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Satuan</label>
                        <input type="text" data-repeater-field="satuan_jumlah" placeholder="tablet / kapsul" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Petunjuk penggunaan</label>
                        <input type="text" data-repeater-field="petunjuk_penggunaan" placeholder="Contoh: diminum setelah makan" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[12.5px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div class="flex items-end justify-end sm:col-span-2 lg:col-span-4">
                        <button type="button" data-repeater-hapus class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-[11.5px] font-semibold text-rose-500 transition-colors hover:bg-rose-50"><i data-lucide="trash-2" class="size-3.5"></i>Hapus obat</button>
                    </div>
                </div>
            </div>
        </template>

        <button type="button" data-repeater-tambah class="mt-3 inline-flex items-center gap-1.5 rounded-lg border border-dashed border-slate-300 px-3.5 py-2 text-[11.5px] font-semibold text-slate-500 transition-colors hover:border-violet-400 hover:text-violet-700">
            <i data-lucide="plus" class="size-3.5"></i>Tambah obat
        </button>
    </div>
    @error('rincian')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
</div>
