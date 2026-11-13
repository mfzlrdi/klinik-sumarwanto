<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Antrean {{ $kunjungan->kodeAntrean() }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css'])
    <style>
        :root { font-family: 'Instrument Sans', sans-serif; }
        body { background: #f1f4f3; }
        .tiket { width: 320px; margin: 0 auto; }
    </style>
</head>
<body class="min-h-screen py-10 antialiased">
    <div class="tiket overflow-hidden rounded-2xl bg-white shadow-[0_18px_50px_rgba(15,40,35,.14)]">
        <div class="bg-teal-solid px-6 py-5 text-center text-white">
            <p class="text-[11px] font-medium uppercase tracking-[.16em] text-teal-200">Praktik Dokter Umum</p>
            <h1 class="mt-1 text-[17px] font-bold">dr. Sumarwanto</h1>
        </div>

        <div class="px-6 py-6">
            <div class="text-center">
                <p class="text-[11px] font-medium uppercase tracking-[.14em] text-slate-400">Nomor Antrean</p>
                <p class="mt-2 text-[54px] font-bold leading-none tracking-tight text-teal-700">{{ $kunjungan->kodeAntrean() }}</p>
            </div>

            <div class="mt-6 space-y-3 border-t border-dashed border-slate-200 pt-5">
                <div class="flex justify-between text-[12.5px]">
                    <span class="text-slate-400">Pasien</span>
                    <span class="font-semibold text-slate-700">{{ $kunjungan->pasien->nama_lengkap }}</span>
                </div>
                <div class="flex justify-between text-[12.5px]">
                    <span class="text-slate-400">No. Rekam Medis</span>
                    <span class="font-mono font-semibold text-slate-700">{{ $kunjungan->pasien->nomor_rekam_medis }}</span>
                </div>
                <div class="flex justify-between text-[12.5px]">
                    <span class="text-slate-400">Tanggal</span>
                    <span class="font-semibold text-slate-700">{{ $kunjungan->tanggal_kunjungan->translatedFormat('l, j F Y') }}</span>
                </div>
            </div>

            <div class="mt-6 rounded-xl bg-slate-50 px-4 py-3 text-center text-[11px] leading-5 text-slate-500">
                Mohon menunggu hingga nomor Anda dipanggil. Simpan tiket ini sebagai bukti antrean.
            </div>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/60 px-6 py-3 text-center text-[10px] text-slate-400">
            {{ now()->translatedFormat('j F Y H:i') }} · Sistem Informasi Praktik Dokter
        </div>
    </div>

    <div class="no-print mt-6 text-center">
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-5 py-2.5 text-[12.5px] font-semibold text-white shadow-sm hover:bg-teal-deep">
            Cetak Tiket
        </button>
        <a href="{{ route('kunjungan.show', $kunjungan) }}" class="ml-2 inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-[12.5px] font-semibold text-slate-600 hover:bg-slate-50">
            Selesai
        </a>
    </div>
</body>
</html>
