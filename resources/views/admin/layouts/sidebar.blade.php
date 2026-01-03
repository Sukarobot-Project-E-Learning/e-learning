@php
    $admin = auth('admin')->user();
    $adminRoleLabel = $admin?->role === 'admin' ? 'Administrator' : ucfirst($admin?->role ?? 'Administrator');
    $adminAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($admin?->name ?? 'Admin') . '&background=f97316&color=fff';
@endphp

<aside class="z-20 hidden w-72 overflow-y-auto sidebar-scroll bg-gradient-to-b from-white via-orange-50/30 to-white dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 md:block flex-shrink-0 border-r border-orange-100 dark:border-orange-900/30 shadow-xl shadow-orange-100/50 dark:shadow-orange-900/20">
    <div class="py-6 text-gray-600 dark:text-gray-300">

        <!-- Logo Section with Decorative Border -->
        <div class="relative px-6 pb-6">
            <div class="flex justify-center p-4 rounded-2xl">
                <img src="{{ asset('assets/elearning/client/img/logo.png') }}"
                    class="inline w-auto h-12 md:h-10 lg:h-10 align-middle items-center object-contain transition-all duration-300 ease-in-out hover:scale-105 drop-shadow-md"
                    alt="main_logo" />
            </div>
        </div>

        <!-- Section Divider -->
        <div class="mx-6 my-4">
            <div class="h-px shimmer-divider"></div>
        </div>

        <ul class="space-y-1">
            <!-- Dashboard -->
            <li class="relative px-4 py-1 group">
                <a data-turbo="false" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ request()->routeIs('admin.dashboard') 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 hover:translate-x-1' }}"
                    href="{{ route('admin.dashboard') }}">
                    @if(request()->routeIs('admin.dashboard'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <div class="p-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/40' }} transition-colors duration-300">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span class="ml-3">Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                    <span class="ml-auto flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    @endif
                </a>
            </li>

            <!-- Akun (Dropdown) -->
            <li class="relative px-4 py-1" x-data="{ isOpen: {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) ? 'true' : 'false' }} }">
                <button class="flex items-center justify-between w-full px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400' }}"
                    @click="isOpen = !isOpen"
                    aria-haspopup="true">
                    @if(request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <span class="inline-flex items-center">
                        <div class="p-2 rounded-lg {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Akun</span>
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': isOpen }" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="isOpen"
                    x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition-all ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                    class="mt-2 ml-4 pl-4 border-l-2 border-orange-200 dark:border-orange-800 space-y-1"
                    aria-label="submenu">
                    <a href="{{ route('admin.users.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.users.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Users
                        </span>
                    </a>
                    <a href="{{ route('admin.admins.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.admins.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.admins.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Admin
                        </span>
                    </a>
                    <a href="{{ route('admin.instructors.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.instructors.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.instructors.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Instruktur
                        </span>
                    </a>
                </div>
            </li>

            <!-- Program (Dropdown) -->
            <li class="relative px-4 py-1" x-data="{ isOpen: {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'true' : 'false' }} }">
                <button class="flex items-center justify-between w-full px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400' }}"
                    @click="isOpen = !isOpen"
                    aria-haspopup="true">
                    @if(request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <span class="inline-flex items-center">
                        <div class="p-2 rounded-lg {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Program</span>
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': isOpen }" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="isOpen"
                    x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition-all ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                    class="mt-2 ml-4 pl-4 border-l-2 border-orange-200 dark:border-orange-800 space-y-1"
                    aria-label="submenu">
                    <a href="{{ route('admin.programs.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.programs.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.programs.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Program
                        </span>
                    </a>
                    <a href="{{ route('admin.program-approvals.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.program-approvals.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.program-approvals.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Pengajuan Program
                        </span>
                    </a>
                    <a href="{{ route('admin.program-proofs.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.program-proofs.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.program-proofs.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Bukti Program
                        </span>
                    </a>
                    <a href="{{ route('admin.certificates.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.certificates.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.certificates.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Sertifikat
                        </span>
                    </a>
                </div>
            </li>

            <!-- Promosi (Dropdown) -->
            <li class="relative px-4 py-1" x-data="{ isOpen: {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'true' : 'false' }} }">
                <button class="flex items-center justify-between w-full px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400' }}"
                    @click="isOpen = !isOpen"
                    aria-haspopup="true">
                    @if(request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <span class="inline-flex items-center">
                        <div class="p-2 rounded-lg {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Promosi</span>
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': isOpen }" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="isOpen"
                    x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition-all ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                    class="mt-2 ml-4 pl-4 border-l-2 border-orange-200 dark:border-orange-800 space-y-1"
                    aria-label="submenu">
                    <a href="{{ route('admin.promos.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.promos.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.promos.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Promo
                        </span>
                    </a>
                    <a href="{{ route('admin.vouchers.index') }}"
                        class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.vouchers.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold border-l-2 border-orange-500' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 hover:translate-x-1' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full {{ request()->routeIs('admin.vouchers.*') ? 'bg-orange-500' : 'bg-orange-300' }} mr-3"></span>
                            Voucher
                        </span>
                    </a>
                </div>
            </li>

            <!-- Artikel -->
            <li class="relative px-4 py-1 group">
                <a class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ request()->routeIs('admin.articles.*') 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 hover:translate-x-1' }}"
                    href="{{ route('admin.articles.index') }}">
                    @if(request()->routeIs('admin.articles.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <div class="p-2 rounded-lg {{ request()->routeIs('admin.articles.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/40' }} transition-colors duration-300">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.articles.*') ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                    <span class="ml-3">Artikel</span>
                </a>
            </li>

            <!-- Broadcast -->
            <li class="relative px-4 py-1 group">
                <a class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ request()->routeIs('admin.broadcasts.*') 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 hover:translate-x-1' }}"
                    href="{{ route('admin.broadcasts.index') }}">
                    @if(request()->routeIs('admin.broadcasts.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <div class="p-2 rounded-lg {{ request()->routeIs('admin.broadcasts.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/40' }} transition-colors duration-300">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.broadcasts.*') ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                        </svg>
                    </div>
                    <span class="ml-3">Broadcast</span>
                </a>
            </li>

            <!-- Laporan -->
            <li class="relative px-4 py-1 group">
                <a class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ request()->routeIs('admin.reports.*') 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 hover:translate-x-1' }}"
                    href="{{ route('admin.reports.index') }}">
                    @if(request()->routeIs('admin.reports.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <div class="p-2 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/40' }} transition-colors duration-300">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="ml-3">Laporan</span>
                </a>
            </li>

            <!-- Transaksi -->
            <li class="relative px-4 py-1 group">
                <a class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ request()->routeIs('admin.transactions.*') 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 hover:translate-x-1' }}"
                    href="{{ route('admin.transactions.index') }}">
                    @if(request()->routeIs('admin.transactions.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <div class="p-2 rounded-lg {{ request()->routeIs('admin.transactions.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/40' }} transition-colors duration-300">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.transactions.*') ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <span class="ml-3">Transaksi</span>
                </a>
            </li>

            <!-- Tugas/Postest -->
            <li class="relative px-4 py-1 group">
                <a class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                    {{ request()->routeIs('admin.quizzes.*') 
                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:text-orange-600 dark:hover:text-orange-400 hover:translate-x-1' }}"
                    href="{{ route('admin.quizzes.index') }}">
                    @if(request()->routeIs('admin.quizzes.*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-orange-400 rounded-r-full active-pulse"></span>
                    @endif
                    <div class="p-2 rounded-lg {{ request()->routeIs('admin.quizzes.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/40' }} transition-colors duration-300">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.quizzes.*') ? 'text-white' : 'text-orange-500' }}" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="ml-3">Tugas/Postest</span>
                </a>
            </li>
        </ul>

        <!-- Bottom Section with User Profile Card -->
        <div class="px-4 mt-8">
            <div class="h-px shimmer-divider mb-6"></div>
            <div class="p-4 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/30 dark:to-gray-800 border border-orange-200/50 dark:border-orange-800/30">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <img class="w-10 h-10 rounded-full ring-2 ring-orange-300 dark:ring-orange-600 object-cover shadow-lg shadow-orange-500/30"
                            src="{{ $adminAvatar }}"
                            alt="{{ $admin?->name ?? 'Admin' }}">
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $admin?->name ?? 'Admin' }}</p>
                        <p class="text-xs text-orange-500 dark:text-orange-400">{{ $adminRoleLabel }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</aside>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    orange: {
                        50: '#fff7ed',
                        100: '#ffedd5',
                        200: '#fed7aa',
                        300: '#fdba74',
                        400: '#fb923c',
                        500: '#f97316',
                        600: '#ea580c',
                        700: '#c2410c',
                        800: '#9a3412',
                        900: '#7c2d12',
                    }
                }
            }
        }
    }
</script>
<!-- Turbo Drive for seamless page navigation -->
<style>
    turbo-progress-bar {
        background: linear-gradient(to right, #f97316, #ea580c);
        height: 3px;
        box-shadow: 0 0 10px rgba(249, 115, 22, 0.3);
    }

    /* SIDEBAR */
    .sidebar-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: #fdba74;
        border-radius: 20px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: #f97316;
    }

    .nav-glow {
        box-shadow: 0 0 15px rgba(249, 115, 22, 0.3);
    }

    .gradient-border {
        background: linear-gradient(180deg, rgba(249, 115, 22, 0.1) 0%, rgba(249, 115, 22, 0.05) 100%);
    }

    @keyframes pulse-glow {

        0%,
        100% {
            box-shadow: 0 0 5px rgba(249, 115, 22, 0.5);
        }

        50% {
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.8);
        }
    }

    .active-pulse {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    .shimmer-divider {
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.3), transparent);
        background-size: 200% 100%;
        animation: shimmer 3s ease-in-out infinite;
    }

</style>
<script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
<script>
    // Show the progress bar immediately on navigation
    Turbo.setProgressBarDelay(0);

    // Reinitialize Alpine components when Turbo loads a new page
    document.addEventListener('turbo:load', () => {
        if (window.Alpine && typeof window.Alpine.initTree === 'function') {
            window.Alpine.initTree(document.body);
        }

        // Restore sidebar scroll position after Turbo navigation
        try {
            const saved = sessionStorage.getItem('sidebar:scrollTop');
            const sidebar = document.querySelector('.sidebar-scroll');
            if (sidebar && saved !== null) {
                sidebar.scrollTop = Number(saved) || 0;
            }
        } catch (_) {}
    });

    // Optional: ensure active state in sidebar updates on Turbo navigation
    document.addEventListener('turbo:render', () => {
        // If any custom logic is needed, hook it here.
    });

    // Save sidebar scroll just before navigating away
    document.addEventListener('turbo:before-visit', () => {
        try {
            const sidebar = document.querySelector('.sidebar-scroll');
            if (sidebar) {
                sessionStorage.setItem('sidebar:scrollTop', String(sidebar.scrollTop));
            }
        } catch (_) {}
    });
</script>