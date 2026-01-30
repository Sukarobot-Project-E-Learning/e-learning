<nav id="main-navbar" class="fixed top-0 w-full z-50 transition-all duration-300 bg-transparent py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('assets/elearning/client/img/logo.png') }}" alt="Logo" class="w-50 h-auto">
                </a>
            </div>

            <!-- Desktop Nav -->
            <div class="nav hidden lg:flex items-center space-x-8">
                <!-- Home -->
                <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Home</a>

                <a href="{{ url('/program') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Program</a>

                <!-- Kompetisi Dropdown -->
                <div class="relative group">
                    <button class="flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer">
                        Kompetisi <span class="ml-1"></span>
                    </button>
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0">
                        <a href="https://brc.sukarobot.com/" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600">BRC</a>
                        <a href="https://src.sukarobot.com/" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600">SRC</a>
                    </div>
                </div>

                <!-- Tentang Sukarobot Dropdown -->
                <div class="relative group">
                    <button class="flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer">
                        Tentang Sukarobot <span class="ml-1"></span>
                    </button>
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0">
                        <a href="{{ url('/instruktur') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600">Instruktur</a>
                        <a href="{{ url('/tentang') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600">Tentang Kami</a>
                    </div>
                </div>

                <!-- Artikel -->
                <a href="{{ url('/artikel') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Artikel</a>

                <!-- Auth Buttons -->
                @guest
                    <a href="{{ url('/login') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30">
                        Masuk
                    </a>
                @endguest

                @auth
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 focus:outline-none cursor-pointer">
                            <img src="{{ Auth::user()->avatar_url }}" class="w-10 h-10 rounded-full border border-gray-200 object-cover">
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0">
                            @if(Auth::user()->role === 'instructor')
                                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50">Profil Saya</a>
                                <a href="{{ route('instructor.dashboard') }}?welcome=1" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50">Dashboard Instruktur</a>
                            @else
                                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50">Profil Saya</a>
                                <a href="{{ route('client.become-instructor') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50">Menjadi Instruktur</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 cursor-pointer">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button id="mobile-menu-btn" class="text-gray-600 focus:outline-none p-2 cursor-pointer">
                    <svg id="icon-menu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg id="icon-close" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden absolute top-full left-0 w-full bg-white shadow-lg border-t max-h-[80vh] overflow-y-auto">
        <div class="px-4 py-6 space-y-4 flex flex-col">
            <a href="{{ url('/') }}" class="text-base font-medium text-gray-700 hover:text-blue-600">Home</a>

            <a href="{{ url('/program') }}" class="text-base font-medium text-gray-700 hover:text-blue-600">Program</a>

            <div>
                <button class="w-full flex justify-between items-center text-base font-medium text-gray-700 hover:text-blue-600 mobile-dropdown-btn">
                    Kompetisi <span>▾</span>
                </button>
                <div class="hidden pl-4 space-y-2 mobile-dropdown-content">
                    <a href="https://brc.sukarobot.com/" class="block text-sm font-medium text-gray-600 hover:text-blue-600">BRC</a>
                    <a href="https://src.sukarobot.com/" class="block text-sm font-medium text-gray-600 hover:text-blue-600">SRC</a>
                </div>
            </div>

            <div>
                <button class="w-full flex justify-between items-center text-base font-medium text-gray-700 hover:text-blue-600 mobile-dropdown-btn">
                    Tentang Sukarobot <span>▾</span>
                </button>
                <div class="hidden pl-4 space-y-2 mobile-dropdown-content">
                    <a href="{{ url('/instruktur') }}" class="block text-sm font-medium text-gray-600 hover:text-blue-600">Instruktur</a>
                    <a href="{{ url('/tentang') }}" class="block text-sm font-medium text-gray-600 hover:text-blue-600">Tentang Kami</a>
                </div>
            </div>

            <a href="{{ url('/artikel') }}" class="text-base font-medium text-gray-700 hover:text-blue-600">Artikel</a>

            @guest
                <a href="{{ url('/login') }}" class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold text-center block hover:bg-blue-700 transition-colors">
                    Masuk
                </a>
            @endguest

            @auth
                <div class="pt-4 border-t border-gray-300">
                    <div class="flex items-center gap-3 mb-3">
                        <img src="{{ Auth::user()->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
                        <span class="font-medium text-gray-900">{{ Auth::user()->name }}</span>
                    </div>
                    <a href="{{ route('client.dashboard') }}" class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold text-center block hover:bg-blue-700 transition-colors mb-3">Profil Saya</a>
                    
                    @if(Auth::user()->role === 'instructor')
                        <a href="{{ route('instructor.dashboard') }}?welcome=1" class="w-full bg-white text-blue-600 border-2 border-blue-600 px-6 py-3 rounded-xl font-semibold text-center block hover:bg-blue-50 transition-colors mb-3">Dashboard Instruktur</a>
                    @else
                        <a href="{{ route('client.become-instructor') }}" class="w-full bg-white text-blue-600 border-2 border-blue-600 px-6 py-3 rounded-xl font-semibold text-center block hover:bg-blue-50 transition-colors mb-3">Menjadi Instruktur</a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 rounded-xl font-semibold text-center block hover:bg-red-700 transition-colors cursor-pointer">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>

<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/partials/navbar.css') }}">
