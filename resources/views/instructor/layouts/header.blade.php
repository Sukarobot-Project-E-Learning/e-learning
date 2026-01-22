<header class="z-40 bg-white dark:bg-gray-800 border-b border-slate-100 dark:border-gray-700 transition-colors duration-200" 
         x-data="headerComponent()" 
         x-init="initHeader()"
         @keydown.escape="closeAll()"
         @scroll.window="handleScroll()">
    {{-- Top Bar --}}
    <div class="flex items-center justify-between h-16 px-6">
        
        {{-- Left Section: Mobile Menu Button + Dark Mode Toggle + Navigation --}}
        <div class="flex items-center space-x-4">
            {{-- Mobile hamburger button --}}
            <button @click="$dispatch('toggle-mobile-sidebar')"
                    class="p-2.5 text-slate-500 dark:text-gray-400 rounded-xl md:hidden hover:bg-slate-100 dark:hover:bg-gray-700 hover:text-slate-700 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-700 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Dark/Light Mode Toggle --}}
            <button @click="toggleDarkMode()"
                    class="relative p-2.5 text-blue-700 dark:text-blue-400 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-700 transition-all duration-300 group">
                {{-- Sun Icon (Light Mode) --}}
                <svg x-show="!isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                {{-- Moon Icon (Dark Mode) --}}
                <svg x-show="isDarkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            {{-- Navigation Links (Desktop) --}}
            <nav class="hidden md:flex items-center space-x-1 ml-4 pl-4 border-l border-slate-200 dark:border-gray-600">
                {{-- Home --}}
                <a href="{{ url('/') }}" data-turbo="false" class="px-3 py-2 text-sm font-medium text-slate-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    Home
                </a>

                {{-- Program --}}
                <a href="{{ url('/program') }}" data-turbo="false" class="px-3 py-2 text-sm font-medium text-slate-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                    Program
                </a>

                {{-- Kompetisi Dropdown --}}
                <div class="relative" @mouseenter="kompetisiOpen = true" @mouseleave="kompetisiOpen = false">
                    <button class="flex items-center px-3 py-2 text-sm font-medium text-slate-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        Kompetisi
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="kompetisiOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="kompetisiOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute left-0 mt-1 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-100 dark:border-gray-700 py-2 z-50">
                        <a href="https://brc.sukarobot.com/" class="block px-4 py-2 text-sm font-medium text-slate-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">BRC</a>
                        <a href="https://src.sukarobot.com/" class="block px-4 py-2 text-sm font-medium text-slate-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">SRC</a>
                    </div>
                </div>

                {{-- Tentang Sukarobot Dropdown --}}
                <div class="relative" @mouseenter="tentangOpen = true" @mouseleave="tentangOpen = false">
                    <button class="flex items-center px-3 py-2 text-sm font-medium text-slate-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200">
                        Tentang Sukarobot
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="tentangOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="tentangOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute left-0 mt-1 w-44 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-100 dark:border-gray-700 py-2 z-50">
                        <a href="{{ url('/instruktur') }}" data-turbo="false" class="block px-4 py-2 text-sm font-medium text-slate-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">Instruktur</a>
                        <a href="{{ url('/tentang') }}" data-turbo="false" class="block px-4 py-2 text-sm font-medium text-slate-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">Tentang Kami</a>
                    </div>
                </div>

                {{-- Artikel --}}
                <a href="{{ url('/artikel') }}" class="px-3 py-2 text-sm font-medium text-slate-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200" data-turbo="false"> 
                    Artikel
                </a>
            </nav>
        </div>

        {{-- Right Section: Profile Dropdown --}}
        {{-- Desktop Profile Dropdown (hover-based) --}}
        <div class="hidden md:block relative" 
             @mouseenter="profileOpen = true" 
             @mouseleave="profileOpen = false">
            
            <button class="flex items-center space-x-3 p-1.5 pr-4 rounded-xl hover:bg-slate-50 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200 group">
                {{-- Avatar --}}
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 p-0.5 shadow-lg shadow-blue-700/20">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Instruktur') . '&background=1d4ed8&color=fff' }}" 
                             alt="Profile" 
                             class="w-full h-full rounded-lg object-cover bg-white">
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                </div>
                
                {{-- Name --}}
                <div class="text-left">
                    <p class="text-sm font-bold text-slate-800 dark:text-gray-200">{{ Auth::user()->name ?? 'Instruktur' }}</p>
                    <p class="text-xs text-slate-500 dark:text-gray-400">Instruktur</p>
                </div>
                
                {{-- Chevron --}}
                <svg class="w-4 h-4 text-slate-400 dark:text-gray-500 transition-transform duration-200" 
                     :class="profileOpen ? 'rotate-180' : ''" 
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Desktop Dropdown Menu --}}
            <div x-show="profileOpen" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-gray-700 overflow-hidden z-50">
                
                {{-- User Info Header --}}
                <div class="px-5 py-4 bg-gradient-to-r from-blue-700 to-blue-600">
                    <div class="flex items-center">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Instruktur') . '&background=ffffff&color=1d4ed8' }}" 
                             alt="Profile" 
                             class="w-12 h-12 rounded-xl border-2 border-white/30">
                        <div class="ml-3">
                            <p class="text-sm font-bold text-white">{{ Auth::user()->name ?? 'Instruktur' }}</p>
                            <p class="text-xs text-blue-200">{{ Auth::user()->email ?? 'instructor@email.com' }}</p>
                        </div>
                    </div>
                </div>
                
                {{-- Menu Items --}}
                <div class="py-2">
                    <a href="{{ route('client.dashboard') }}" data-turbo="false"
                       class="flex items-center px-5 py-3 text-sm font-medium text-slate-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 transition-colors duration-150 group">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-gray-700 flex items-center justify-center mr-3 group-hover:bg-blue-100 dark:group-hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 text-slate-500 dark:text-gray-400 group-hover:text-blue-700 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Profil Saya
                    </a>
                </div>
                
                {{-- Logout --}}
                <div class="border-t border-slate-100 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-5 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150 group">
                            <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center mr-3 group-hover:bg-red-100 dark:group-hover:bg-red-900/50 transition-colors">
                                <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mobile Profile Dropdown (click-based, separate component) --}}
        <div class="md:hidden relative" x-data="{ mobileProfileOpen: false }">
            <button @click="mobileProfileOpen = !mobileProfileOpen" 
                    type="button"
                    class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-slate-50 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200">
                {{-- Avatar --}}
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 p-0.5 shadow-lg shadow-blue-700/20">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Instruktur') . '&background=1d4ed8&color=fff' }}" 
                             alt="Profile" 
                             class="w-full h-full rounded-lg object-cover bg-white">
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                </div>
                
                {{-- Chevron --}}
                <svg class="w-4 h-4 text-slate-400 dark:text-gray-500 transition-transform duration-200" 
                     :class="mobileProfileOpen ? 'rotate-180' : ''" 
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Mobile Dropdown Menu --}}
            <div x-show="mobileProfileOpen" 
                 x-cloak
                 @click.outside="mobileProfileOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-gray-700 overflow-hidden z-50 origin-top-right">
                
                {{-- User Info Header --}}
                <div class="px-5 py-4 bg-gradient-to-r from-blue-700 to-blue-600">
                    <div class="flex items-center">
                        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Instruktur') . '&background=ffffff&color=1d4ed8' }}" 
                             alt="Profile" 
                             class="w-12 h-12 rounded-xl border-2 border-white/30">
                        <div class="ml-3">
                            <p class="text-sm font-bold text-white">{{ Auth::user()->name ?? 'Instruktur' }}</p>
                            <p class="text-xs text-blue-200">{{ Auth::user()->email ?? 'instructor@email.com' }}</p>
                        </div>
                    </div>
                </div>
                
                {{-- Client Menu Items (Mobile Only) --}}            
                {{-- Account Section --}}
                <div class="py-2 border-b border-slate-100 dark:border-gray-700">                
                    <a href="{{ route('client.dashboard') }}" data-turbo="false" 
                       @click="mobileProfileOpen = false"
                       class="flex items-center px-5 py-3 text-sm font-medium text-slate-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-blue-400 transition-colors duration-150 group">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-gray-700 flex items-center justify-center mr-3 group-hover:bg-blue-100 dark:group-hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 text-slate-500 dark:text-gray-400 group-hover:text-blue-700 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Profil Saya
                    </a>
                </div>
                
                {{-- Logout --}}
                <div class="py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-5 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150 group">
                            <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center mr-3 group-hover:bg-red-100 dark:group-hover:bg-red-900/50 transition-colors">
                                <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Title Bar with Slide Up Effect --}}
    <div class="px-6 py-4 bg-slate-50/50 dark:bg-gray-800/50 transition-all duration-300 ease-out overflow-hidden"
         :class="titleBarHidden ? 'max-h-0 py-0 opacity-0' : 'max-h-20 opacity-100'">
        <h1 class="text-xl font-bold text-slate-800 dark:text-gray-100 transition-transform duration-300"
            :class="titleBarHidden ? '-translate-y-full' : 'translate-y-0'">
            @yield('title', 'Dashboard')
        </h1>
    </div>
