@php
    if (request()->is('admin*')) {
        $role = 'admin';
    } elseif (request()->is('instructor*')) {
        $role = 'instructor';
    } else {
        $role = auth('admin')->check() ? 'admin' : (auth()->check() && auth()->user()->role === 'instructor' ? 'instructor' : 'guest');
    }
@endphp

<aside id="desktop-sidebar" class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:flex flex-col flex-shrink-0 border-r border-slate-100 dark:border-gray-700 transition-colors duration-200">
    <div class="flex flex-col h-full">

        {{-- Logo Section --}}
        <div class="flex items-center justify-center h-16 px-6 border-b border-slate-100 dark:border-gray-700">
            <a href="{{ route($role . '.dashboard') }}" class="flex items-center group">
                <img src="{{ asset('assets/elearning/client/img/logo.png') }}"
                    class="w-auto h-8 md:h-10 object-contain transition-all duration-300 ease-in-out hover:scale-105"
                    alt="Sukarobot Logo" />
            </a>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-4 py-6 overflow-y-auto custom-scrollbar">
            @include('panel.layouts.menu-items')
        </nav>
    </div>
</aside>