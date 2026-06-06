@extends('panel.layouts.app')

@section('title', 'Rekap Program')

@section('content')
    <div class="container px-6 mx-auto max-w-full">
        <div class="flex flex-col gap-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <a href="{{ route('admin.rekap-program.index') }}" class="text-sm font-semibold text-orange-600 dark:text-orange-400 inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Rekap
                        </a>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mt-3">{{ $program->title }}</h1>
                        <p class="text-sm text-slate-500 dark:text-gray-400">{{ $scheduleText }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold uppercase tracking-wide text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30 px-3 py-1 rounded-full">
                            {{ $program->category ?? 'Lainnya' }}
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-gray-300 bg-slate-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            {{ $program->type ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            @include('admin.rekap-program.partials._stats')

            @include('admin.rekap-program.partials._table')
        </div>
    </div>
@endsection
