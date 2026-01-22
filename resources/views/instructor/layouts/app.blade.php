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
}" 
@dark-mode-updated.window="dark = $event.detail"
lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Learning Instructor') - Sukarobot</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        'primary-dark': '#2563EB',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <!-- App CSS & JS via Vite -->
    @vite(['resources/css/instructor.css', 'resources/js/instructor.js'])

    <!-- Alpine.js x-cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
        
        /* Turbo Progress Bar */
        .turbo-progress-bar {
            height: 3px;
            background-color: #3b82f6; /* Tailwind blue-500 */
            background-image: linear-gradient(to right, #3b82f6, #f97316); /* Blue to Orange */
        }
        
        /* Loading Screen Styles - Only shown when ?welcome=1 parameter is present */
        #instructor-loading-screen {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            transition: transform 0.6s cubic-bezier(0.65, 0, 0.35, 1);
        }
        
        #instructor-loading-screen.active {
            display: flex;
        }
        
        #instructor-loading-screen.slide-up {
            transform: translateY(-100%);
        }
        
        /* Decorative elements */
        .loading-decoration {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
        }
        
        
        /* Spinner */
        .side-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-right-color: #f97316;
            border-radius: 50%;
            animation: spinSmooth 0.8s linear infinite;
        }
        
        @keyframes spinSmooth {
            to { transform: rotate(360deg); }
        }
        
        /* Text fade */
        .loading-subtitle {
            animation: subtitlePulse 1.5s ease-in-out infinite;
        }
        
        @keyframes subtitlePulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
        
        /* Progress dots */
        .progress-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: dotPulse 1.2s ease-in-out infinite;
        }
        
        .progress-dot:nth-child(1) { animation-delay: 0s; background: #3b82f6; }
        .progress-dot:nth-child(2) { animation-delay: 0.2s; background: #60a5fa; }
        .progress-dot:nth-child(3) { animation-delay: 0.4s; background: #f97316; }
        
        @keyframes dotPulse {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1.2); opacity: 1; }
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> 

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @stack('styles')
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100 antialiased"
      @toggle-mobile-sidebar.window="toggleSideMenu">

      <!-- Instructor Loading Screen - Only shows when coming from client pages -->
    <div id="instructor-loading-screen">
        <!-- Decorative Background Elements -->
        <div class="loading-decoration blue" style="width: 300px; height: 300px; top: -100px; right: -100px; filter: blur(60px);"></div>
        <div class="loading-decoration orange" style="width: 200px; height: 200px; bottom: -50px; left: -50px; filter: blur(50px);"></div>
        <div class="loading-decoration blue" style="width: 150px; height: 150px; top: 50%; left: 10%; filter: blur(40px);"></div>
        <div class="loading-decoration orange" style="width: 100px; height: 100px; top: 20%; right: 20%; filter: blur(30px);"></div>
        
        <!-- Main Content -->
        <div class="relative flex flex-col items-center justify-center space-y-6 px-6">
            <!-- Logo with Side Spinner -->
            <div class="flex items-center gap-4">
                <img src="{{ asset('assets/elearning/client/img/logo.png') }}" alt="Sukarobot" class="h-12 md:h-14 w-auto">
                <div class="side-spinner"></div>
            </div>
            
            <!-- Welcome Text -->
            <div class="text-center space-y-2">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                    Selamat Datang, {{ Auth::user()->name }}!
                </h2>
                <p class="text-gray-500 text-sm loading-subtitle">
                    Menyiapkan dashboard Anda...
                </p>
            </div>
            
            <!-- Progress Dots -->
            <div class="flex items-center gap-2">
                <div class="progress-dot"></div>
                <div class="progress-dot"></div>
                <div class="progress-dot"></div>
            </div>
        </div>
    </div>

    <script>
        // Loading screen - only show when ?welcome=1 parameter is present
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const showWelcome = urlParams.get('welcome') === '1';
            
            if (showWelcome) {
                const loadingScreen = document.getElementById('instructor-loading-screen');
                loadingScreen.classList.add('active');
                
                // Clean up URL (remove ?welcome=1 parameter)
                const cleanUrl = window.location.pathname + window.location.hash;
                window.history.replaceState({}, document.title, cleanUrl);
                
                // Slide up after content loads
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        loadingScreen.classList.add('slide-up');
                        
                        setTimeout(function() {
                            loadingScreen.style.display = 'none';
                        }, 600);
                    }, 1200);
                });
            }

            // Ensure loader hides on Turbo navigation (just in case)
            document.addEventListener("turbo:load", function() {
                const loadingScreen = document.getElementById('instructor-loading-screen');
                if (loadingScreen && loadingScreen.style.display !== 'none' && !showWelcome) {
                    loadingScreen.style.display = 'none';
                }
            });
        })();
    </script>

    <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
        
        <!-- Desktop Sidebar -->
        @include('instructor.layouts.sidebar')

        <!-- Mobile Sidebar -->
        @include('instructor.layouts.mobile-sidebar')

        <div class="flex flex-col flex-1 w-full h-full overflow-hidden relative">
            
            <!-- Header/Navbar -->
            @include('instructor.layouts.header')

            <!-- Main Content -->
            <main class="flex-1 h-full overflow-y-auto overflow-x-hidden">
                <div class="container mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>