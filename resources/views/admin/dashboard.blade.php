@extends('panel.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

        <!-- Greeting Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
                        Hai Admin 👋
                    </h2>
                    <p class="text-base sm:text-lg text-orange-500 dark:text-orange-400 font-medium mt-1">
                        Selamat Datang di Sukarobot Academy Dashboard!
                    </p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span id="currentDate"></span>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 1: RINGKASAN KEUANGAN ===== -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-green-100 dark:bg-green-900/40 rounded-xl">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ringkasan Keuangan</h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- Card 1: Pendapatan Bulan -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg shadow-green-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Pendapatan Bulan</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate">Rp
                        {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
                </div>

                <!-- Card 2: Pendapatan Tahun -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Pendapatan Tahun</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate">Rp
                        {{ number_format($yearlyRevenue, 0, ',', '.') }}</h4>
                </div>

                <!-- Card 3: Transaksi Bulan -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg shadow-blue-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Transaksi Bulan</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $monthlyTransactions }}</h4>
                </div>

                <!-- Card 4: Transaksi Tahun -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-indigo-400 to-indigo-600 text-white shadow-lg shadow-indigo-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Transaksi Tahun</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $yearlyTransactions }}</h4>
                </div>

                <!-- Card 5: Total Kursus -->
                <div
                    class="col-span-2 sm:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Total Kursus</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $totalCourses }}</h4>
                </div>
            </div>
        </div>

        <!-- ===== SECTION 2: STATISTIK PLATFORM ===== -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Statistik Platform</h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <!-- Card: Total User -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg shadow-blue-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Total User</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</h4>
                </div>

                <!-- Card: Total Instructor -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-violet-400 to-violet-600 text-white shadow-lg shadow-violet-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Total Instructor</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $totalInstructors }}</h4>
                </div>

                <!-- Card: Total Program -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Total Program</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $totalPrograms }}</h4>
                </div>

                <!-- Card: Program Aktif -->
                <div
                    class="col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg shadow-green-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Program Aktif</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $programsAvailable }}</h4>
                </div>

                <!-- Card: Program Non Aktif -->
                <div
                    class="col-span-2 sm:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="p-2.5 rounded-xl bg-gradient-to-br from-red-400 to-red-600 text-white shadow-lg shadow-red-500/30">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Program Non Aktif</p>
                    <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">{{ $programsUnavailable }}</h4>
                </div>
            </div>
        </div>

        <!-- ===== CHARTS SECTION ===== -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Trend Chart -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/40 rounded-xl">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">Tren Pendapatan</h3>
                    </div>
                    <select id="revenueYearSelector"
                        class="w-full sm:w-auto bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2.5 transition-all duration-200">
                        @php
                            $revenueYears = array_keys($chartData);
                        @endphp
                        @foreach($revenueYears as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="revenueChart" class="w-full min-h-[280px] sm:min-h-[320px]"></div>
            </div>

            <!-- Platform Statistics Chart -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">Statistik Platform</h3>
                    </div>
                    @php
                        $availableYears = array_keys($chartData);
                        $currentYear = date('Y');
                        $bestYear = in_array($currentYear, $availableYears) ? $currentYear : ($availableYears[0] ?? date('Y'));
                    @endphp
                    <select id="yearSelector"
                        class="w-full sm:w-auto bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 transition-all duration-200">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $bestYear ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="platformChart" class="w-full min-h-[280px] sm:min-h-[320px]"></div>
            </div>
        </div>

        <!-- ===== REVIEWS SECTION ===== -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/40 rounded-xl">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white">Ulasan Kursus</h3>
                </div>
                <select id="ratingSelector"
                    class="w-full sm:w-auto bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 px-4 py-2.5 transition-all duration-200">
                    <option value="all">Semua Rating</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>
            </div>

            <!-- Mobile Cards View -->
            <div id="reviewsMobileContainer" class="block lg:hidden space-y-4">
                <!-- Reviews will be rendered here by JavaScript -->
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm text-left">
                    <thead
                        class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-5 py-4">User</th>
                            <th scope="col" class="px-5 py-4">Kursus</th>
                            <th scope="col" class="px-5 py-4">Rating</th>
                            <th scope="col" class="px-5 py-4">Ulasan</th>
                            <th scope="col" class="px-5 py-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="reviewsDesktopContainer" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Reviews will be rendered here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    
    <!-- Chart Utility Scripts (order matters!) -->
    <script src="{{ asset('assets/elearning/admin/js/dashboard/chart-theme.js') }}"></script>
    <script src="{{ asset('assets/elearning/admin/js/dashboard/chart-utils.js') }}"></script>
    <script src="{{ asset('assets/elearning/admin/js/dashboard/platform-chart.js') }}"></script>
    <script src="{{ asset('assets/elearning/admin/js/dashboard/revenue-chart.js') }}"></script>
    <script src="{{ asset('assets/elearning/admin/js/dashboard/dashboard-charts.js') }}"></script>
    <script src="{{ asset('assets/elearning/admin/js/dashboard/reviews.js') }}"></script>

    <!-- Initialize data from PHP -->
    <script>
        // Make data available globally
        window.chartDataByYear = @json($chartData ?? []);
        window.programReviews = @json($programReviews ?? []);
    </script>

    <!-- Initialize Dashboard -->
    <script src="{{ asset('assets/elearning/admin/js/dashboard/dashboard-init.js') }}"></script>
@endpush