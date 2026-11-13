<div data-confirm-modal class="fixed inset-0 z-[70] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-hidden="true">
    <div data-confirm-overlay class="animate-overlay-in absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]"></div>
    <div class="animate-modal-pop relative w-full max-w-[400px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_24px_60px_rgba(15,40,35,.28)]">
        <div class="flex items-start gap-3 p-5">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-500" data-confirm-ikon>
                <i data-lucide="triangle-alert" class="size-5"></i>
            </span>
            <div class="min-w-0 flex-1">
                <h3 data-confirm-judul class="text-[15px] font-semibold text-slate-800">Konfirmasi</h3>
                <p data-confirm-pesan class="mt-1 text-[12.5px] leading-5 text-slate-500">Apakah Anda yakin ingin melanjutkan?</p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 border-t border-slate-100 bg-slate-50/60 px-5 py-3.5">
            <button type="button" data-confirm-batal class="rounded-lg px-3.5 py-2 text-[12px] font-semibold text-slate-600 transition-colors hover:bg-slate-100">Batal</button>
            <button type="button" data-confirm-ya class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3.5 py-2 text-[12px] font-semibold text-white shadow-sm transition-colors hover:bg-rose-700">
                <i data-lucide="check" class="size-4"></i><span data-confirm-label>Ya, lanjutkan</span>
            </button>
        </div>
    </div>
</div>
