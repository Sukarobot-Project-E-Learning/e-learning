<!DOCTYPE html>
<html :class="{ 'dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Learning Admin') - Sukarobot</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- App CSS & JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js x-cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
        
        /* Loading spinner animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        /* Pulse animation for loading text */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Init Alpine -->
    <script src="{{ asset('assets/elearning/admin/js/init-alpine.js') }}"></script>

    @stack('styles')
</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">

        <!-- Loading Indicator Overlay -->
        <div x-show="isLoading" 
             x-cloak
             class="fixed inset-0 z-9999 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
             style="display: none;">
            <div class="flex flex-col items-center space-y-4">
                <!-- Spinner -->
                <div class="relative">
                    <div class="w-16 h-16 border-4 border-orange-200 border-t-orange-600 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
                <!-- Loading Text -->
                <div class="text-center">
                    <p class="text-lg font-semibold text-white">Memproses...</p>
                    <p class="text-sm text-gray-300">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Mobile Sidebar -->
        @include('admin.layouts.mobile-sidebar')

        <div class="flex flex-col flex-1 w-full">

            <!-- Header/Navbar -->
            @include('admin.layouts.header')

            <!-- Main Content -->
            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    @stack('scripts')

    <!-- Auto-loading script for forms and navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get Alpine data
            const alpineData = window.Alpine ? Alpine.raw(document.querySelector('[x-data="data()"]').__x.$data) : null;
            
            // Show loading on form submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Don't show loading for delete confirmations or AJAX forms
                    if (form.hasAttribute('data-no-loading') || 
                        form.hasAttribute('x-submit') ||
                        form.querySelector('[data-no-loading]')) {
                        return;
                    }
                    
                    // Show loading indicator
                    if (alpineData) {
                        alpineData.isLoading = true;
                    }
                    
                    // Disable submit button to prevent double submission
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
                        
                        // Restore button after 30 seconds (failsafe)
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            if (alpineData) alpineData.isLoading = false;
                        }, 30000);
                    }
                });
            });
            
            // Show loading on navigation links (except external, # anchors, and logout)
            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    
                    // Skip if it's an anchor, external link, javascript:, or has data-no-loading attribute
                    if (!href || 
                        href === '#' || 
                        href.startsWith('javascript:') || 
                        href.startsWith('http://') || 
                        href.startsWith('https://') ||
                        this.hasAttribute('download') ||
                        this.hasAttribute('target') ||
                        this.hasAttribute('data-no-loading') ||
                        href.includes('logout')) {
                        return;
                    }
                    
                    // Show loading for internal navigation
                    if (alpineData) {
                        alpineData.isLoading = true;
                    }
                    
                    // Hide loading after navigation starts (failsafe - 5 seconds)
                    setTimeout(() => {
                        if (alpineData) alpineData.isLoading = false;
                    }, 5000);
                });
            });
            
            // Hide loading when page is loaded/visible again
            window.addEventListener('pageshow', function(event) {
                if (alpineData) {
                    alpineData.isLoading = false;
                }
                
                // Re-enable all submit buttons
                document.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.disabled = false;
                });
            });
            
            // Hide loading on browser back button
            window.addEventListener('popstate', function() {
                if (alpineData) {
                    alpineData.isLoading = false;
                }
            });
        });
    </script>
</body>

</html>