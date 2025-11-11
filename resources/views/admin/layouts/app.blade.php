<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Learning Admin') - Sukarobot</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('assets/elearning/admin/css/tailwind.output.css') }}" />

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Init Alpine -->
    <script src="{{ asset('assets/elearning/admin/js/init-alpine.js') }}"></script>

    @stack('styles')
</head>
<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">

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
</body>
</html>
