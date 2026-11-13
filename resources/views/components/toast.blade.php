@php
    $toasts = [];
    if (session('toast')) {
        $toasts[] = session('toast');
    }
@endphp

@if (count($toasts))
    <div class="toast-stack fixed right-4 top-4 z-[60] flex w-[calc(100vw-2rem)] max-w-[360px] flex-col gap-3 sm:right-6 sm:top-6" role="region" aria-label="Pemberitahuan">
        @foreach ($toasts as $toast)
            @php
                $jenis = $toast['jenis'] ?? 'sukses';
                $peta = [
                    'sukses' => ['icon' => 'circle-check', 'kelas' => 'border-emerald-200', 'ikonWarna' => 'text-emerald-500', 'judulWarna' => 'text-emerald-800'],
                    'galat' => ['icon' => 'circle-x', 'kelas' => 'border-rose-200', 'ikonWarna' => 'text-rose-500', 'judulWarna' => 'text-rose-800'],
                    'peringatan' => ['icon' => 'triangle-alert', 'kelas' => 'border-amber-200', 'ikonWarna' => 'text-amber-500', 'judulWarna' => 'text-amber-800'],
                    'info' => ['icon' => 'info', 'kelas' => 'border-sky-200', 'ikonWarna' => 'text-sky-500', 'judulWarna' => 'text-sky-800'],
                ];
                $gaya = $peta[$jenis] ?? $peta['sukses'];
            @endphp

            <div data-toast class="animate-toast-in relative overflow-hidden rounded-xl border {{ $gaya['kelas'] }} bg-white p-4 shadow-[0_14px_40px_rgba(15,40,35,.16)]">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 shrink-0 {{ $gaya['ikonWarna'] }}"><i data-lucide="{{ $gaya['icon'] }}" class="size-5"></i></span>
                    <div class="min-w-0 flex-1">
                        @if (! empty($toast['judul']))
                            <p class="text-[13px] font-semibold {{ $gaya['judulWarna'] }}">{{ $toast['judul'] }}</p>
                        @endif
                        @if (! empty($toast['pesan']))
                            <p class="mt-0.5 text-[12px] leading-5 text-slate-500">{{ $toast['pesan'] }}</p>
                        @endif
                    </div>
                    <button type="button" data-toast-tutup class="shrink-0 rounded-md p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600" aria-label="Tutup pemberitahuan">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
                <span class="absolute inset-x-0 bottom-0 h-[3px] bg-current opacity-20" data-toast-progress></span>
            </div>
        @endforeach
    </div>
@endif
