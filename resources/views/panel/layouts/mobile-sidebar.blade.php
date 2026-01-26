@php
    if (request()->is('admin*')) {
        $role = 'admin';
    } elseif (request()->is('instructor*')) {
        $role = 'instructor';
    } else {
        $role = auth('admin')->check() ? 'admin' : (auth()->check() && auth()->user()->role === 'instructor' ? 'instructor' : 'guest');
    }
@endphp
<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>

<aside
    class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 bg-white dark:bg-gray-800 md:hidden transition-transform duration-300 ease-in-out flex flex-col"
    x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full" @click.away="closeSideMenu" @keydown.escape="closeSideMenu">

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

    {{-- Logout Section at Bottom --}}
    <div class="flex-shrink-0 p-4 border-t border-slate-100 dark:border-gray-700 bg-white dark:bg-gray-800">
        <form method="POST" action="{{ route('logout') }}" data-turbo="false">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>