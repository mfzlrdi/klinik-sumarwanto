@props(['judul', 'deskripsi' => null, 'ikon' => null])

<div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
    <div>
        <h1 class="text-[22px] font-semibold tracking-[-.03em] text-slate-800 sm:text-[24px]">{{ $judul }}</h1>
        @if ($deskripsi)
            <p class="mt-1 max-w-2xl text-[12.5px] leading-5 text-slate-500">{{ $deskripsi }}</p>
        @endif
    </div>
    @if (isset($slot) && trim($slot->toHtml()) !== '')
        <div class="flex shrink-0 items-center gap-2">{{ $slot }}</div>
    @endif
</div>
