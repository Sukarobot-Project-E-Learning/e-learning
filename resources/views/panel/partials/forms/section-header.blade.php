{{--
    Section Header Component
    
    Props:
    - title: string (required) - Section title
    - subtitle: string - Section subtitle/description
    - icon: string - SVG path for icon
    - color: string - Color theme (orange, blue, red, purple, green) default: orange
--}}

@php
    $colorClasses = [
        'orange' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400',
        'blue' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
        'red' => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
        'purple' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
        'green' => 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
    ];
    $selectedColor = $colorClasses[$color ?? 'orange'] ?? $colorClasses['orange'];
@endphp

<div class="flex items-center gap-3 mb-6">
    @if(!empty($icon))
        <div class="flex items-center justify-center w-10 h-10 rounded-xl {{ $selectedColor }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>
    @endif
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        @if(!empty($subtitle))
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
        @endif
    </div>
</div>