</header>

@push('scripts')
<script>
function headerComponent() {
    return {
        // ============================================
        // STATE
        // ============================================
        isDarkMode: false,
        profileOpen: false,
        kompetisiOpen: false,
        tentangOpen: false,
        titleBarHidden: false,
        lastScrollY: 0,
        scrollThreshold: 50,

        // ============================================
        // INITIALIZATION
        // ============================================
        initHeader() {
            // Initialize dark mode from localStorage
            const darkMode = localStorage.getItem('darkMode') === 'true';
            this.isDarkMode = darkMode;
            
            // Apply dark mode class to document
            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Initialize scroll position
            this.lastScrollY = window.scrollY;
        },

        // ============================================
        // METHODS
        // ============================================
        
        /**
         * Toggle dark mode and persist to localStorage
         */
        toggleDarkMode() {
            this.isDarkMode = !this.isDarkMode;
            localStorage.setItem('darkMode', this.isDarkMode);
            
            // Sync with root html scope
            window.dispatchEvent(new CustomEvent('dark-mode-updated', { detail: this.isDarkMode }));
            
            // Fallback class toggle (optional as root scope handles it, but good for immediate feedback if listener lags)
            if (this.isDarkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },

        /**
         * Handle scroll events for title bar slide effect
         */
        handleScroll() {
            const currentScrollY = window.scrollY;
            
            // Only trigger after passing the threshold
            if (currentScrollY > this.scrollThreshold) {
                // Scrolling down - hide title bar
                if (currentScrollY > this.lastScrollY) {
                    this.titleBarHidden = true;
                }
                // Scrolling up - show title bar
                else if (currentScrollY < this.lastScrollY) {
                    this.titleBarHidden = false;
                }
            } else {
                // Above threshold - always show
                this.titleBarHidden = false;
            }
            
            this.lastScrollY = currentScrollY;
        },

        /**
         * Close all dropdowns
         */
        closeAll() {
            this.profileOpen = false;
            this.kompetisiOpen = false;
            this.tentangOpen = false;
        }
    };
}
</script>
@endpush