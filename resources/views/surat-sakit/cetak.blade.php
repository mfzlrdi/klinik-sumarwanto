<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Keterangan Sakit · {{ $surat->nomor_surat }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css'])
    <style>
        :root { font-family: 'Instrument Sans', sans-serif; }
        body { background: #f1f4f3; }
        .lembar { max-width: 720px; margin: 0 auto; }
        @media print { body { background: #fff; } .lembar { box-shadow: none; } }
    </style>
</head>

<body class="min-h-screen py-10 antialiased text-slate-800">
    <div class="lembar overflow-hidden rounded-2xl bg-white shadow-[0_18px_50px_rgba(15,40,35,.14)]">
        <div class="border-b-2 border-teal-solid px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="flex size-12 items-center justify-center rounded-xl bg-teal-solid text-white"><i data-lucide="activity" class="size-6"></i></span>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-teal-600">Praktik Dokter Umum</p>
                        <h1 class="mt-0.5 text-[19px] font-bold text-slate-800">dr. Sumarwanto</h1>
                    </div>
                </div>
                <div class="text-right text-[11px] text-slate-400">
                    <p>{{ now()->translatedFormat('j F Y') }}</p>
                    <p class="mt-0.5 font-mono">{{ $surat->nomor_surat }}</p>
                </div>
            </div>
        </div>

        <div class="px-8 py-8">
            <h2 class="text-center text-[17px] font-bold uppercase tracking-[.08em] text-slate-800">Surat Keterangan Sakit</h2>
            <p class="mt-1 text-center text-[12px] text-slate-500">Nomor: {{ $surat->nomor_surat }}</p>

            <div class="mt-8 text-[13px] leading-7 text-slate-700">
                <p>Yang bertanda tangan di bawah ini, dokter pemeriksa pada Praktik Dokter Umum dr. Sumarwanto, menerangkan bahwa:</p>

                <table class="mt-4 w-full text-[13px]">
                    <tbody>
                        <tr><td class="w-40 py-1 align-top text-slate-400">Nama</td><td class="py-1 font-semibold">: {{ $surat->kunjungan->pasien->nama_lengkap }}</td></tr>
                        <tr><td class="py-1 align-top text-slate-400">No. Rekam Medis</td><td class="py-1 font-mono">: {{ $surat->kunjungan->pasien->nomor_rekam_medis }}</td></tr>
                        <tr><td class="py-1 align-top text-slate-400">Tanggal Lahir</td><td class="py-1">: {{ $surat->kunjungan->pasien->tanggal_lahir?->translatedFormat('j F Y') ?? '-' }}</td></tr>
                        <tr><td class="py-1 align-top text-slate-400">Jenis Kelamin</td><td class="py-1">: {{ $surat->kunjungan->pasien->jenis_kelamin?->label() ?? '-' }}</td></tr>
                    </tbody>
                </table>

                <p class="mt-5">Berdasarkan hasil pemeriksaan, yang bersangkutan dinyatakan <strong>sakit</strong> dan memerlukan istirahat selama <strong>{{ $surat->jumlahHari() }} hari</strong>, terhitung sejak
                    <strong>{{ $surat->tanggal_mulai->translatedFormat('j F Y') }}</strong> sampai dengan <strong>{{ $surat->tanggal_selesai->translatedFormat('j F Y') }}</strong>.</p>

                @if ($surat->diagnosa_ringkas)
                    <p class="mt-3">Diagnosa ringkas: <strong>{{ $surat->diagnosa_ringkas }}</strong>.</p>
                @endif

                @if ($surat->keterangan)
                    <p class="mt-3">{{ $surat->keterangan }}</p>
                @endif

                <p class="mt-5">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
            </div>

            <div class="mt-10 flex justify-end">
                <div class="text-center">
                    <p class="text-[12px] text-slate-500">{{ now()->translatedFormat('j F Y') }}</p>
                    <p class="mt-1 text-[12px] font-semibold text-slate-700">Dokter Pemeriksa</p>
                    <div class="mt-16 border-t border-slate-300 pt-1 text-[11px] font-semibold text-slate-700">dr. {{ $surat->dokter?->pengguna?->nama_lengkap ?? 'Sumarwanto' }}</div>
                    <p class="mt-0.5 text-[10px] text-slate-400">{{ $surat->dokter?->nomor_sip ? 'SIP: '.$surat->dokter->nomor_sip : '' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="no-print mt-6 text-center">
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-teal-solid px-5 py-2.5 text-[12.5px] font-semibold text-white shadow-sm hover:bg-teal-deep">Cetak Surat</button>
        <a href="{{ $konteks === 'pemeriksaan' ? route('pemeriksaan.show', $surat->id_kunjungan) : route('kunjungan.show', $surat->id_kunjungan) }}" class="ml-2 inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-[12.5px] font-semibold text-slate-600 hover:bg-slate-50">Selesai</a>
    </div>
</body>

</html>
