@php
    $pasien = $pasien ?? null;
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="nama_lengkap" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nama lengkap <span class="text-rose-500">*</span></label>
        <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap', $pasien?->nama_lengkap) }}" required placeholder="Nama lengkap pasien" @class(['w-full rounded-lg border bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2', 'border-slate-200 focus:border-teal-500 focus:ring-teal-500/20' => ! $errors->has('nama_lengkap'), 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20' => $errors->has('nama_lengkap')])>
        @error('nama_lengkap')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nomor_identitas" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nomor identitas (NIK)</label>
        <input id="nomor_identitas" name="nomor_identitas" type="text" value="{{ old('nomor_identitas', $pasien?->nomor_identitas) }}" placeholder="Opsional" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
        @error('nomor_identitas')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_lahir" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Tanggal lahir</label>
        <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $pasien?->tanggal_lahir?->format('Y-m-d')) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
        @error('tanggal_lahir')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="jenis_kelamin" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Jenis kelamin</label>
        <select id="jenis_kelamin" name="jenis_kelamin" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
            <option value="">— Pilih —</option>
            <option value="laki_laki" @selected(old('jenis_kelamin', $pasien?->jenis_kelamin?->value) === 'laki_laki')>Laki-laki</option>
            <option value="perempuan" @selected(old('jenis_kelamin', $pasien?->jenis_kelamin?->value) === 'perempuan')>Perempuan</option>
        </select>
        @error('jenis_kelamin')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nomor_telepon" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Nomor telepon</label>
        <input id="nomor_telepon" name="nomor_telepon" type="tel" value="{{ old('nomor_telepon', $pasien?->nomor_telepon) }}" placeholder="Contoh: 0812xxxx" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
        @error('nomor_telepon')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="alamat_email" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Alamat email</label>
        <input id="alamat_email" name="alamat_email" type="email" value="{{ old('alamat_email', $pasien?->alamat_email) }}" placeholder="Opsional" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
        @error('alamat_email')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="alamat" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Alamat</label>
        <textarea id="alamat" name="alamat" rows="2" placeholder="Alamat tempat tinggal" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('alamat', $pasien?->alamat) }}</textarea>
        @error('alamat')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label for="catatan_administratif" class="mb-1.5 block text-[12px] font-semibold text-slate-700">Catatan administratif</label>
        <textarea id="catatan_administratif" name="catatan_administratif" rows="2" placeholder="Catatan non-medis (opsional)" class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-[13px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20">{{ old('catatan_administratif', $pasien?->catatan_administratif) }}</textarea>
        @error('catatan_administratif')<p class="mt-1.5 text-[11px] text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>
