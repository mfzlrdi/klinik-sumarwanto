import './bootstrap';

import {
    Activity, AlertCircle, ArrowLeft, ArrowRight, ArrowUpRight, BadgeCheck, BarChart3, Bell, BellRing,
    Box, Building2, CalendarDays, Check, CheckCircle2, ChevronDown, ChevronLeft, ChevronRight,
    ChevronsLeft, ChevronsRight, ChevronUp, CircleCheck, CircleX, ClipboardCheck, ClipboardList,
    Clock, DoorOpen, Download, Droplets, Edit, Eye, EyeOff, ExternalLink, FileHeart, FileOutput, FileText, Filter,
    FlaskConical, Grip, Heart, HeartPulse, History, Home, Hospital, Hourglass, IdCard, Inbox, Info, ListChecks,
    LayoutTemplate, ListFilter, LogIn, LogOut, Mail, MapPin, Mars, Maximize, Menu, Microscope, Minus, Monitor, MoreHorizontal, Package, PackageCheck,
    Pencil, Phone, PhoneCall, Pill, PillBottle, Play, Plus, Printer, RefreshCw, Ruler, Save, Scale,
    ScrollText, Search, Send, Settings, Shapes, ShieldCheck, ShieldPlus, Stethoscope, StickyNote, Thermometer,
    Trash2, TriangleAlert, Truck, UserCog, UserPlus, UserRound, Users, Venus, Volume2, Wind, X,
    XCircle, createIcons,
} from 'lucide';

const icons = {
    Activity, AlertCircle, ArrowLeft, ArrowRight, ArrowUpRight, BadgeCheck, BarChart3, Bell, BellRing,
    Box, Building2, CalendarDays, Check, CheckCircle2, ChevronDown, ChevronLeft, ChevronRight,
    ChevronsLeft, ChevronsRight, ChevronUp, CircleCheck, CircleX, ClipboardCheck, ClipboardList,
    Clock, DoorOpen, Download, Droplets, Edit, Eye, EyeOff, ExternalLink, FileHeart, FileOutput, FileText, Filter,
    FlaskConical, Grip, Heart, HeartPulse, History, Home, Hospital, Hourglass, IdCard, Inbox, Info, ListChecks,
    LayoutTemplate, ListFilter, LogIn, LogOut, Mail, MapPin, Mars, Maximize, Menu, Microscope, Minus, Monitor, MoreHorizontal, Package, PackageCheck,
    Pencil, Phone, PhoneCall, Pill, PillBottle, Play, Plus, Printer, RefreshCw, Ruler, Save, Scale,
    ScrollText, Search, Send, Settings, Shapes, ShieldCheck, ShieldPlus, Stethoscope, StickyNote, Thermometer,
    Trash2, TriangleAlert, Truck, UserCog, UserPlus, UserRound, Users, Venus, Volume2, Wind, X,
    XCircle,
};

window.renderLucide = function renderLucide(root = document) {
    createIcons({ icons, attrs: { 'stroke-width': 1.8, 'aria-hidden': 'true' }, root });
};

window.renderLucide();

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

/* ===================== Kata sandi ===================== */
for (const toggle of document.querySelectorAll('[data-password-toggle]')) {
    const button = toggle.querySelector('button');
    const input = document.getElementById(button?.dataset.passwordToggle ?? '');

    button?.addEventListener('click', () => {
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-label', visible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        button.innerHTML = visible ? '<i data-lucide="eye-off"></i>' : '<i data-lucide="eye"></i>';
        window.renderLucide(button);
    });
}

/* ===================== Tab login ===================== */
for (const tab of document.querySelectorAll('[data-login-tab]')) {
    tab.addEventListener('click', () => {
        const selected = tab.dataset.loginTab;
        const basePath = tab.dataset.loginUrl ?? window.location.pathname;
        window.location.assign(selected === 'dokter' ? basePath : `${basePath}?akun=resepsionis`);
    });
}

/* ===================== Sidebar (buka/tutup di desktop, tablet, mobile) ===================== */
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');
const kunciSidebar = 'klinik-sidebar-terbuka';

