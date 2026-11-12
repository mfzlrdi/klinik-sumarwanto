<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#153c3a">
    <meta name="description" content="Masuk ke Sistem Informasi Administrasi Pelayanan Pasien Praktik Dokter dr. Sumarwanto.">
    <title>Masuk · Praktik Dokter dr. Sumarwanto</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            font-family: 'DM Sans', 'Instrument Sans', sans-serif;
        }

        .login-canvas {
            background-image: radial-gradient(#cddbd6 .72px, transparent .72px);
            background-position: -5px -5px;
            background-size: 21px 21px;
        }

        .form-control:focus {
            outline: 3px solid rgba(52, 130, 114, .12);
            border-color: #368474;
        }

        .brand-grid {
            background-image: linear-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, .04) 1px, transparent 1px);
            background-size: 54px 54px;
            mask-image: linear-gradient(135deg, rgba(0, 0, 0, .8), transparent 80%);
        }

        .login-enter {
            animation: login-enter .45s ease-out both;
        }

        @keyframes login-enter {
            from { opacity: 0; transform: translateY(9px); }
            to { opacity: 1; transform: translateY(0); }
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #1d3431;
            -webkit-box-shadow: 0 0 0 1000px white inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        }
    </style>
</head>

<body class="min-h-screen bg-white text-slate-800 antialiased">
    <div class="grid min-h-screen lg:grid-cols-[minmax(0,1.02fr)_minmax(480px,.98fr)]">
        <aside class="relative hidden min-h-screen flex-col overflow-hidden bg-[#153c3a] px-12 py-10 text-white lg:flex xl:px-16">
            <div class="brand-grid pointer-events-none absolute inset-0"></div>
            <div class="pointer-events-none absolute -right-32 top-[34%] h-[34rem] w-[34rem] rounded-full border border-white/[.075]"></div>
            <div class="pointer-events-none absolute -right-[4.5rem] top-[40%] h-[22rem] w-[22rem] rounded-full border border-white/[.08]"></div>
            <div class="pointer-events-none absolute -right-2 top-[47%] h-[10rem] w-[10rem] rounded-full border border-white/[.12]"></div>

            <a class="relative z-10 flex w-fit items-center gap-3.5 rounded-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white" href="{{ route('login') }}" aria-label="Praktik Dokter dr. Sumarwanto">
                <span class="flex size-11 items-center justify-center rounded-[15px] border border-white/10 bg-white/[.09] text-emerald-100 shadow-sm">
                    <i data-lucide="activity" class="size-[23px]"></i>
                </span>
                <span class="leading-tight">
                    <span class="block text-sm font-semibold tracking-[.008em] text-white">Praktik dr. Sumarwanto</span>
                    <span class="mt-1 block text-[11px] font-medium tracking-[.05em] text-teal-100/65">PRAKTIK DOKTER UMUM</span>
                </span>
            </a>

            <div class="relative z-10 my-auto -translate-y-5 py-16">
                <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-white/[.12] bg-white/[.055] px-3.5 py-2 text-[11px] font-medium tracking-[.07em] text-[#c8e0d9]">
                    <span class="size-1.5 rounded-full bg-[#81c7a8]"></span>
                    PELAYANAN KESEHATAN PRIMER
                </div>
                <h1 class="max-w-[34rem] text-[42px] font-semibold leading-[1.17] tracking-[-.045em] xl:text-[49px]">
                    Administrasi lebih teratur,<br>pelayanan terasa <span class="text-[#9bd2b3]">lebih dekat.</span>
                </h1>
                <p class="mt-5 max-w-[30rem] text-[14px] leading-[1.9] text-teal-50/65">
                    Satu ruang kerja untuk pendaftaran, antrean, dan pelayanan pasien di Praktik Dokter Umum dr. Sumarwanto.
                </p>

                <div class="mt-11 grid max-w-[32rem] grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-white/[.11] bg-white/[.055] p-4.5 transition-colors hover:bg-white/[.085]">
                        <span class="flex size-9 items-center justify-center rounded-xl bg-[#b4dfc0]/[.12] text-[#b4dfc0]"><i data-lucide="users" class="size-[17px]"></i></span>
                        <h2 class="mt-4 text-[13px] font-semibold">Data pasien terpusat</h2>
                        <p class="mt-1.5 text-[11.5px] leading-[1.75] text-teal-50/55">Informasi administrasi tersusun dalam satu tempat.</p>
                    </div>
                    <div class="rounded-2xl border border-white/[.11] bg-white/[.055] p-4.5 transition-colors hover:bg-white/[.085]">
                        <span class="flex size-9 items-center justify-center rounded-xl bg-[#b4dfc0]/[.12] text-[#b4dfc0]"><i data-lucide="clipboard-list" class="size-[17px]"></i></span>
                        <h2 class="mt-4 text-[13px] font-semibold">Antrean lebih tertata</h2>
                        <p class="mt-1.5 text-[11.5px] leading-[1.75] text-teal-50/55">Alur kunjungan yang lebih mudah dipantau bersama.</p>
                    </div>
                </div>

                <div class="mt-10 flex items-center gap-3">
                    <span class="flex size-9 items-center justify-center rounded-full border border-white/10 bg-white/[.075] text-[#b4dfc0]"><i data-lucide="shield-check" class="size-[17px]"></i></span>
                    <div>
                        <p class="text-[11.5px] font-medium text-white/90">Akses sesuai peran</p>
                        <p class="mt-0.5 text-[10.5px] text-teal-50/50">Informasi terjaga untuk pengguna berwenang.</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex items-center justify-between border-t border-white/10 pt-5 text-[10.5px] text-teal-50/45">
                <span>Praktik Dokter Umum · dr. Sumarwanto</span>
                <span class="flex items-center gap-1.5"><span class="size-1.5 rounded-full bg-[#81c7a8]"></span>Sistem operasional</span>
            </div>
        </aside>

        <main class="login-canvas relative flex min-h-screen items-center justify-center overflow-hidden px-5 py-8 sm:px-8">
            <div class="pointer-events-none absolute inset-0 bg-white/[.78]"></div>
            <div class="login-enter relative z-10 w-full max-w-[404px]">
                <div class="mb-8 flex items-center gap-3.5 lg:hidden">
                    <span class="flex size-11 items-center justify-center rounded-[15px] bg-[#1d514b] text-white shadow-sm shadow-[#153c3a]/15"><i data-lucide="activity" class="size-[22px]"></i></span>
                    <span>
                        <span class="block text-sm font-semibold tracking-[-.02em] text-[#173e3a]">Praktik dr. Sumarwanto</span>
                        <span class="mt-1 block text-[10px] font-medium tracking-[.1em] text-[#648079]">PRAKTIK DOKTER UMUM</span>
                    </span>
                </div>

                <section class="rounded-[22px] border border-[#e8eeeb] bg-white p-7 shadow-[0_18px_65px_rgba(33,74,62,.065)] sm:p-9">
                    <div class="mb-7 flex size-[45px] items-center justify-center rounded-[14px] border border-[#e0efeb] bg-[#f1f8f6] text-[#318172]">
                        <i data-lucide="heart-pulse" class="size-[21px]"></i>
                    </div>
                    <p class="text-[11px] font-semibold uppercase tracking-[.11em] text-[#458778]">PORTAL ADMINISTRASI KLINIK</p>
                    <h1 class="mt-2.5 text-[27px] font-semibold tracking-[-.045em] text-[#19342f] sm:text-[29px]">Selamat datang</h1>
                    <p class="mt-1.5 text-[13px] leading-6 text-[#72817d]">Masuk untuk melanjutkan pekerjaan Anda.</p>

                    <div class="mt-7 grid grid-cols-2 rounded-[11px] border border-[#e8eeec] bg-[#f7f9f8] p-[3px]" role="tablist" aria-label="Pilih jenis akun">
                        <button type="button" data-login-tab="dokter" data-login-url="{{ route('login') }}" role="tab" aria-selected="{{ $akun === 'dokter' ? 'true' : 'false' }}" class="rounded-[8px] px-3 py-[10px] text-[12px] font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#388477] {{ $akun === 'dokter' ? 'bg-white text-[#275e55] shadow-[0_1px_4px_rgba(35,66,56,.12)]' : 'text-[#798781] hover:text-[#315e55]' }}">
                            <span class="inline-flex items-center justify-center gap-2"><i data-lucide="stethoscope" class="size-[15px]"></i>Dokter / Admin</span>
                        </button>
                        <button type="button" data-login-tab="resepsionis" data-login-url="{{ route('login') }}" role="tab" aria-selected="{{ $akun === 'resepsionis' ? 'true' : 'false' }}" class="rounded-[8px] px-3 py-[10px] text-[12px] font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#388477] {{ $akun === 'resepsionis' ? 'bg-white text-[#275e55] shadow-[0_1px_4px_rgba(35,66,56,.12)]' : 'text-[#798781] hover:text-[#315e55]' }}">
                            <span class="inline-flex items-center justify-center gap-2"><i data-lucide="users" class="size-[15px]"></i>Resepsionis</span>
                        </button>
                    </div>

                    @if (session('status'))
                        <div role="status" class="mt-5 flex items-start gap-2.5 rounded-[10px] border border-[#d8eae1] bg-[#f1f8f4] px-3.5 py-3 text-[12px] leading-5 text-[#3e7259]">
                            <i data-lucide="check" class="mt-0.5 size-4 shrink-0"></i>{{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->has('alamat_email') && !old('alamat_email'))
                        <div role="alert" class="mt-5 flex items-start gap-2.5 rounded-[10px] border border-[#f0dedd] bg-[#fff8f7] px-3.5 py-3 text-[12px] leading-5 text-[#a44f4a]">
                            <i data-lucide="shield-check" class="mt-0.5 size-4 shrink-0"></i>{{ $errors->first('alamat_email') }}
                        </div>
                    @endif

                    <form class="mt-6 space-y-[18px]" action="{{ route('login.store') }}" method="POST" novalidate>
                        @csrf
                        <div>
                            <label for="alamat_email" class="mb-2 block text-[12px] font-semibold text-[#334541]">Alamat email</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#8e9c97]"><i data-lucide="user-round" class="size-[17px]"></i></span>
                                <input id="alamat_email" name="alamat_email" type="email" value="{{ old('alamat_email', $akun === 'dokter' ? 'dokter@praktik.test' : 'resepsionis@praktik.test') }}" autocomplete="username" inputmode="email" required autofocus placeholder="nama@praktik.test" aria-describedby="email-bantuan email-error" @class(['form-control block w-full rounded-[10px] border bg-white py-[11px] pl-[41px] pr-3.5 text-[13px] text-[#233a35] placeholder:text-[#b4beb9]', 'border-[#dfe7e4]' => !$errors->has('alamat_email'), 'border-[#e2a9a4]' => $errors->has('alamat_email')])>
                            </div>
                            <p id="email-bantuan" class="mt-1.5 text-[10.5px] text-[#8a9792]">Gunakan alamat email akun praktik Anda.</p>
                            @error('alamat_email')
                                <p id="email-error" class="mt-1.5 text-[11px] text-[#b84d47]" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-2">
                                <label for="kata_sandi" class="text-[12px] font-semibold text-[#334541]">Kata sandi</label>
                                <span class="text-[10.5px] font-medium text-[#368273]">Akses staf terdaftar</span>
                            </div>
                            <div data-password-toggle class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#8e9c97]"><i data-lucide="shield-check" class="size-[17px]"></i></span>
                                <input id="kata_sandi" name="kata_sandi" type="password" autocomplete="current-password" required placeholder="Masukkan kata sandi" aria-describedby="sandi-error" @class(['form-control block w-full rounded-[10px] border bg-white py-[11px] pl-[41px] pr-11 text-[13px] text-[#233a35] placeholder:text-[#b4beb9]', 'border-[#dfe7e4]' => !$errors->has('kata_sandi'), 'border-[#e2a9a4]' => $errors->has('kata_sandi')])>
                                <button type="button" data-password-toggle="kata_sandi" aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-[10px] text-[#84938e] transition-colors hover:text-[#317568] focus-visible:outline-2 focus-visible:outline-offset-[-3px] focus-visible:outline-[#388477]"><i data-lucide="eye" class="size-[17px]"></i></button>
                            </div>
                            @error('kata_sandi')
                                <p id="sandi-error" class="mt-1.5 text-[11px] text-[#b84d47]" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <label for="ingat_saya" class="inline-flex cursor-pointer items-center gap-2.5 text-[11.5px] text-[#5f716b]">
                            <input id="ingat_saya" name="ingat_saya" type="checkbox" value="1" @checked(old('ingat_saya')) class="size-[15px] rounded border-[#ccd8d3] accent-[#2d796c] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#388477]">
                            Ingat saya di perangkat ini
                        </label>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-[10px] bg-[#245f55] px-4 py-[12px] text-[12.5px] font-semibold text-white shadow-[0_3px_7px_rgba(33,85,72,.13)] transition-colors hover:bg-[#1b5047] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#347f71] active:bg-[#17483f]">
                            Masuk ke aplikasi <i data-lucide="arrow-right" class="size-[16px]"></i>
                        </button>
                    </form>

                    <div class="mt-6 flex items-start gap-2.5 border-t border-[#eef1ef] pt-5 text-[10.5px] leading-[1.75] text-[#8b9792]">
                        <i data-lucide="shield-check" class="mt-[1px] size-4 shrink-0 text-[#4e9884]"></i>
                        <p>Akses dibatasi untuk tenaga praktik. Aktivitas akun tercatat untuk menjaga keamanan informasi.</p>
                    </div>
                </section>

                <footer class="mt-5 flex flex-wrap items-center justify-center gap-x-1.5 gap-y-1 text-center text-[10.5px] leading-5 text-[#89958f]">
                    <span>© {{ now()->year }} Praktik dr. Sumarwanto</span><span class="text-[#c0c9c5]">·</span><span>Sistem administrasi pelayanan pasien</span>
                </footer>
            </div>
        </main>
    </div>
</body>

</html>
