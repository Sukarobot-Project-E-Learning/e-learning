<!DOCTYPE html>
<html :class="{ 'dark': dark }" x-data="{ 
    dark: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    isSideMenuOpen: false,
    toggleSideMenu() {
        this.isSideMenuOpen = !this.isSideMenuOpen
    },
    closeSideMenu() {
        this.isSideMenuOpen = false
    }
}" @dark-mode-updated.window="dark = $event.detail" lang="en"
    data-role="{{ request()->is('admin*') ? 'admin' : (request()->is('instructor*') ? 'instructor' : (auth('admin')->check() ? 'admin' : (auth()->check() && auth()->user()->role === 'instructor' ? 'instructor' : 'guest'))) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sukarobot</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--color-primary-admin)',
                        'primary-dark': 'var(--color-primary-dark-admin)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <!-- Turbo CDN -->
    <script type="module">
        import * as Turbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.12/+esm';
        Turbo.start();
    </script>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

    <!-- Panel CSS via Vite (only CSS, no JS imports) -->
    @vite(['resources/css/panel.css'])

    <style>
        /* FAB Fixed Position - Above Mobile Navigation */
        .btn-float {
            position: fixed;
            bottom: 5rem;
            /* bottom-20 = 80px */
            right: 1.5rem;
            /* right-6 = 24px */
            z-index: 40;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-float:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.08);
        }

        /* Ensure pagination has margin when FAB is present */
        .pagination-with-fab {
            margin-bottom: 5rem;
        }

        /* Loading screen transition */
        #loading-screen {
            transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased"
    @toggle-mobile-sidebar.window="toggleSideMenu">

    @php
        if (request()->is('admin*')) {
            $role = 'admin';
        } elseif (request()->is('instructor*')) {
            $role = 'instructor';
        } else {
            $role = auth('admin')->check() ? 'admin' : (auth()->check() && auth()->user()->role === 'instructor' ? 'instructor' : 'guest');
        }
    @endphp

    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-white">

        <!-- Main Content -->
        <div class="relative flex flex-col items-center justify-center space-y-6 px-6">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="{{ asset('assets/elearning/client/img/logo.png') }}" alt="Sukarobot"
                    class="h-12 md:h-14 w-auto">
            </div>

            <!-- Welcome Text -->
            <div class="text-center space-y-2">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                    Selamat Datang, {{ auth($role === 'admin' ? 'admin' : 'web')->user()?->name ?? ucfirst($role) }}!
                </h2>
                <p class="text-gray-500 text-sm animate-pulse">
                    Menyiapkan dashboard Anda...
                </p>
            </div>

            <!-- Progress Dots -->
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full animate-bounce"
                    style="background: {{ $role === 'admin' ? '#F97316' : '#3B82F6' }}; animation-delay: 0s;"></div>
                <div class="w-1.5 h-1.5 rounded-full animate-bounce"
                    style="background: {{ $role === 'admin' ? '#FB923C' : '#60A5FA' }}; animation-delay: 0.2s;"></div>
                <div class="w-1.5 h-1.5 rounded-full animate-bounce"
                    style="background: {{ $role === 'admin' ? '#FED7AA' : '#93C5FD' }}; animation-delay: 0.4s;"></div>
            </div>
        </div>
    </div>

    <script>
        // Loading screen - only show when ?welcome=1 parameter is present
        (function () {
            const urlParams = new URLSearchParams(window.location.search);
            const showWelcome = urlParams.get('welcome') === '1';

            if (showWelcome) {
                const loadingScreen = document.getElementById('loading-screen');
                loadingScreen.classList.remove('hidden');
                loadingScreen.classList.add('flex');

                // Clean up URL (remove ?welcome=1 parameter)
                const cleanUrl = window.location.pathname + window.location.hash;
                window.history.replaceState({}, document.title, cleanUrl);

                // Slide up after content loads
                window.addEventListener('load', function () {
                    setTimeout(function () {
                        loadingScreen.style.transform = 'translateY(-100%)';

                        setTimeout(function () {
                            loadingScreen.style.display = 'none';
                        }, 600);
                    }, 1200);
                });
            }

            // Ensure loader hides on Turbo navigation
            document.addEventListener("turbo:load", function () {
                const loadingScreen = document.getElementById('loading-screen');
                if (loadingScreen && loadingScreen.style.display !== 'none' && !showWelcome) {
                    loadingScreen.style.display = 'none';
                }
            });
        })();
    </script>

    <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">

        <!-- Desktop Sidebar -->
        @include('panel.layouts.sidebar')

        <!-- Mobile Sidebar -->
        @include('panel.layouts.mobile-sidebar')

        <div class="flex flex-col flex-1 w-full h-full overflow-hidden relative">

            <!-- Header/Navbar -->
            @include('panel.layouts.header')

            <!-- Main Content -->
            <main class="flex-1 h-full overflow-y-auto overflow-x-hidden">
                <div class="container mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Global SweetAlert2 Configuration --}}
    @include('panel.layouts.partials.sweetalert-scripts')

    @stack('scripts')
</body>

</html>