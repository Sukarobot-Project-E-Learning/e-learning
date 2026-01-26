@php
    if (request()->is('admin*')) {
        $role = 'admin';
    } elseif (request()->is('instructor*')) {
        $role = 'instructor';
    } else {
        $role = auth('admin')->check() ? 'admin' : (auth()->check() && auth()->user()->role === 'instructor' ? 'instructor' : 'guest');
    }
@endphp

<aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:flex flex-col flex-shrink-0 border-r border-slate-100 dark:border-gray-700 transition-colors duration-200">
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

        {{-- Logout Section --}}
        <div class="p-4 border-t border-slate-100 dark:border-gray-700">
             <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                @csrf
                <button type="submit" 
                        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>
</aside>