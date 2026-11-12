import './bootstrap';

import { Activity, ArrowLeft, ArrowRight, ArrowUpRight, Bell, CalendarDays, Check, ChevronDown, ChevronLeft, ChevronRight, ClipboardList, Eye, EyeOff, FileHeart, FileText, HeartPulse, Home, LogOut, MoreHorizontal, Plus, Search, Settings2, ShieldCheck, Stethoscope, UserRound, Users, X, createIcons } from 'lucide';

const icons = {
    Activity,
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    Bell,
    CalendarDays,
    Check,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    ClipboardList,
    Eye,
    EyeOff,
    FileHeart,
    FileText,
    HeartPulse,
    Home,
    LogOut,
    MoreHorizontal,
    Plus,
    Search,
    Settings2,
    ShieldCheck,
    Stethoscope,
    UserRound,
    Users,
    X,
};

function renderIcons(root = document) {
    createIcons({
        icons,
        attrs: {
            'stroke-width': 1.7,
            'aria-hidden': 'true',
        },
        root,
    });
}

renderIcons();

for (const toggle of document.querySelectorAll('[data-password-toggle]')) {
    const button = toggle.querySelector('button');
    const input = document.getElementById(button?.dataset.passwordToggle ?? '');

    button?.addEventListener('click', () => {
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-label', visible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        button.setAttribute('title', visible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        button.innerHTML = visible ? '<i data-lucide="eye-off"></i>' : '<i data-lucide="eye"></i>';
        renderIcons(button);
    });
}

for (const tab of document.querySelectorAll('[data-login-tab]')) {
    tab.addEventListener('click', () => {
        const selected = tab.dataset.loginTab;
        const currentUrl = new URL(window.location.href);
        const basePath = tab.dataset.loginUrl ?? currentUrl.pathname;
        const nextUrl = selected === 'dokter' ? basePath : `${basePath}?akun=resepsionis`;

        window.location.assign(nextUrl);
    });
}

const sidebar = document.querySelector('[data-sidebar]');
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

function closeSidebar() {
    sidebar?.classList.remove('sidebar-open');
    sidebarOverlay?.classList.remove('sidebar-overlay-open');
    document.body.classList.remove('overflow-hidden');
}

sidebarToggle?.addEventListener('click', () => {
    sidebar?.classList.toggle('sidebar-open');
    sidebarOverlay?.classList.toggle('sidebar-overlay-open');
    document.body.classList.toggle('overflow-hidden');
});

sidebarOverlay?.addEventListener('click', closeSidebar);
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeSidebar();
});

document.querySelector('[data-profile-menu]')?.addEventListener('click', (event) => {
    const button = event.target.closest('button');
    const menu = document.querySelector('[data-profile-dropdown]');

    if (!button || !menu) return;

    menu.classList.toggle('hidden');
    button.setAttribute('aria-expanded', String(!menu.classList.contains('hidden')));
});

document.addEventListener('click', (event) => {
    const dropdown = document.querySelector('[data-profile-dropdown]');
    const menu = document.querySelector('[data-profile-menu]');

    if (dropdown && menu && !menu.contains(event.target)) {
        dropdown.classList.add('hidden');
        menu.querySelector('button')?.setAttribute('aria-expanded', 'false');
    }
});
