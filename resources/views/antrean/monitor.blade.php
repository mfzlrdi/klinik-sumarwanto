<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#062e2b">
    <title>Monitor Antrean · Praktik Dokter dr. Sumarwanto</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { font-family: 'Instrument Sans', sans-serif; }
        body { background: #062e2b; }
        @keyframes monitor-pulse {
            0%, 100% { opacity: .55; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        .monitor-pulse { animation: monitor-pulse 2s ease-in-out infinite; }
        @keyframes ticker { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .monitor-ticker { animation: ticker 28s linear infinite; }
    </style>
</head>

<body class="min-h-screen text-white antialiased" data-monitor-antrean data-url="{{ route('antrean.monitor.data') }}">
    <div class="mx-auto flex min-h-screen max-w-[1920px] flex-col px-4 py-5 sm:px-8 xl:px-12">

        <header class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-5">
            <div class="flex items-center gap-4">
                <span class="flex size-12 items-center justify-center rounded-2xl bg-teal-solid text-teal-50 ring-1 ring-white/15">
                    <i data-lucide="activity" class="size-6"></i>
                </span>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[.22em] text-teal-300/70">Praktik Dokter Umum</p>
                    <h1 class="text-xl font-bold tracking-tight text-white sm:text-2xl">dr. Sumarwanto</h1>
                </div>
            </div>

            <div class="flex items-center gap-4 sm:gap-6">
                <div class="text-right">
                    <p data-monitor-jam class="text-3xl font-bold leading-none tracking-tight text-white sm:text-4xl">--:--</p>
                    <p data-monitor-tanggal class="mt-1 text-[11px] text-teal-300/70">—</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" data-monitor-aktifkan class="inline-flex items-center gap-2 rounded-xl bg-teal-solid px-4 py-2.5 text-[12px] font-semibold text-white ring-1 ring-white/15 transition-colors hover:bg-teal-deep">
                        <i data-lucide="volume-2" class="size-4"></i><span data-monitor-aktifkan-label>Aktifkan Suara</span>
                    </button>
                    <button type="button" data-monitor-fullscreen class="flex size-10 items-center justify-center rounded-xl bg-white/10 text-teal-100 ring-1 ring-white/15 transition-colors hover:bg-white/15" aria-label="Layar penuh">
                        <i data-lucide="maximize" class="size-5"></i>
                    </button>
                </div>
            </div>
        </header>

        <main class="mt-6 grid flex-1 gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1.6fr)]">
            <section class="relative flex flex-col items-center justify-center overflow-hidden rounded-3xl bg-gradient-to-b from-teal-solid to-teal-ink px-6 py-10 text-center ring-1 ring-white/10">
                <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-20 -left-16 size-72 rounded-full border border-white/[.07]"></div>

                <div class="relative z-10 flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-[11px] font-semibold uppercase tracking-[.18em] text-teal-100 ring-1 ring-white/15">
                    <span class="monitor-pulse size-2 rounded-full bg-emerald-300"></span>
                    <span data-monitor-label-aktif>Menunggu panggilan</span>
                </div>

                <p data-monitor-kode class="relative z-10 mt-6 text-[88px] font-black leading-none tracking-tighter text-white sm:text-[120px] xl:text-[140px]">A-000</p>
                <p data-monitor-nama class="relative z-10 mt-3 text-2xl font-semibold text-teal-50 sm:text-3xl">—</p>
                <p data-monitor-dokter class="relative z-10 mt-2 text-sm font-medium text-teal-200/80 sm:text-base">dr. Sumarwanto</p>

                <div class="relative z-10 mt-8 inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2 text-[12px] text-teal-100 ring-1 ring-white/10">
                    <i data-lucide="door-open" class="size-4"></i>
                    Silakan menuju ruang pemeriksaan
                </div>
            </section>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-2">
                @foreach ([
                    ['kunci' => 'menunggu', 'judul' => 'Menunggu', 'ikon' => 'hourglass', 'warna' => 'amber'],
                    ['kunci' => 'dipanggil', 'judul' => 'Dipanggil', 'ikon' => 'phone-call', 'warna' => 'sky'],
                    ['kunci' => 'konsultasi', 'judul' => 'Sedang Konsultasi', 'ikon' => 'stethoscope', 'warna' => 'emerald'],
                    ['kunci' => 'selesai', 'judul' => 'Selesai', 'ikon' => 'check-circle-2', 'warna' => 'slate'],
                ] as $kolom)
                    @php
                        $warna = [
                            'amber' => 'text-amber-300 bg-amber-400/10 ring-amber-300/20',
                            'sky' => 'text-sky-300 bg-sky-400/10 ring-sky-300/20',
                            'emerald' => 'text-emerald-300 bg-emerald-400/10 ring-emerald-300/20',
                            'slate' => 'text-slate-300 bg-slate-400/10 ring-slate-300/20',
                        ][$kolom['warna']];
                    @endphp
                    <div class="flex flex-col overflow-hidden rounded-2xl bg-white/[.04] ring-1 ring-white/10">
                        <div class="flex items-center justify-between border-b border-white/10 px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="flex size-7 items-center justify-center rounded-lg {{ $warna }} ring-1 ring-inset"><i data-lucide="{{ $kolom['ikon'] }}" class="size-4"></i></span>
                                <span class="text-[13px] font-semibold text-white">{{ $kolom['judul'] }}</span>
                            </div>
                            <span data-monitor-jumlah="{{ $kolom['kunci'] }}" class="rounded-md bg-white/10 px-2 py-0.5 text-[11px] font-bold text-teal-100">0</span>
                        </div>
                        <div data-monitor-daftar="{{ $kolom['kunci'] }}" class="dashboard-scrollbar flex-1 space-y-2 overflow-y-auto p-3" style="max-height: 30vh;">
                            <p class="px-2 py-6 text-center text-[12px] text-teal-200/50">Belum ada antrean</p>
                        </div>
                    </div>
                @endforeach
            </section>
        </main>

        <footer class="mt-5 overflow-hidden rounded-xl border border-white/10 bg-white/[.03] py-2.5">
            <div class="monitor-ticker flex w-max items-center gap-16 whitespace-nowrap text-[12px] text-teal-200/70">
                <span>Selamat datang di Praktik Dokter Umum dr. Sumarwanto</span>
                <span>Mohon menunggu hingga nomor antrean Anda dipanggil</span>
                <span>Harap menjaga ketenangan dan kebersihan ruang tunggu</span>
                <span>Selamat datang di Praktik Dokter Umum dr. Sumarwanto</span>
                <span>Mohon menunggu hingga nomor antrean Anda dipanggil</span>
                <span>Harap menjaga ketenangan dan kebersihan ruang tunggu</span>
            </div>
        </footer>
    </div>
</body>

</html>