function sidebarTerbuka() {
    return document.body.classList.contains('sidebar-terbuka');
}

function aturOverlaySidebar() {
    if (!sidebarOverlay) return;
    const perluOverlay = sidebarTerbuka() && window.innerWidth < 1024;
    sidebarOverlay.classList.toggle('hidden', !perluOverlay);
    requestAnimationFrame(() => sidebarOverlay.classList.toggle('opacity-0', !perluOverlay));
    document.body.classList.toggle('overflow-hidden', perluOverlay);
}

function bukaSidebar() {
    document.body.classList.add('sidebar-terbuka');
    try { localStorage.setItem(kunciSidebar, '1'); } catch { /* mode privat */ }
    sidebarToggle?.setAttribute('aria-expanded', 'true');
    aturOverlaySidebar();
}

function tutupSidebar() {
    document.body.classList.remove('sidebar-terbuka');
    try { localStorage.setItem(kunciSidebar, '0'); } catch { /* mode privat */ }
    sidebarToggle?.setAttribute('aria-expanded', 'false');
    aturOverlaySidebar();
}

(() => {
    let tersimpan = null;
    try { tersimpan = localStorage.getItem(kunciSidebar); } catch { /* mode privat */ }

    const desktop = window.innerWidth >= 1024;
    const buka = desktop ? tersimpan !== '0' : false;

    document.body.classList.toggle('sidebar-terbuka', buka);
    sidebarToggle?.setAttribute('aria-expanded', String(buka));
    aturOverlaySidebar();
})();

sidebarToggle?.addEventListener('click', () => {
    if (sidebarTerbuka()) {
        tutupSidebar();
    } else {
        bukaSidebar();
    }
});

sidebarOverlay?.addEventListener('click', tutupSidebar);
window.addEventListener('resize', aturOverlaySidebar);

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        tutupSidebar();
        tutupDropdown();
    }
});

/* ===================== Dropdown umum ===================== */
function tutupDropdown() {
    document.querySelectorAll('[data-profile-dropdown], [data-notifikasi-panel]').forEach((el) => el.classList.add('hidden'));
    document.querySelectorAll('[data-profile-menu] button, [data-notifikasi-trigger]').forEach((el) => el.setAttribute('aria-expanded', 'false'));
}

document.querySelector('[data-profile-menu]')?.addEventListener('click', (event) => {
    const button = event.target.closest('button');
    const menu = document.querySelector('[data-profile-dropdown]');
    if (!button || !menu) return;

    const terbuka = !menu.classList.contains('hidden');
    document.querySelector('[data-notifikasi-panel]')?.classList.add('hidden');
    document.querySelector('[data-notifikasi-trigger]')?.setAttribute('aria-expanded', 'false');
    menu.classList.toggle('hidden');
    button.setAttribute('aria-expanded', String(!terbuka));
});

document.addEventListener('click', (event) => {
    const menu = document.querySelector('[data-profile-menu]');
    const dropdown = document.querySelector('[data-profile-dropdown]');
    if (dropdown && menu && !menu.contains(event.target)) {
        dropdown.classList.add('hidden');
        menu.querySelector('button')?.setAttribute('aria-expanded', 'false');
    }

    const notif = document.querySelector('[data-notifikasi]');
    if (notif && !notif.contains(event.target)) {
        document.querySelector('[data-notifikasi-panel]')?.classList.add('hidden');
        document.querySelector('[data-notifikasi-trigger]')?.setAttribute('aria-expanded', 'false');
    }
});

/* ===================== Toast ===================== */
function tutupToast(toast) {
    toast?.classList.add('animate-fade-in');
    toast?.style.setProperty('transition', 'opacity .25s ease, transform .25s ease');
    toast?.style.setProperty('opacity', '0');
    toast?.style.setProperty('transform', 'translateX(12px)');
    setTimeout(() => toast?.remove(), 260);
}

for (const toast of document.querySelectorAll('[data-toast]')) {
    const tutup = toast.querySelector('[data-toast-tutup]');
    tutup?.addEventListener('click', () => tutupToast(toast));
    setTimeout(() => tutupToast(toast), 5200);
}

