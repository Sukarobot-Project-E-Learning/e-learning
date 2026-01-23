<!-- Mobile Sidebar Backdrop -->
<div
    x-show="isSideMenuOpen"
    x-cloak
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 flex items-end bg-black/50 sm:items-center sm:justify-center md:hidden backdrop-blur-sm"
    @click="closeSideMenu"
></div>

<!-- Mobile Sidebar Content -->
<aside
    class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto bg-white dark:bg-gray-800 md:hidden transition-transform duration-300 ease-in-out"
    :class="isSideMenuOpen ? 'translate-x-0' : '-translate-x-full'"
    x-show="isSideMenuOpen"
    x-cloak
    x-transition:enter="transition ease-in-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in-out duration-300"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform -translate-x-full"
    @keydown.escape="closeSideMenu"
>
    <div class="flex flex-col h-full">
        
        {{-- Header with Logo & Close --}}
        <div class="flex items-center justify-between h-16 px-6 border-b border-slate-100 dark:border-gray-700">
            <a href="{{ route('instructor.dashboard') }}" class="flex items-center group">
                 <img src="{{ asset('assets/elearning/client/img/logo.png') }}" alt="Sukarobot" class="h-8 w-auto">
            </a>
            <button @click="closeSideMenu" 
                    class="p-2 -mr-2 text-slate-400 dark:text-gray-500 rounded-md hover:bg-slate-100 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-4 py-6 overflow-y-auto">
            <ul class="space-y-2">
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('instructor.dashboard') }}"
                        @click="closeSideMenu"
                        class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                              {{ request()->routeIs('instructor.dashboard') 
                                 ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                 : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                    {{ request()->routeIs('instructor.dashboard') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('instructor.dashboard') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        Dashboard
                    </a>
                </li>

                {{-- Pengajuan Program --}}
                <li>
                    <a href="{{ route('instructor.programs.index') }}"
                        @click="closeSideMenu"
                        class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                              {{ request()->routeIs('instructor.programs.*') 
                                 ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                 : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                    {{ request()->routeIs('instructor.programs.*') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('instructor.programs.*') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        Pengajuan Program
                    </a>
                </li>

                {{-- Tugas/Post Test --}}
                <li>
                    <a href="{{ route('instructor.quizzes.index') }}"
                        @click="closeSideMenu"
                        class="group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200
                              {{ request()->routeIs('instructor.quizzes.*') 
                                 ? 'bg-blue-700 text-white shadow-lg shadow-blue-700/30' 
                                 : 'text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400' }}">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center mr-3 transition-all duration-200
                                    {{ request()->routeIs('instructor.quizzes.*') ? 'bg-white/20' : 'bg-blue-100 dark:bg-gray-700' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('instructor.quizzes.*') ? 'text-white' : 'text-blue-700 dark:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        Tugas/Post Test
                    </a>
                </li>

                <!-- Client Menu Divider -->
                <li class="pt-4 pb-2">
                    <div class="h-px bg-slate-200 dark:bg-gray-700 mx-4"></div>
                    <p class="px-4 py-2 text-xs font-semibold text-slate-400 dark:text-gray-500 uppercase tracking-wider">
                        Menu Utama
                    </p>
                </li>

                <!-- Home -->
                <li>
                    <a href="{{ url('/') }}" data-turbo="false" class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                </li>

                <!-- Program -->
                <li>
                    <a href="{{ url('/program') }}" data-turbo="false" class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Program
                    </a>
                </li>

                <!-- Kompetisi -->
                <li x-data="{ expanded: false }">
                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            Kompetisi
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="expanded" x-cloak class="pl-12 pr-4 space-y-1">
                        <a href="https://brc.sukarobot.com/" data-turbo="false" class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">BRC</a>
                        <a href="https://src.sukarobot.com/" data-turbo="false" class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">SRC</a>
                    </div>
                </li>

                <!-- Tentang -->
                <li x-data="{ expanded: false }">
                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Tentang Sukarobot
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="expanded" x-cloak class="pl-12 pr-4 space-y-1">
                        <a href="{{ url('/instruktur') }}" data-turbo="false" class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Instruktur</a>
                        <a href="{{ url('/tentang') }}" data-turbo="false" class="block py-2 text-sm text-slate-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Tentang Kami</a>
                    </div>
                </li>

                <!-- Artikel -->
                <li>
                    <a href="{{ url('/artikel') }}" data-turbo="false" class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        Artikel
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-slate-100 dark:border-gray-700">
             <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                @csrf
                <button type="submit" 
                        class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>
</aside>