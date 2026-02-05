{{--
    Action Buttons Component
    
    Props:
    - backUrl: string (required) - URL for back button
    - backText: string - Back button text (default: Kembali)
    - submitText: string - Submit button text (default: Simpan)
    - submitIcon: string - SVG path for submit icon
    - sticky: bool - Whether to make footer sticky (default: true)
    - formId: string - Form ID to target when button is outside form (uses HTML5 form attribute)
--}}

@php
    $isSticky = $sticky ?? true;
    $backLabel = $backText ?? 'Kembali';
    $submitLabel = $submitText ?? 'Simpan';
    $targetFormId = $formId ?? null;
@endphp

<div class="{{ $isSticky ? 'sticky bottom-0' : '' }} px-5 sm:px-8 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between gap-4">
        <a href="{{ $backUrl }}"
            class="flex items-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 active:scale-95 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="hidden sm:inline">{{ $backLabel }}</span>
        </a>

        <button type="submit" @if($targetFormId) form="{{ $targetFormId }}" @endif
            class="flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl hover:from-orange-600 hover:to-orange-700 active:scale-95 transition-all shadow-lg shadow-orange-500/30">
            @if(!empty($submitIcon))
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $submitIcon !!}
                </svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @endif
            <span>{{ $submitLabel }}</span>
        </button>
    </div>
</div>