/* ===================== Modal konfirmasi ===================== */
const confirmModal = document.querySelector('[data-confirm-modal]');
let confirmTarget = null;

function tampilkanKonfirmasi({ judul, pesan, label, form }) {
    if (!confirmModal) return;
    confirmTarget = form;
    confirmModal.querySelector('[data-confirm-judul]').textContent = judul;
    confirmModal.querySelector('[data-confirm-pesan]').textContent = pesan;
    confirmModal.querySelector('[data-confirm-label]').textContent = label;
    confirmModal.classList.remove('hidden');
    confirmModal.classList.add('flex');
    confirmModal.setAttribute('aria-hidden', 'false');
}

function sembunyikanKonfirmasi() {
    confirmModal?.classList.add('hidden');
    confirmModal?.classList.remove('flex');
    confirmModal?.setAttribute('aria-hidden', 'true');
    confirmTarget = null;
}

confirmModal?.querySelector('[data-confirm-batal]')?.addEventListener('click', sembunyikanKonfirmasi);
confirmModal?.querySelector('[data-confirm-overlay]')?.addEventListener('click', sembunyikanKonfirmasi);
confirmModal?.querySelector('[data-confirm-ya]')?.addEventListener('click', () => {
    const form = confirmTarget;
    sembunyikanKonfirmasi();
    if (!form) return;

    // Tandai sudah dikonfirmasi lalu submit native agar benar-benar terkirim.
    form.dataset.confirmed = 'true';

    if (typeof form.requestSubmit === 'function') {
        form.requestSubmit();
    } else {
        form.submit();
    }
});

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!form.matches('[data-confirm]') || form.dataset.confirmed === 'true') return;

    event.preventDefault();
    tampilkanKonfirmasi({
        judul: form.dataset.confirmTitle ?? 'Konfirmasi tindakan',
        pesan: form.dataset.confirmMessage ?? 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
        label: form.dataset.confirmLabel ?? 'Ya, lanjutkan',
        form,
    });
}, true);

/* ===================== Notifikasi (poling) ===================== */
const notifTrigger = document.querySelector('[data-notifikasi-trigger]');
const notifPanel = document.querySelector('[data-notifikasi-panel]');
const notifBadge = document.querySelector('[data-notifikasi-badge]');
const notifDaftar = document.querySelector('[data-notifikasi-daftar]');

function setBadge(jumlah) {
    if (!notifBadge) return;
    if (jumlah > 0) {
        notifBadge.textContent = jumlah > 99 ? '99+' : String(jumlah);
        notifBadge.classList.remove('hidden');
        notifBadge.classList.add('flex');
    } else {
        notifBadge.classList.add('hidden');
        notifBadge.classList.remove('flex');
    }
}

function renderNotifikasi(data) {
    if (!notifDaftar) return;
    setBadge(data.belum_dibaca);

    if (!data.notifikasi.length) {
        notifDaftar.innerHTML = `
            <div class="flex flex-col items-center justify-center px-6 py-12 text-center">
                <span class="flex size-11 items-center justify-center rounded-2xl bg-slate-50 text-slate-400"><i data-lucide="bell" class="size-5"></i></span>
                <p class="mt-2.5 text-[12px] font-semibold text-slate-600">Belum ada notifikasi</p>
                <p class="mt-1 text-[11px] text-slate-400">Pemberitahuan akan muncul di sini.</p>
            </div>`;
        window.renderLucide(notifDaftar);
        return;
    }

    notifDaftar.innerHTML = data.notifikasi.map((n) => `
        <button type="button" data-notifikasi-item="${n.id}" data-tautan="${n.tautan ?? ''}" class="flex w-full items-start gap-3 border-b border-slate-50 px-4 py-3 text-left transition-colors hover:bg-slate-50 ${n.dibaca ? '' : 'bg-teal-50/40'}">
            <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full ${n.dibaca ? 'bg-slate-100 text-slate-400' : 'bg-teal-solid text-white'}"><i data-lucide="${ikonNotifikasi(n.jenis)}" class="size-4"></i></span>
            <span class="min-w-0 flex-1">
                <span class="flex items-start justify-between gap-2">
                    <span class="truncate text-[12px] font-semibold text-slate-700">${escapeHtml(n.judul)}</span>
                    ${n.dibaca ? '' : '<span class="mt-1 size-2 shrink-0 rounded-full bg-teal-500"></span>'}
                </span>
                <span class="mt-0.5 block text-[11px] leading-5 text-slate-500">${escapeHtml(n.pesan)}</span>
                <span class="mt-1 block text-[10px] text-slate-400">${escapeHtml(n.waktu ?? '')}</span>
            </span>
        </button>`).join('');

    window.renderLucide(notifDaftar);
}

