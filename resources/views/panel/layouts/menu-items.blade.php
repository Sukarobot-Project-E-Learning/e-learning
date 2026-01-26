@php
    // Determine Role Logic (Route Priority > Auth Priority)
    if (request()->is('admin*')) {
        $role = 'admin';
    } elseif (request()->is('instructor*')) {
        $role = 'instructor';
    } else {
        $role = auth('admin')->check() ? 'admin' : (auth()->check() && auth()->user()->role === 'instructor' ? 'instructor' : 'guest');
    }

    // Define color classes based on role
    $activeClass = $role === 'admin'
        ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30'
        : 'bg-blue-700 text-white shadow-lg shadow-blue-700/30';

    $inactiveClass = $role === 'admin'
        ? 'text-slate-600 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-orange-600 dark:hover:text-orange-400'
        : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400';

    $iconActiveWrapper = 'bg-white/20';
    $iconInactiveWrapper = $role === 'admin'
        ? 'bg-orange-100 dark:bg-gray-700 group-hover:bg-orange-200 dark:group-hover:bg-gray-600'
        : 'bg-blue-100 dark:bg-gray-700 group-hover:bg-blue-200 dark:group-hover:bg-gray-600';

    $iconActive = 'text-white';
    $iconInactive = $role === 'admin' ? 'text-orange-600 dark:text-orange-400' : 'text-blue-700 dark:text-blue-400';

    $expandedBg = $role === 'admin' ? 'bg-orange-50 dark:bg-gray-700/50 text-orange-700 dark:text-orange-400' : 'bg-blue-50 dark:bg-gray-700/50 text-blue-700 dark:text-blue-400';
@endphp

