@props(['pengguna'])

<header class="no-print sticky top-0 z-20 flex h-[68px] items-center gap-2 border-b border-slate-200/70 bg-white/90 px-4 backdrop-blur sm:gap-3 sm:px-7 lg:px-9">
    <button data-sidebar-toggle type="button" aria-label="Buka atau tutup navigasi" aria-controls="sidebar" aria-expanded="true" class="flex size-9 shrink-0 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-teal-600">
        <i data-lucide="menu" class="size-5"></i>
    </button>

    <form class="relative ml-1 hidden w-[230px] md:block" action="{{ route('pasien.index') }}" method="GET" role="search">
        <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 size-[15px] -translate-y-1/2 text-slate-400"></i>
        <input type="search" name="cari" aria-label="Cari pasien" placeholder="Cari pasien…" class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-[12px] text-slate-700 placeholder:text-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20">
    </form>

    <div class="ml-auto flex items-center gap-1.5 sm:gap-2">
        <div class="hidden items-center gap-2 rounded-lg border border-slate-200/70 bg-white px-3 py-1.5 md:flex" title="Waktu lokal">
            <i data-lucide="clock" class="size-4 text-teal-600"></i>
            <div class="leading-tight">
                <p data-jam class="font-mono text-[12.5px] font-semibold tracking-tight text-slate-700">00.00.00</p>
                <p data-tanggal class="text-[9.5px] text-slate-400">—</p>
            </div>
        </div>

        <a href="{{ route('antrean.monitor') }}" target="_blank" rel="noopener" aria-label="Buka monitor antrean" title="Monitor antrean (TV) di tab baru" class="flex size-9 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-teal-600">
            <i data-lucide="monitor" class="size-[19px]"></i>
        </a>

        <div class="relative" data-notifikasi>
            <button type="button" data-notifikasi-trigger aria-haspopup="true" aria-expanded="false" class="relative flex size-9 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-teal-600" aria-label="Notifikasi">
                <i data-lucide="bell" class="size-[19px]"></i>
                <span data-notifikasi-badge class="absolute -right-0.5 -top-0.5 hidden min-w-[18px] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-[18px] text-white ring-2 ring-white">0</span>
            </button>

            <div data-notifikasi-panel class="absolute right-0 top-[calc(100%+10px)] z-30 hidden w-[340px] max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_18px_50px_rgba(15,40,35,.16)] sm:w-[380px]">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <div class="flex items-center gap-2">
                        <i data-lucide="bell" class="size-4 text-teal-600"></i>
                        <span class="text-[13px] font-semibold text-slate-800">Notifikasi</span>
                    </div>
                    <button type="button" data-notifikasi-tandai-semua class="text-[11px] font-medium text-teal-700 hover:text-teal-800 hover:underline">Tandai semua dibaca</button>
                </div>
                <div data-notifikasi-daftar class="dashboard-scrollbar max-h-[380px] overflow-y-auto"></div>
            </div>
        </div>

        <span class="hidden h-7 w-px bg-slate-200 sm:block"></span>

        <div class="relative" data-profile-menu>
            <button type="button" aria-haspopup="true" aria-expanded="false" class="flex items-center gap-2 rounded-lg py-1 pl-1 pr-1.5 transition-colors hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-teal-600">
                <span class="flex size-8 items-center justify-center rounded-full bg-teal-solid text-[10px] font-semibold text-white">
                    {{ collect(explode(' ', $pengguna->nama_lengkap))->take(2)->map(fn ($b) => mb_strtoupper(mb_substr($b, 0, 1)))->implode('') }}
                </span>
                <span class="hidden text-left sm:block">
                    <span class="block max-w-[140px] truncate text-[11.5px] font-semibold text-slate-700">{{ $pengguna->nama_lengkap }}</span>
                    <span class="mt-0.5 block text-[9.5px] text-slate-400">{{ $pengguna->namaPeran() }}</span>
                </span>
                <i data-lucide="chevron-down" class="hidden size-4 text-slate-400 sm:block"></i>
            </button>

            <div data-profile-dropdown class="absolute right-0 top-[calc(100%+10px)] z-20 hidden w-60 overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-[0_10px_34px_rgba(15,40,35,.14)]">
                <div class="px-3 py-2.5">
                    <p class="truncate text-[12px] font-semibold text-slate-800">{{ $pengguna->nama_lengkap }}</p>
                    <p class="mt-0.5 truncate text-[10.5px] text-slate-400">{{ $pengguna->alamat_email }}</p>
                </div>
                <div class="my-1 border-t border-slate-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-[12px] text-slate-600 transition-colors hover:bg-rose-50 hover:text-rose-600">
                        <i data-lucide="log-out" class="size-4"></i>Keluar dari aplikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