function ikonNotifikasi(jenis) {
    const peta = {
        kunjungan_baru: 'user-plus',
        status_antrean: 'clipboard-list',
        resep_difinalisasi: 'pill',
        tindak_lanjut_obat: 'package-check',
        penyerahan_obat: 'package-check',
        administratif: 'settings',
    };
    return peta[jenis] ?? 'bell';
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

async function muatNotifikasi() {
    try {
        const { data } = await window.axios.get('/notifikasi');
        renderNotifikasi(data);
    } catch {
        // Kegagalan poling tidak menghapus notifikasi yang sudah tersimpan.
    }
}

notifTrigger?.addEventListener('click', () => {
    const terbuka = !notifPanel.classList.contains('hidden');
    document.querySelector('[data-profile-dropdown]')?.classList.add('hidden');
    notifPanel.classList.toggle('hidden');
    notifTrigger.setAttribute('aria-expanded', String(!terbuka));
    if (!terbuka) muatNotifikasi();
});

notifDaftar?.addEventListener('click', async (event) => {
    const item = event.target.closest('[data-notifikasi-item]');
    if (!item) return;

    const id = item.dataset.notifikasiItem;
    try {
        await window.axios.post(`/notifikasi/${id}/baca`);
    } catch {
        // Abaikan kegagalan pembacaan, tautan tetap diikuti.
    }

    const tautan = item.dataset.tautan;
    if (tautan) window.location.assign(tautan);
});

document.querySelector('[data-notifikasi-tandai-semua]')?.addEventListener('click', async () => {
    try {
        await window.axios.post('/notifikasi/tandai-semua-dibaca');
        muatNotifikasi();
    } catch {
        // Abaikan.
    }
});

if (notifTrigger) {
    muatNotifikasi();
    setInterval(muatNotifikasi, 20000);
}

/* ===================== Autocomplete pasien ===================== */
const pasienCari = document.querySelector('[data-pasien-cari]');
if (pasienCari) {
    const input = pasienCari.querySelector('input[type="search"]');
    const hasil = pasienCari.querySelector('[data-pasien-hasil]');
    const tersimpan = pasienCari.querySelector('input[type="hidden"]');
    const tampil = pasienCari.querySelector('[data-pasien-terpilih]');
    const batal = pasienCari.querySelector('[data-pasien-batal]');
    let timer;

    input?.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(async () => {
            const q = input.value.trim();
            if (q.length < 2) {
                hasil.innerHTML = '';
                hasil.classList.add('hidden');
                return;
            }
            try {
                const { data } = await window.axios.get('/pasien/cari', { params: { q } });
                hasil.innerHTML = data.hasil.length
                    ? data.hasil.map((p) => `
                        <button type="button" data-id="${p.id}" data-nama="${escapeHtml(p.nama)}" data-rm="${escapeHtml(p.nomor_rekam_medis)}" class="flex w-full items-center gap-3 px-4 py-3 text-left transition-colors hover:bg-slate-50">
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-teal-solid text-white"><i data-lucide="user-round" class="size-4"></i></span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[12px] font-semibold text-slate-700">${escapeHtml(p.nama)}</span>
                                <span class="mt-0.5 block text-[10.5px] text-slate-400">${escapeHtml(p.nomor_rekam_medis)}${p.jenis_kelamin ? ' · ' + escapeHtml(p.jenis_kelamin) : ''}</span>
                            </span>
                        </button>`).join('')
                    : '<p class="px-4 py-3 text-[11.5px] text-slate-400">Tidak ditemukan. <a href="' + (pasienCari.dataset.tambahUrl ?? '#') + '" class="font-semibold text-teal-700 hover:underline">Daftarkan pasien baru</a></p>';
                hasil.classList.remove('hidden');
                window.renderLucide(hasil);
            } catch {
                // Abaikan.
            }
        }, 250);
    });

    hasil?.addEventListener('click', (event) => {
        const tombol = event.target.closest('[data-id]');
        if (!tombol) return;
        tersimpan.value = tombol.dataset.id;
        tampil.classList.remove('hidden');
        tampil.querySelector('[data-nama]').textContent = tombol.dataset.nama;
        tampil.querySelector('[data-rm]').textContent = tombol.dataset.rm;
        input.value = tombol.dataset.nama;
        hasil.classList.add('hidden');
    });

    batal?.addEventListener('click', () => {
        tersimpan.value = '';
        tampil.classList.add('hidden');
        input.value = '';
        hasil.innerHTML = '';
        hasil.classList.add('hidden');
    });
}

