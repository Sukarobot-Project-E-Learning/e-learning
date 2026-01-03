<!-- Mobile sidebar -->
<!-- Backdrop -->
<div x-show="isSideMenuOpen"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center md:hidden"
    x-cloak>
</div>

<aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
    x-show="isSideMenuOpen"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20"
    x-cloak>

    <!-- Scrollable Content -->
    <div class="h-full overflow-y-auto mobile-scroll pb-24">
        <div class="py-6 text-gray-600 dark:text-gray-300">

            <!-- Logo Section -->
            <div class="relative px-5 pb-5 pt-2">
                <div class="flex justify-center p-4 rounded-2xl">
                    <img src="{{ asset('assets/elearning/client/img/logo.png') }}"
                        class="inline w-auto h-10 align-middle items-center object-contain transition-all duration-300 ease-in-out hover:scale-105 drop-shadow-md"
                        alt="main_logo" />
                </div>
            </div>

            <!-- Section Divider -->
            <div class="mx-5 my-4">
                <div class="h-px shimmer-divider"></div>
            </div>

            <ul class="space-y-1">
                <!-- Dashboard -->
                <li class="relative px-3 py-0.5 menu-item">
                    <a class="flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ request()->routeIs('admin.dashboard') 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100 dark:active:bg-orange-900/40' }}"
                        href="{{ route('admin.dashboard') }}">
                        @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <div class="p-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <span class="ml-3 flex-1">Dashboard</span>
                        @if(request()->routeIs('admin.dashboard'))
                        <span class="flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-2.5 w-2.5 rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                        </span>
                        @endif
                    </a>
                </li>


                <!-- Akun (Dropdown) -->
                <li class="relative px-3 py-0.5 menu-item" x-data="{ isOpen: {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) ? 'true' : 'false' }} }">
                    <button class="flex items-center justify-between w-full px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        @click="isOpen = !isOpen">
                        @if(request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <span class="inline-flex items-center">
                            <div class="p-2.5 rounded-xl {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                                <svg class="w-5 h-5 {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <span class="ml-3">Akun</span>
                        </span>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*')) ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600' }}">3</span>
                            <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>
                    <div x-show="isOpen"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-48"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 max-h-48"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="overflow-hidden mt-2 ml-4 pl-4 border-l-2 border-orange-200 dark:border-orange-800 space-y-1">
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3 transition-all"></span>
                            <span>Users</span>
                            @if(request()->routeIs('admin.users.*'))
                            <svg class="w-4 h-4 ml-auto text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </a>
                        <a href="{{ route('admin.admins.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.admins.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.admins.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3 transition-all"></span>
                            <span>Admin</span>
                        </a>
                        <a href="{{ route('admin.instructors.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.instructors.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.instructors.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3 transition-all"></span>
                            <span>Instruktur</span>
                        </a>
                    </div>
                </li>

                <!-- Program (Dropdown) -->
                <li class="relative px-3 py-0.5 menu-item" x-data="{ isOpen: {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'true' : 'false' }} }">
                    <button class="flex items-center justify-between w-full px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        @click="isOpen = !isOpen">
                        @if(request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <span class="inline-flex items-center">
                            <div class="p-2.5 rounded-xl {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                                <svg class="w-5 h-5 {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="ml-3">Program</span>
                        </span>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600' }}">4</span>
                            <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>
                    <div x-show="isOpen"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-64"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 max-h-64"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="overflow-hidden mt-2 ml-4 pl-4 border-l-2 border-orange-200 dark:border-orange-800 space-y-1">
                        <a href="{{ route('admin.programs.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.programs.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.programs.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3"></span>
                            <span>Program</span>
                        </a>
                        <a href="{{ route('admin.program-approvals.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.program-approvals.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.program-approvals.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3"></span>
                            <span>Pengajuan Program</span>
                            <span class="ml-auto px-2 py-0.5 text-xs bg-red-500 text-white rounded-full animate-pulse">5</span>
                        </a>
                        <a href="{{ route('admin.program-proofs.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.program-proofs.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.program-proofs.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3"></span>
                            <span>Bukti Program</span>
                        </a>
                        <a href="{{ route('admin.certificates.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.certificates.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.certificates.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3"></span>
                            <span>Sertifikat</span>
                        </a>
                    </div>
                </li>

                <!-- Promosi (Dropdown) -->
                <li class="relative px-3 py-0.5 menu-item" x-data="{ isOpen: {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'true' : 'false' }} }">
                    <button class="flex items-center justify-between w-full px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        @click="isOpen = !isOpen">
                        @if(request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <span class="inline-flex items-center">
                            <div class="p-2.5 rounded-xl {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                                <svg class="w-5 h-5 {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <span class="ml-3">Promosi</span>
                        </span>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600' }}">2</span>
                            <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>
                    <div x-show="isOpen"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-32"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 max-h-32"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="overflow-hidden mt-2 ml-4 pl-4 border-l-2 border-orange-200 dark:border-orange-800 space-y-1">
                        <a href="{{ route('admin.promos.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.promos.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.promos.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3"></span>
                            <span>Promo</span>
                        </a>
                        <a href="{{ route('admin.vouchers.index') }}"
                            class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.vouchers.*') ? 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-semibold' : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ request()->routeIs('admin.vouchers.*') ? 'bg-orange-500 ring-4 ring-orange-200' : 'bg-orange-300' }} mr-3"></span>
                            <span>Voucher</span>
                        </a>
                    </div>
                </li>

                <!-- Artikel -->
                <li class="relative px-3 py-0.5 menu-item">
                    <a class="flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ request()->routeIs('admin.articles.*') 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        href="{{ route('admin.articles.index') }}">
                        @if(request()->routeIs('admin.articles.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <div class="p-2.5 rounded-xl {{ request()->routeIs('admin.articles.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.articles.*') ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Artikel</span>
                    </a>
                </li>

                <!-- Broadcast -->
                <li class="relative px-3 py-0.5 menu-item">
                    <a class="flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ request()->routeIs('admin.broadcasts.*') 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        href="{{ route('admin.broadcasts.index') }}">
                        @if(request()->routeIs('admin.broadcasts.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <div class="p-2.5 rounded-xl {{ request()->routeIs('admin.broadcasts.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.broadcasts.*') ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Broadcast</span>
                    </a>
                </li>

                <!-- Laporan -->
                <li class="relative px-3 py-0.5 menu-item">
                    <a class="flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ request()->routeIs('admin.reports.*') 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        href="{{ route('admin.reports.index') }}">
                        @if(request()->routeIs('admin.reports.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <div class="p-2.5 rounded-xl {{ request()->routeIs('admin.reports.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Laporan</span>
                    </a>
                </li>

                <!-- Transaksi -->
                <li class="relative px-3 py-0.5 menu-item">
                    <a class="flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ request()->routeIs('admin.transactions.*') 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        href="{{ route('admin.transactions.index') }}">
                        @if(request()->routeIs('admin.transactions.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <div class="p-2.5 rounded-xl {{ request()->routeIs('admin.transactions.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.transactions.*') ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Transaksi</span>
                    </a>
                </li>

                <!-- Tugas/Postest -->
                <li class="relative px-3 py-0.5 menu-item">
                    <a class="flex items-center px-4 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 ease-in-out
                            {{ request()->routeIs('admin.quizzes.*') 
                                ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 nav-glow' 
                                : 'hover:bg-orange-50 dark:hover:bg-orange-900/20 active:bg-orange-100' }}"
                        href="{{ route('admin.quizzes.index') }}">
                        @if(request()->routeIs('admin.quizzes.*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-10 bg-orange-400 rounded-r-full active-pulse"></span>
                        @endif
                        <div class="p-2.5 rounded-xl {{ request()->routeIs('admin.quizzes.*') ? 'bg-white/20' : 'bg-orange-100 dark:bg-orange-900/30' }} transition-colors duration-300">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.quizzes.*') ? 'text-white' : 'text-orange-500' }}" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="ml-3">Tugas/Postest</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Fixed Bottom User Profile Card -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-white via-white to-transparent dark:from-gray-800 dark:via-gray-800">
        <div class="p-4 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/30 dark:to-gray-800 border border-orange-200/50 dark:border-orange-800/30 shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200 truncate">Admin</p>
                    <p class="text-xs text-orange-500 dark:text-orange-400">Administrator</p>
                </div>
                <button class="p-2 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 hover:bg-orange-200 dark:hover:bg-orange-800/40 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>


</aside>

<style>
    @keyframes pulse-glow {

        0%,
        100% {
            box-shadow: 0 0 5px rgba(249, 115, 22, 0.3);
        }

        50% {
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.6);
        }
    }

    .animate-pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    @supports not (backdrop-filter: blur(12px)) {
        .backdrop-blur-md {
            background-color: rgba(255, 255, 255, 0.95);
        }

        .dark .backdrop-blur-md {
            background-color: rgba(31, 41, 55, 0.95);
        }
    }

    ul::-webkit-scrollbar {
        width: 4px;
    }

    ul::-webkit-scrollbar-track {
        background: transparent;
    }

    ul::-webkit-scrollbar-thumb {
        background: rgba(249, 115, 22, 0.3);
        border-radius: 2px;
    }

    ul::-webkit-scrollbar-thumb:hover {
        background: rgba(249, 115, 22, 0.5);
    }

    /* Hide elements with x-cloak until Alpine initializes */
    [x-cloak] {
        display: none !important;
    }
</style>

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
<script>
    // Restore mobile sidebar scroll after Turbo navigation
    document.addEventListener('turbo:load', () => {
        try {
            const saved = sessionStorage.getItem('mobile-sidebar:scrollTop');
            const mobile = document.querySelector('.mobile-scroll');
            if (mobile && saved !== null) {
                mobile.scrollTop = Number(saved) || 0;
            }
        } catch (_) {}
    });

    // Save mobile sidebar scroll just before navigating away
    document.addEventListener('turbo:before-visit', () => {
        try {
            const mobile = document.querySelector('.mobile-scroll');
            if (mobile) {
                sessionStorage.setItem('mobile-sidebar:scrollTop', String(mobile.scrollTop));
            }
        } catch (_) {}
    });
</script>