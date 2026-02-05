@php
    if (request()->is('admin*')) {
        $role = 'admin';
    } elseif (request()->is('instructor*')) {
        $role = 'instructor';
    } else {
        $role = auth('admin')->check() ? 'admin' : (auth()->check() && auth()->user()->role === 'instructor' ? 'instructor' : 'guest');
    }
@endphp

{{-- Mobile Sidebar Overlay --}}
<div id="mobile-sidebar-overlay"
    class="hidden fixed inset-0 z-10 bg-black bg-opacity-50 transition-opacity duration-150"></div>

{{-- Mobile Sidebar --}}
<aside id="mobile-sidebar"
    class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 bg-white dark:bg-gray-800 md:hidden transition-transform duration-300 ease-in-out flex flex-col -translate-x-full">

    {{-- Main Navigation Content --}}
    <div class="flex-1 overflow-y-auto py-4 text-gray-500 dark:text-gray-400">
        <div class="ml-16">
            <img src="{{ asset('assets/elearning/client/img/logo.png') }}"
                class="w-auto h-8 md:h-10 object-contain transition-all duration-300 ease-in-out hover:scale-105"
                alt="Sukarobot Logo" />
        </div>

        <div class="mt-6 px-4">
            @include('panel.layouts.menu-items')
        </div>
    </div>
</aside>