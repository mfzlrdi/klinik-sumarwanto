<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <title>Dashboard · Praktik Dokter dr. Sumarwanto</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { font-family: 'DM Sans', 'Instrument Sans', sans-serif; }
        .sidebar-open { transform: translateX(0) !important; }
        .sidebar-overlay-open { display: block !important; opacity: 1 !important; }
        .mobile-menu-transition { transition: transform .22s ease; }
        .dashboard-dot-pattern { background-image: radial-gradient(#cfdbd5 .8px, transparent .8px); background-position: -5px -5px; background-size: 19px 19px; }
        @media (prefers-reduced-motion: reduce) { *,*::before,*::after { animation-duration: .01ms !important; transition-duration: .01ms !important; } }
    </style>
</head>
<body class="min-h-screen bg-[#f7f9f8] text-[#243834] antialiased">
    <div data-sidebar-overlay class="fixed inset-0 z-30 hidden bg-[#163832]/35 opacity-0 backdrop-blur-[2px] transition-opacity lg:hidden" aria-hidden="true"></div>

    <aside data-sidebar class="mobile-menu-transition fixed inset-y-0 left-0 z-40 flex w-[250px] -translate-x-full flex-col border-r border-[#e9eeeb] bg-white shadow-[8px_0_28px_rgba(31,70,59,.05)] lg:translate-x-0 lg:shadow-none">
        <a href="{{ route('dashboard') }}" class="flex h-[78px] shrink-0 items-center gap-3 border-b border-[#edf1ef] px-[21px] focus-visible:outline-2 focus-visible:outline-[#3c8876]">
            <span class="flex size-[39px] items-center justify-center rounded-[13px] bg-[#eaf4f0] text-[#2b7869]"><i data-lucide="activity" class="size-[21px]"></i></span>
            <span><span class="block text-[12px] font-bold tracking-[-.025em] text-[#24463f]">Praktik Dokter</span><span class="mt-0.5 block text-[10px] font-medium text-[#788c84]">dr. Sumarwanto</span></span>
        </a>

        <div class="flex items-center gap-3 border-b border-[#edf1ef] px-[21px] py-[18px]">
            <span class="flex size-[39px] shrink-0 items-center justify-center rounded-full border border-[#d7eae3] bg-[#eff7f4] text-[12px] font-bold text-[#337b6a]">DS</span>
            <span class="min-w-0 flex-1"><span class="block truncate text-[11.5px] font-semibold text-[#31443f]">{{ $pengguna->nama_lengkap }}</span><span class="mt-1 block truncate text-[10px] text-[#83908a]">{{ $pengguna->namaPeran() }}</span></span>
            <span class="size-1.5 shrink-0 rounded-full bg-[#57a47c]" title="Akun aktif"></span>
        </div>

        <nav class="dashboard-scrollbar flex-1 space-y-[22px] overflow-y-auto px-3 py-6" aria-label="Navigasi utama">
            <div>
                <p class="mb-2 px-2 text-[9px] font-semibold uppercase tracking-[.13em] text-[#9aa7a1]">MENU UTAMA</p>
                <a href="{{ route('dashboard') }}" aria-current="page" class="flex items-center gap-3 rounded-[9px] bg-[#edf6f2] px-3 py-[10px] text-[11.5px] font-semibold text-[#317564]"><i data-lucide="home" class="size-[16px]"></i>Dashboard<span class="ml-auto h-[18px] w-[3px] rounded-full bg-[#438a76]"></span></a>
                <a href="#" aria-disabled="true" class="mt-1.5 flex cursor-not-allowed items-center gap-3 rounded-[9px] px-3 py-[9px] text-[11px] text-[#a1aaa6]"><i data-lucide="users" class="size-[16px]"></i>Data Pasien<span class="ml-auto rounded bg-[#f2f4f3] px-1.5 py-[3px] text-[9px]">Segera</span></a>
                <a href="#" aria-disabled="true" class="mt-1 flex cursor-not-allowed items-center gap-3 rounded-[9px] px-3 py-[9px] text-[11px] text-[#a1aaa6]"><i data-lucide="clipboard-list" class="size-[16px]"></i>Antrean Pasien<span class="ml-auto rounded bg-[#f2f4f3] px-1.5 py-[3px] text-[9px]">Segera</span></a>
                <a href="#" aria-disabled="true" class="mt-1 flex cursor-not-allowed items-center gap-3 rounded-[9px] px-3 py-[9px] text-[11px] text-[#a1aaa6]"><i data-lucide="file-heart" class="size-[16px]"></i>Rekam Medis<span class="ml-auto rounded bg-[#f2f4f3] px-1.5 py-[3px] text-[9px]">Segera</span></a>
            </div>
            <div>
                <p class="mb-2 px-2 text-[9px] font-semibold uppercase tracking-[.13em] text-[#9aa7a1]">LAINNYA</p>
                <a href="#" aria-disabled="true" class="flex cursor-not-allowed items-center gap-3 rounded-[9px] px-3 py-[9px] text-[11px] text-[#a1aaa6]"><i data-lucide="file-text" class="size-[16px]"></i>Laporan<span class="ml-auto rounded bg-[#f2f4f3] px-1.5 py-[3px] text-[9px]">Segera</span></a>
                @if ($pengguna->memilikiPeran('administrator'))
                    <a href="#" aria-disabled="true" class="mt-1 flex cursor-not-allowed items-center gap-3 rounded-[9px] px-3 py-[9px] text-[11px] text-[#a1aaa6]"><i data-lucide="settings-2" class="size-[16px]"></i>Pengaturan<span class="ml-auto rounded bg-[#f2f4f3] px-1.5 py-[3px] text-[9px]">Segera</span></a>
                @endif
            </div>
        </nav>

        <div class="border-t border-[#edf1ef] px-3 py-3">
            <div class="flex items-center gap-2.5 rounded-[10px] bg-[#f7faf8] px-3 py-[11px]">
                <span class="flex size-[30px] shrink-0 items-center justify-center rounded-lg bg-white text-[#42816c] shadow-sm"><i data-lucide="shield-check" class="size-[15px]"></i></span>
                <span><span class="block text-[10px] font-semibold text-[#456057]">Akses terlindungi</span><span class="mt-0.5 block text-[9px] text-[#87968f]">Akun dengan sesi aktif</span></span>
            </div>
            <form class="mt-2" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-2.5 rounded-[9px] px-3 py-[10px] text-[11px] text-[#72807a] transition-colors hover:bg-[#fff5f3] hover:text-[#aa544c] focus-visible:outline-2 focus-visible:outline-[#3c8876]"><i data-lucide="log-out" class="size-[16px]"></i>Keluar dari aplikasi</button>
            </form>
        </div>
    </aside>

    <div class="min-h-screen lg:pl-[250px]">
        <header class="sticky top-0 z-20 flex h-[68px] items-center gap-3 border-b border-[#e9eeeb] bg-white/95 px-4 backdrop-blur sm:px-7 lg:px-9">
            <button data-sidebar-toggle type="button" aria-label="Buka navigasi" class="flex size-[35px] shrink-0 items-center justify-center rounded-lg text-[#64766f] hover:bg-[#f2f6f4] focus-visible:outline-2 focus-visible:outline-[#3c8876] lg:hidden"><i data-lucide="menu" class="size-[19px]"></i></button>
            <div class="hidden items-center gap-2 text-[11px] text-[#99a59f] sm:flex"><i data-lucide="home" class="size-[14px]"></i><i data-lucide="chevron-right" class="size-[13px]"></i><span class="font-medium text-[#406e60]">Dashboard</span></div>
            <div class="relative ml-auto hidden w-[210px] md:block"><i data-lucide="search" class="absolute left-3 top-1/2 size-[15px] -translate-y-1/2 text-[#9ba7a2]"></i><input type="search" aria-label="Cari" disabled placeholder="Cari..." class="w-full rounded-[9px] border border-[#edf0ee] bg-[#fafbfa] py-[8px] pl-9 pr-3 text-[11px] placeholder:text-[#a3aca7] focus:outline-none"></div>
            <button type="button" disabled aria-label="Notifikasi" title="Notifikasi tersedia saat fitur diterapkan" class="relative flex size-[35px] shrink-0 items-center justify-center rounded-[9px] text-[#74817b]"><i data-lucide="bell" class="size-[17px]"></i></button>
            <span class="hidden h-7 w-px bg-[#e9eeeb] sm:block"></span>
            <div data-profile-menu class="relative">
                <button type="button" aria-haspopup="true" aria-expanded="false" class="flex items-center gap-2 rounded-[9px] py-1 pl-1 pr-1.5 transition-colors hover:bg-[#f7f9f8] focus-visible:outline-2 focus-visible:outline-[#3c8876]">
                    <span class="flex size-[32px] items-center justify-center rounded-full bg-[#eaf4f0] text-[10px] font-semibold text-[#387765]">{{ collect(explode(' ', $pengguna->nama_lengkap))->take(2)->map(fn ($bagian) => mb_strtoupper(mb_substr($bagian, 0, 1)))->implode('') }}</span>
                    <span class="hidden text-left sm:block"><span class="block max-w-[140px] truncate text-[10.5px] font-semibold text-[#42544e]">{{ $pengguna->nama_lengkap }}</span><span class="mt-0.5 block text-[9px] text-[#87928d]">{{ $pengguna->namaPeran() }}</span></span>
                    <i data-lucide="chevron-down" class="hidden size-[14px] text-[#9da9a3] sm:block"></i>
                </button>
                <div data-profile-dropdown class="absolute right-0 top-[calc(100%+10px)] z-20 hidden w-56 rounded-xl border border-[#e7eeea] bg-white p-2 shadow-[0_8px_30px_rgba(31,70,59,.12)]">
                    <div class="px-3 py-2.5"><p class="truncate text-[11px] font-semibold text-[#395149]">{{ $pengguna->nama_lengkap }}</p><p class="mt-1 truncate text-[10px] text-[#87948e]">{{ $pengguna->alamat_email }}</p></div>
                    <div class="my-1 border-t border-[#edf1ef]"></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-[11px] text-[#6e7d76] hover:bg-[#fff5f3] hover:text-[#aa544c]"><i data-lucide="log-out" class="size-[15px]"></i>Keluar dari aplikasi</button></form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-[1440px] px-4 pb-10 pt-7 sm:px-7 lg:px-9 lg:pt-9">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div><p class="text-[10px] font-semibold uppercase tracking-[.12em] text-[#58927d]">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</p><h1 class="mt-2 text-[23px] font-semibold tracking-[-.04em] text-[#29443b]">{{ $sapaan }}, {{ Str::before($pengguna->nama_lengkap, ' ') }}</h1><p class="mt-1.5 text-[11px] leading-5 text-[#82908a]">Selamat datang di sistem administrasi Praktik Dokter dr. Sumarwanto.</p></div>
                <div class="flex items-center gap-2 self-start rounded-[9px] border border-[#e5ece8] bg-white px-3 py-2 text-[10px] font-medium text-[#607970] sm:self-auto"><span class="relative flex size-2"><span class="absolute inline-flex size-full animate-ping rounded-full bg-[#7cba92] opacity-25"></span><span class="relative inline-flex size-2 rounded-full bg-[#66a982]"></span></span>Praktik terkelola</div>
            </div>

            <section class="relative mt-7 overflow-hidden rounded-[16px] border border-[#dcebe3] bg-[#f1f7f3] px-5 py-[18px] sm:px-6" aria-label="Panduan awal">
                <div class="absolute right-0 top-0 hidden h-full w-[39%] dashboard-dot-pattern opacity-70 sm:block"></div>
                <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3.5"><span class="flex size-[38px] shrink-0 items-center justify-center rounded-[11px] border border-[#e2ede6] bg-white text-[#4e8b71]"><i data-lucide="heart-pulse" class="size-[19px]"></i></span><div><h2 class="text-[12px] font-semibold text-[#365a48]">Ruang kerja Anda sudah siap</h2><p class="mt-1 max-w-[480px] text-[10.5px] leading-[1.7] text-[#748a7d]">Gunakan satu sistem untuk mengelola pendaftaran, antrean, dan layanan pasien dengan alur yang lebih teratur.</p></div></div>
                    <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-[8px] border border-[#dfeae3] bg-white/75 px-3 py-2 text-[10px] font-medium text-[#697e70]"><i data-lucide="shield-check" class="size-[14px]"></i>{{ Str::ucfirst($pengguna->peran->pluck('nama_peran')->join(' · ')) }}</span>
                </div>
            </section>

            <div class="mt-6 flex items-center justify-between"><div><h2 class="text-[13px] font-semibold tracking-[-.015em] text-[#334941]">Ringkasan layanan hari ini</h2><p class="mt-1 text-[10px] text-[#8a9690]">Informasi pelayanan akan muncul di sini.</p></div><span class="rounded-[7px] border border-[#e8edeb] bg-white px-2.5 py-1.5 text-[9.5px] text-[#7f8c86]">{{ now()->locale('id')->translatedFormat('j F Y') }}</span></div>
            <section class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan statistik">
                @foreach ([['icon' => 'users', 'label' => 'Total pasien', 'value' => '—', 'color' => 'text-[#4c8396]', 'bg' => 'bg-[#edf5f7]'], ['icon' => 'clipboard-list', 'label' => 'Antrean hari ini', 'value' => '—', 'color' => 'text-[#588c68]', 'bg' => 'bg-[#edf5ef]'], ['icon' => 'activity', 'label' => 'Sedang dilayani', 'value' => '—', 'color' => 'text-[#b48646]', 'bg' => 'bg-[#faf5eb]'], ['icon' => 'heart-pulse', 'label' => 'Kunjungan selesai', 'value' => '—', 'color' => 'text-[#8a77a4]', 'bg' => 'bg-[#f3f0f8]']] as $statistik)
                    <article class="rounded-[12px] border border-[#e9eeeb] bg-white p-4 sm:p-[17px]">
                        <div class="flex items-center justify-between"><span class="text-[10.5px] font-medium text-[#73827b]">{{ $statistik['label'] }}</span><span class="flex size-[30px] items-center justify-center rounded-[9px] {{ $statistik['bg'] }} {{ $statistik['color'] }}"><i data-lucide="{{ $statistik['icon'] }}" class="size-[15px]"></i></span></div>
                        <div class="mt-3 flex items-end justify-between"><span class="text-[23px] font-semibold leading-none tracking-[-.04em] text-[#374c44]">{{ $statistik['value'] }}</span><span class="text-[9px] text-[#a0aaa5]">Menunggu data</span></div>
                        <div class="mt-3 h-[3px] overflow-hidden rounded-full bg-[#f0f3f1]"><div class="h-full w-[18%] rounded-full bg-[#dce9e1]"></div></div>
                    </article>
                @endforeach
            </section>

            <div class="mt-6 grid gap-4 xl:grid-cols-[minmax(0,1.75fr)_minmax(250px,1fr)]">
                <section class="overflow-hidden rounded-[12px] border border-[#e9eeeb] bg-white">
                    <div class="flex items-center justify-between border-b border-[#edf1ef] px-4 py-[15px] sm:px-[18px]"><div><h2 class="text-[11.5px] font-semibold text-[#3a5047]">Antrean kunjungan hari ini</h2><p class="mt-1 text-[9.5px] text-[#909b95]">Daftar kunjungan praktik</p></div><button type="button" disabled class="flex items-center gap-1.5 rounded-[8px] border border-[#e9eeeb] px-2.5 py-[6px] text-[9px] font-medium text-[#73837b] opacity-70"><i data-lucide="calendar-days" class="size-[13px]"></i>Hari ini<i data-lucide="chevron-down" class="size-3"></i></button></div>
                    <div class="flex min-h-[220px] flex-col items-center justify-center px-4 py-9 text-center">
                        <span class="flex size-[43px] items-center justify-center rounded-[13px] border border-[#ecf0ee] bg-[#f8faf9] text-[#91a79c]"><i data-lucide="clipboard-list" class="size-[19px]"></i></span>
                        <h3 class="mt-3 text-[11px] font-semibold text-[#53665d]">Belum ada data antrean</h3>
                        <p class="mt-1.5 max-w-[270px] text-[10px] leading-[1.75] text-[#939e98]">Pasien yang telah terdaftar akan muncul di sini sesuai alur layanan praktik.</p>
                    </div>
                    <div class="flex items-center justify-between border-t border-[#f0f3f1] px-4 py-[11px] sm:px-[18px]"><span class="text-[9px] text-[#99a39e]">Menampilkan 0 kunjungan</span><div class="flex items-center gap-1"><button type="button" disabled aria-label="Halaman sebelumnya" class="flex size-[24px] items-center justify-center rounded border border-[#edf0ee] text-[#a6b0ab]"><i data-lucide="chevron-left" class="size-[13px]"></i></button><button type="button" disabled aria-label="Halaman berikutnya" class="flex size-[24px] items-center justify-center rounded border border-[#edf0ee] text-[#a6b0ab]"><i data-lucide="chevron-right" class="size-[13px]"></i></button></div></div>
                </section>

                <section class="rounded-[12px] border border-[#e9eeeb] bg-white p-4 sm:p-[18px]">
                    <div class="flex items-center justify-between"><div><h2 class="text-[11.5px] font-semibold text-[#3a5047]">Aktivitas terbaru</h2><p class="mt-1 text-[9.5px] text-[#909b95]">Catatan kegiatan layanan</p></div><button type="button" disabled aria-label="Lainnya" class="flex size-[28px] items-center justify-center rounded-[8px] text-[#87948d]"><i data-lucide="more-horizontal" class="size-[17px]"></i></button></div>
                    <div class="flex min-h-[188px] flex-col items-center justify-center py-8 text-center"><span class="flex size-[39px] items-center justify-center rounded-[12px] bg-[#f7f9f8] text-[#97a79f]"><i data-lucide="activity" class="size-[17px]"></i></span><p class="mt-2.5 text-[10.5px] font-medium text-[#667971]">Belum ada aktivitas</p><p class="mt-1 max-w-[210px] text-[9.5px] leading-[1.7] text-[#98a39d]">Aktivitas layanan akan tercatat di sini.</p></div>
                </section>
            </div>

            <footer class="mt-7 flex flex-col justify-between gap-2 border-t border-[#e9eeeb] pt-4 text-[9px] text-[#9aa49e] sm:flex-row sm:items-center"><span>© {{ now()->year }} Praktik Dokter Umum dr. Sumarwanto</span><span class="inline-flex items-center gap-1.5"><i data-lucide="shield-check" class="size-[13px] text-[#62967b]"></i>Sesi Anda terlindungi · {{ $pengguna->namaPeran() }}</span></footer>
        </main>
    </div>
</body>
</html>