/* ===================== Repeater dinamis ===================== */
function indeksUlang(kontainer) {
    const prefix = kontainer.dataset.repeaterPrefix;
    kontainer.querySelectorAll('[data-repeater-row]').forEach((baris, indeks) => {
        baris.querySelectorAll('[data-repeater-field]').forEach((field) => {
            const nama = field.dataset.repeaterField;
            field.setAttribute('name', `${prefix}[${indeks}][${nama}]`);
        });
    });
}

for (const kontainer of document.querySelectorAll('[data-repeater]')) {
    const templat = kontainer.querySelector('template');
    const tambah = kontainer.querySelector('[data-repeater-tambah]');

    tambah?.addEventListener('click', () => {
        if (!templat) return;
        const klon = templat.content.firstElementChild.cloneNode(true);
        kontainer.querySelector('[data-repeater-daftar]')?.appendChild(klon);
        window.renderLucide(klon);
        indeksUlang(kontainer);
    });

    kontainer.addEventListener('click', (event) => {
        const hapus = event.target.closest('[data-repeater-hapus]');
        if (!hapus) return;
        hapus.closest('[data-repeater-row]')?.remove();
        indeksUlang(kontainer);
    });

    indeksUlang(kontainer);
}

/* ===================== Grafik (Chart.js, dimuat sesuai kebutuhan) ===================== */
const grafik = document.querySelector('[data-grafik-kunjungan]');
if (grafik) {
    (async () => {
        const { default: Chart } = await import('chart.js/auto');
        const label = JSON.parse(grafik.dataset.label ?? '[]');
        const jumlah = JSON.parse(grafik.dataset.jumlah ?? '[]');

        new Chart(grafik, {
            type: 'bar',
            data: {
                labels: label,
                datasets: [{
                    label: 'Kunjungan',
                    data: jumlah,
                    backgroundColor: 'rgba(13, 92, 86, 0.85)',
                    hoverBackgroundColor: '#0a4a45',
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 36,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { displayColors: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#eef2f1' } },
                    x: { grid: { display: false } },
                },
            },
        });
    })();
}

/* ===================== Cetak ===================== */
for (const tombol of document.querySelectorAll('[data-cetak]')) {
    tombol.addEventListener('click', () => window.print());
}

/* ===================== Panggilan antrean: ding + suara ===================== */
let konteksAudio = null;

function pastikanKonteksAudio() {
    try {
        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
        if (!AudioContextClass) return null;
        konteksAudio = konteksAudio || new AudioContextClass();
        if (konteksAudio.state === 'suspended') konteksAudio.resume();
        return konteksAudio;
    } catch {
        return null;
    }
}

function bunyikanDing() {
    const ctx = pastikanKonteksAudio();
    if (!ctx) return;
    const mulai = ctx.currentTime;
    [880, 1174.66].forEach((frekuensi, urutan) => {
        const osilator = ctx.createOscillator();
        const gain = ctx.createGain();
        const t = mulai + urutan * 0.3;
        osilator.type = 'sine';
        osilator.frequency.value = frekuensi;
        gain.gain.setValueAtTime(0.0001, t);
        gain.gain.exponentialRampToValueAtTime(0.28, t + 0.02);
        gain.gain.exponentialRampToValueAtTime(0.0001, t + 0.5);
        osilator.connect(gain).connect(ctx.destination);
        osilator.start(t);
        osilator.stop(t + 0.55);
    });
}

function pilihSuaraIndonesia() {
    if (!('speechSynthesis' in window)) return null;
    const daftar = window.speechSynthesis.getVoices() || [];
    return daftar.find((suara) => (suara.lang || '').toLowerCase().startsWith('id')) ?? null;
}

function ucapkanTeks(teks) {
    return new Promise((selesai) => {
        if (!('speechSynthesis' in window)) {
            selesai();
            return;
        }
        const u = new SpeechSynthesisUtterance(teks);
        u.lang = 'id-ID';
        u.rate = 0.92;
        u.pitch = 1;
        const suara = pilihSuaraIndonesia();
        if (suara) u.voice = suara;
        u.onend = () => selesai();
        u.onerror = () => selesai();
        window.speechSynthesis.speak(u);
    });
}

function tunggu(ms) {
    return new Promise((selesai) => setTimeout(selesai, ms));
}

let sedangMemanggil = false;

window.panggilAntrean = async function panggilAntrean(kode, dokter) {
    if (sedangMemanggil) return;
    sedangMemanggil = true;
    try {
        bunyikanDing();
        await tunggu(820);
        const kalimat = `Nomor Antrian ${kode}, silakan memasuki ruangan ${dokter ?? 'dr. Sumarwanto'}`;
        await ucapkanTeks(kalimat);
        await tunggu(900);
        await ucapkanTeks(kalimat);
    } finally {
        sedangMemanggil = false;
    }
};

if ('speechSynthesis' in window) {
    window.speechSynthesis.onvoiceschanged = () => pilihSuaraIndonesia();
}

for (const tombol of document.querySelectorAll('[data-panggil-antrean]')) {
    tombol.addEventListener('click', async () => {
        if (tombol.dataset.sibuk === '1') return;
        tombol.dataset.sibuk = '1';
        tombol.disabled = true;

        try {
            const { data } = await window.axios.post(tombol.dataset.url);
            await window.panggilAntrean(data.kode_antrean, data.nama_dokter);
            window.location.reload();
        } catch (error) {
            tombol.dataset.sibuk = '0';
            tombol.disabled = false;
            window.alert(error?.response?.data?.message ?? 'Gagal memanggil pasien.');
        }
    });
}

/* ===================== Monitor TV antrean ===================== */
const monitor = document.querySelector('[data-monitor-antrean]');
if (monitor) {
    const url = monitor.dataset.url;
    const elemen = {
        kode: monitor.querySelector('[data-monitor-kode]'),
        nama: monitor.querySelector('[data-monitor-nama]'),
        dokter: monitor.querySelector('[data-monitor-dokter]'),
        label: monitor.querySelector('[data-monitor-label-aktif]'),
        jam: monitor.querySelector('[data-monitor-jam]'),
        tanggal: monitor.querySelector('[data-monitor-tanggal]'),
        tombolAktifkan: monitor.querySelector('[data-monitor-aktifkan]'),
        labelAktifkan: monitor.querySelector('[data-monitor-aktifkan-label]'),
        tombolFullscreen: monitor.querySelector('[data-monitor-fullscreen]'),
    };

    const petaJumlah = { menunggu: 'menunggu', dipanggil: 'dipanggil', konsultasi: 'sedang_konsultasi', selesai: 'selesai' };
    const petaDaftar = { menunggu: 'menunggu', dipanggil: 'dipanggil', konsultasi: 'sedang_konsultasi', selesai: 'selesai' };
    const warnaBadge = {
        menunggu: 'bg-amber-400/15 text-amber-200',
        dipanggil: 'bg-sky-400/15 text-sky-200',
        sedang_konsultasi: 'bg-emerald-400/15 text-emerald-200',
        selesai: 'bg-slate-400/15 text-slate-200',
    };

    let terakhirId = null;
    let suaraSiap = false;

    function renderDaftar(kunci, daftar) {
        const wadah = monitor.querySelector(`[data-monitor-daftar="${kunci}"]`);
        if (!wadah) return;

        if (!daftar || !daftar.length) {
            wadah.innerHTML = '<p class="px-2 py-5 text-center text-[12px] text-teal-200/40">Belum ada antrean</p>';
            return;
        }

        wadah.innerHTML = daftar.map((item) => `
            <div class="flex items-center gap-3 rounded-xl bg-white/[.05] px-3 py-2.5 ring-1 ring-white/5">
                <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[12px] font-bold text-white">${escapeHtml(item.kode.replace('A-', ''))}</span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-[12.5px] font-semibold text-teal-50">${escapeHtml(item.nama)}</span>
                    <span class="mt-0.5 block text-[10.5px] text-teal-200/50">${escapeHtml(item.kode)}</span>
                </span>
                <span class="rounded-md px-2 py-0.5 text-[10px] font-semibold ${warnaBadge[item.status] ?? 'bg-white/10 text-teal-100'}">${escapeHtml(item.status_label ?? '')}</span>
            </div>`).join('');
    }

    function renderAktif(item) {
        if (!elemen.kode) return;
        if (!item) {
            elemen.kode.textContent = 'A-000';
            elemen.nama.textContent = '—';
            elemen.dokter.textContent = 'dr. Sumarwanto';
            if (elemen.label) elemen.label.textContent = 'Menunggu panggilan';
            return;
        }
        elemen.kode.textContent = item.kode;
        elemen.nama.textContent = item.nama;
        elemen.dokter.textContent = item.dokter;
        if (elemen.label) elemen.label.textContent = item.status === 'dipanggil' ? 'Sedang Dipanggil' : 'Sedang Konsultasi';
    }

    function renderSemua(data) {
        if (elemen.jam) elemen.jam.textContent = data.jam;
        if (elemen.tanggal) elemen.tanggal.textContent = data.tanggal;
        renderAktif(data.aktif);

        Object.entries(petaDaftar).forEach(([kunci, kunciData]) => renderDaftar(kunci, data.kelompok[kunciData]));
        Object.entries(petaJumlah).forEach(([kunci, kunciData]) => {
            const el = monitor.querySelector(`[data-monitor-jumlah="${kunci}"]`);
            if (el) el.textContent = data.jumlah[kunciData] ?? 0;
        });
    }

    async function muatMonitor() {
        try {
            const { data } = await window.axios.get(url);
            renderSemua(data);

            const item = data.aktif;
            if (item && item.id !== terakhirId) {
                terakhirId = item.id;
                if (suaraSiap && item.status === 'dipanggil') {
                    window.panggilAntrean(item.kode, item.dokter);
                }
            }
        } catch {
            // Pertahankan tampilan terakhir bila koneksi terputus.
        }
    }

    elemen.tombolAktifkan?.addEventListener('click', async () => {
        suaraSiap = true;
        elemen.tombolAktifkan.classList.remove('bg-teal-solid');
        elemen.tombolAktifkan.classList.add('bg-emerald-600');
        if (elemen.labelAktifkan) elemen.labelAktifkan.textContent = 'Suara Aktif';
        bunyikanDing();
        await tunggu(700);
        await ucapkanTeks('Suara panggilan antrean telah aktif.');
        muatMonitor();
    });

    elemen.tombolFullscreen?.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen?.();
        } else {
            document.exitFullscreen?.();
        }
    });

    muatMonitor();
    setInterval(muatMonitor, 4000);
}

/* ===================== Jam & tanggal navbar ===================== */
const elemenJam = document.querySelector('[data-jam]');
const elemenTanggal = document.querySelector('[data-tanggal]');
if (elemenJam || elemenTanggal) {
    const perbaruiWaktu = () => {
        const sekarang = new Date();

        if (elemenJam) {
            elemenJam.textContent = sekarang
                .toLocaleTimeString('id-ID', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' })
                .replace(/:/g, '.');
        }

        if (elemenTanggal) {
            elemenTanggal.textContent = sekarang.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            });
        }
    };

    perbaruiWaktu();
    setInterval(perbaruiWaktu, 1000);
}