<ul class="space-y-2">
    {{-- Dashboard --}}
    <li>
        <a href="{{ route($role . '.dashboard') }}" data-turbo="false" class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                  {{ request()->routeIs($role . '.dashboard') ? $activeClass : $inactiveClass }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                        {{ request()->routeIs($role . '.dashboard') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                <svg class="w-5 h-5 {{ request()->routeIs($role . '.dashboard') ? $iconActive : $iconInactive }}"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </div>
            Dashboard
        </a>
    </li>

    @if($role === 'admin')
        {{-- Akun (Dropdown) --}}
        <li
            x-data="{ expanded: {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*') || request()->routeIs('admin.instructor-applications.*')) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                        {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*') || request()->routeIs('admin.instructor-applications.*'))
            ? $expandedBg
            : $inactiveClass }}">
                <div class="flex items-center">
                    <div
                        class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                    {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*') || request()->routeIs('admin.instructor-applications.*')) ? 'bg-orange-100 dark:bg-gray-700' : 'bg-orange-100 dark:bg-gray-700' }}">
                        <svg class="w-5 h-5 {{ (request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') || request()->routeIs('admin.instructors.*') || request()->routeIs('admin.instructor-applications.*')) ? $iconInactive : $iconInactive }}"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    Akun
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="expanded" x-cloak x-collapse class="space-y-1 mt-1">
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Users
                </a>
                <a href="{{ route('admin.admins.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.admins.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Admin
                </a>
                <a href="{{ route('admin.instructors.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.instructors.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Instruktur
                </a>
                <a href="{{ route('admin.instructor-applications.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.instructor-applications.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Pengajuan Instruktur
                </a>
            </div>
        </li>

        {{-- Program (Dropdown) --}}
        <li
            x-data="{ expanded: {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                        {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*'))
            ? $expandedBg
            : $inactiveClass }}">
                <div class="flex items-center">
                    <div
                        class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                    {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? 'bg-orange-100 dark:bg-gray-700' : 'bg-orange-100 dark:bg-gray-700' }}">
                        <svg class="w-5 h-5 {{ (request()->routeIs('admin.programs.*') || request()->routeIs('admin.program-approvals.*') || request()->routeIs('admin.program-proofs.*') || request()->routeIs('admin.certificates.*')) ? $iconInactive : $iconInactive }}"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    Program
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="expanded" x-cloak x-collapse class="space-y-1 mt-1">
                <a href="{{ route('admin.programs.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.programs.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Program
                </a>
                <a href="{{ route('admin.program-approvals.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.program-approvals.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Pengajuan Program
                </a>
                <a href="{{ route('admin.program-proofs.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.program-proofs.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Bukti Program
                </a>
                <a href="{{ route('admin.certificates.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.certificates.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Sertifikat
                </a>
            </div>
        </li>

        {{-- Promosi (Dropdown) --}}
        <li
            x-data="{ expanded: {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                        {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*'))
            ? $expandedBg
            : $inactiveClass }}">
                <div class="flex items-center">
                    <div
                        class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                    {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? 'bg-orange-100 dark:bg-gray-700' : 'bg-orange-100 dark:bg-gray-700' }}">
                        <svg class="w-5 h-5 {{ (request()->routeIs('admin.promos.*') || request()->routeIs('admin.vouchers.*')) ? $iconInactive : $iconInactive }}"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    Promosi
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="expanded" x-cloak x-collapse class="space-y-1 mt-1">
                <a href="{{ route('admin.promos.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.promos.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Promo
                </a>
                <a href="{{ route('admin.vouchers.index') }}"
                    class="flex items-center pl-16 pr-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.vouchers.*') ? 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-gray-700/50' : 'text-slate-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-gray-700/30' }}">
                    Voucher
                </a>
            </div>
        </li>

        {{-- Artikel --}}
        <li>
            <a href="{{ route('admin.articles.index') }}" class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                          {{ request()->routeIs('admin.articles.*') ? $activeClass : $inactiveClass }}">
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                {{ request()->routeIs('admin.articles.*') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.articles.*') ? $iconActive : $iconInactive }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                Artikel
            </a>
        </li>

        {{-- Broadcast --}}
        <li>
            <a href="{{ route('admin.broadcasts.index') }}" class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                          {{ request()->routeIs('admin.broadcasts.*') ? $activeClass : $inactiveClass }}">
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                {{ request()->routeIs('admin.broadcasts.*') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.broadcasts.*') ? $iconActive : $iconInactive }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                    </svg>
                </div>
                Broadcast
            </a>
        </li>

        {{-- Laporan --}}
        <li>
            <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                          {{ request()->routeIs('admin.reports.*') ? $activeClass : $inactiveClass }}">
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                {{ request()->routeIs('admin.reports.*') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? $iconActive : $iconInactive }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                Laporan
            </a>
        </li>

        {{-- Transaksi --}}
        <li>
            <a href="{{ route('admin.transactions.index') }}"
                class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                          {{ request()->routeIs('admin.transactions.*') ? $activeClass : $inactiveClass }}">
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                {{ request()->routeIs('admin.transactions.*') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.transactions.*') ? $iconActive : $iconInactive }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                Transaksi
            </a>
        </li>

        {{-- Tugas/Post Test --}}
        <li>
            <a href="{{ route('admin.quizzes.index') }}" class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                          {{ request()->routeIs('admin.quizzes.*') ? $activeClass : $inactiveClass }}">
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                {{ request()->routeIs('admin.quizzes.*') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.quizzes.*') ? $iconActive : $iconInactive }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                Tugas/Post Test
            </a>
        </li>
    @else
        {{-- Instructor Menu --}}

        {{-- Program Saya --}}
        <li>
            <a href="{{ route('instructor.programs.index') }}"
                class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                          {{ request()->routeIs('instructor.programs.*') ? $activeClass : $inactiveClass }}">
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                {{ request()->routeIs('instructor.programs.*') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('instructor.programs.*') ? $iconActive : $iconInactive }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                Pengajuan Program
            </a>
        </li>

        {{-- Tugas/Post Test --}}
        <li>
            <a href="{{ route('instructor.quizzes.index') }}"
                class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                                          {{ request()->routeIs('instructor.quizzes.*') ? $activeClass : $inactiveClass }}">
                <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                                {{ request()->routeIs('instructor.quizzes.*') ? $iconActiveWrapper : $iconInactiveWrapper }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('instructor.quizzes.*') ? $iconActive : $iconInactive }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                Tugas/Post Test
            </a>
        </li>

        {{-- Client Menu Section - MOBILE ONLY --}}
        <div class="md:hidden">
            <!-- Client Menu Divider -->
            <li class="pt-4 pb-2">
                <div class="h-px bg-slate-200 dark:bg-gray-700 mx-4"></div>
                <p class="px-4 py-2 text-xs font-semibold text-slate-400 dark:text-gray-500 uppercase tracking-wider">
                    Menu Utama
                </p>
            </li>

            <!-- Home -->
            <li>
                <a href="{{ url('/') }}" data-turbo="false"
                    class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
            </li>

            <!-- Program -->
            <li>
                <a href="{{ url('/program') }}" data-turbo="false"
                    class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Program
                </a>
            </li>

            <!-- Kompetisi -->
            <li x-data="{ expanded: false }">
                <button @click="expanded = !expanded"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Kompetisi
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="expanded" x-cloak class="pl-12 pr-4 space-y-1">
                    <a href="https://brc.sukarobot.com/" data-turbo="false"
                        class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">BRC</a>
                    <a href="https://src.sukarobot.com/" data-turbo="false"
                        class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">SRC</a>
                </div>
            </li>

            <!-- Tentang -->
            <li x-data="{ expanded: false }">
                <button @click="expanded = !expanded"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tentang Sukarobot
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="expanded" x-cloak class="pl-12 pr-4 space-y-1">
                    <a href="{{ url('/instruktur') }}" data-turbo="false"
                        class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Instruktur</a>
                    <a href="{{ url('/tentang') }}" data-turbo="false"
                        class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Tentang
                        Kami</a>
                </div>
            </li>

            <!-- Artikel -->
            <li>
                <a href="{{ url('/artikel') }}" data-turbo="false"
                    class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Artikel
                </a>
            </li>
        </div>
        {{-- End Client Menu Section --}}
    @endif
</ul>