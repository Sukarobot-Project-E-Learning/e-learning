@extends('panel.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div x-data="{ 
        selectedRating: 'all',
        reviews: {{ Js::from($programReviews) }},
        get filteredReviews() {
            if (this.selectedRating === 'all') return this.reviews;
            return this.reviews.filter(r => r.rating == this.selectedRating);
        }
    }" class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

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
                    <span
                        x-text="new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
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
                <select x-model="selectedRating"
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
            <div class="block lg:hidden space-y-4">
                <template x-if="filteredReviews.length === 0">
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-base font-medium text-gray-600 dark:text-gray-300">Belum ada ulasan</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ulasan kursus akan muncul di sini</p>
                    </div>
                </template>
                <template x-for="review in filteredReviews" :key="review.id">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
                        <div class="flex items-start gap-3 mb-3">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                                <template x-if="review.student_avatar">
                                    <img :src="'/storage/' + review.student_avatar" class="w-full h-full object-cover"
                                        :alt="review.student_name">
                                </template>
                                <template x-if="!review.student_avatar">
                                    <span class="text-white text-sm font-bold"
                                        x-text="review.student_name ? review.student_name.charAt(0).toUpperCase() : 'U'"></span>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate"
                                    x-text="review.student_name"></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate" x-text="review.program_name">
                                </p>
                            </div>
                            <div class="flex items-center gap-0.5">
                                <template x-for="i in 5">
                                    <svg :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                                        class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2"
                            x-text="review.review || 'Tidak ada ulasan'"></p>
                        <p class="text-xs text-gray-400 dark:text-gray-500"
                            x-text="new Date(review.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })">
                        </p>
                    </div>
                </template>
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
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-if="filteredReviews.length === 0">
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-base font-medium text-gray-600 dark:text-gray-300">Belum ada ulasan
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ulasan kursus akan muncul
                                            di sini</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-for="review in filteredReviews" :key="review.id">
                            <tr
                                class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            <template x-if="review.student_avatar">
                                                <img :src="'/storage/' + review.student_avatar"
                                                    class="w-full h-full object-cover" :alt="review.student_name">
                                            </template>
                                            <template x-if="!review.student_avatar">
                                                <span class="text-white text-xs font-bold"
                                                    x-text="review.student_name ? review.student_name.charAt(0).toUpperCase() : 'U'"></span>
                                            </template>
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-white"
                                            x-text="review.student_name"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-gray-700 dark:text-gray-300 font-medium"
                                        x-text="review.program_name"></span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1">
                                        <template x-for="i in 5">
                                            <svg :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                                                class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </template>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1"
                                            x-text="'(' + review.rating + ')'"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 max-w-xs">
                                    <p class="text-gray-600 dark:text-gray-300 truncate" x-text="review.review || '-'"
                                        :title="review.review"></p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap"
                                        x-text="new Date(review.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Chart data from backend
        const chartDataByYear = @json($chartData ?? []);
        const availableYears = chartDataByYear && Object.keys(chartDataByYear).length > 0
            ? Object.keys(chartDataByYear).map(Number)
            : [new Date().getFullYear()];
        const latestYear = availableYears.length > 0 ? Math.max(...availableYears) : new Date().getFullYear();

        let platformChart = null;
        let revenueChart = null;

        // No Data State HTML Template
        function getNoDataHTML(title, message) {
            const colors = getThemeColors();
            return `
                <div class="flex flex-col items-center justify-center h-full min-h-[280px] text-center p-6">
                    <div class="p-4 rounded-full mb-4 ${isDarkMode() ? 'bg-gray-700' : 'bg-gray-100'}">
                        <svg class="w-12 h-12 ${isDarkMode() ? 'text-gray-500' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold ${isDarkMode() ? 'text-gray-300' : 'text-gray-700'} mb-1">${title}</h4>
                    <p class="text-sm ${isDarkMode() ? 'text-gray-500' : 'text-gray-500'}">${message}</p>
                </div>
            `;
        }

        // Check if data has any non-zero values
        function hasData(dataArray) {
            if (!dataArray || !Array.isArray(dataArray)) return false;
            return dataArray.some(val => val > 0);
        }

        // Format currency
        function formatRupiah(amount) {
            return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Platform Statistics Chart
        function renderPlatformChart(year) {
            const chartContainer = document.querySelector("#platformChart");
            if (!chartContainer) return;

            // Check if data exists for this year
            if (!chartDataByYear || !chartDataByYear[year]) {
                chartContainer.innerHTML = getNoDataHTML('Data Tidak Tersedia', 'Belum ada data statistik untuk tahun ' + year);
                return;
            }

            const currentData = chartDataByYear[year];

            // Check if all data series are empty/zero
            if (!hasData(currentData.users) && !hasData(currentData.instructors) && !hasData(currentData.programs)) {
                chartContainer.innerHTML = getNoDataHTML('Belum Ada Data', 'Data statistik platform akan muncul setelah ada aktivitas');
                return;
            }

            const colors = getThemeColors();

            const options = {
                series: [
                    { name: 'Pengguna', data: currentData.users },
                    { name: 'Instruktur', data: currentData.instructors },
                    { name: 'Total Program', data: currentData.programs }
                ],
                chart: {
                    type: 'line',
                    height: 320,
                    fontFamily: 'inherit',
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false }
                    },
                    animations: { enabled: true, easing: 'easeinout', speed: 800 }
                },
                colors: ['#F97316', '#3B82F6', '#8B5CF6'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                markers: {
                    size: 5,
                    colors: ['#F97316', '#3B82F6', '#8B5CF6'],
                    strokeColors: colors.background,
                    strokeWidth: 2,
                    hover: { size: 7 }
                },
                xaxis: {
                    categories: currentData.months,
                    labels: { style: { fontSize: '12px', fontWeight: 500, colors: colors.text } },
                    axisBorder: { show: true, color: colors.grid },
                    axisTicks: { show: true, color: colors.grid }
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    title: { text: 'Total Kumulatif', style: { fontSize: '13px', fontWeight: 600, color: colors.text } },
                    labels: {
                        style: { fontSize: '12px', colors: colors.text },
                        formatter: val => Math.floor(val)
                    }
                },
                grid: { borderColor: colors.grid, strokeDashArray: 4 },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '13px',
                    fontWeight: 500,
                    labels: { colors: colors.text },
                    markers: { width: 10, height: 10, radius: 10 },
                    itemMargin: { horizontal: 12, vertical: 8 }
                },
                tooltip: { theme: colors.tooltip },
                responsive: [{ breakpoint: 640, options: { chart: { height: 280 }, legend: { fontSize: '11px' } } }]
            };

            // Clear container before rendering
            chartContainer.innerHTML = '';

            if (platformChart) platformChart.destroy();
            platformChart = new ApexCharts(chartContainer, options);
            platformChart.render();
        }

        // Revenue Chart
        function renderRevenueChart(year) {
            const chartContainer = document.querySelector("#revenueChart");
            if (!chartContainer) return;

            // Check if data exists for this year
            if (!chartDataByYear || !chartDataByYear[year]) {
                chartContainer.innerHTML = getNoDataHTML('Data Tidak Tersedia', 'Belum ada data pendapatan untuk tahun ' + year);
                return;
            }

            const currentData = chartDataByYear[year];

            // Check if revenue data is empty/zero
            if (!hasData(currentData.revenue)) {
                chartContainer.innerHTML = getNoDataHTML('Belum Ada Pendapatan', 'Data pendapatan akan muncul setelah ada transaksi');
                return;
            }

            const colors = getThemeColors();

            const options = {
                series: [{ name: 'Pendapatan', data: currentData.revenue }],
                chart: {
                    type: 'bar',
                    height: 320,
                    fontFamily: 'inherit',
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false }
                    },
                    animations: { enabled: true, easing: 'easeinout', speed: 800 }
                },
                colors: ['#10B981'],
                plotOptions: {
                    bar: { borderRadius: 8, columnWidth: '55%' }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: currentData.months,
                    labels: { style: { fontSize: '12px', fontWeight: 500, colors: colors.text } },
                    axisBorder: { show: true, color: colors.grid }
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    title: { text: 'Pendapatan (Rp)', style: { fontSize: '13px', fontWeight: 600, color: colors.text } },
                    labels: {
                        style: { fontSize: '12px', colors: colors.text },
                        formatter: val => {
                            if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                            if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                            return 'Rp ' + val;
                        }
                    }
                },
                grid: { borderColor: colors.grid, strokeDashArray: 4 },
                tooltip: { theme: colors.tooltip, y: { formatter: val => formatRupiah(val) } },
                responsive: [{ breakpoint: 640, options: { chart: { height: 280 } } }]
            };

            // Clear container before rendering
            chartContainer.innerHTML = '';

            if (revenueChart) revenueChart.destroy();
            revenueChart = new ApexCharts(chartContainer, options);
            revenueChart.render();
        }

        // Initial render with safety check
        document.addEventListener('DOMContentLoaded', function () {
            // Wait for ApexCharts to be available
            if (typeof ApexCharts !== 'undefined') {
                renderPlatformChart(latestYear);
                renderRevenueChart(latestYear);
            } else {
                console.warn('ApexCharts not loaded. Charts will not render.');
            }
        });

        // Year selector events
        const yearSelector = document.getElementById('yearSelector');
        const revenueYearSelector = document.getElementById('revenueYearSelector');

        if (yearSelector) {
            yearSelector.addEventListener('change', function () {
                renderPlatformChart(parseInt(this.value));
            });
        }

        if (revenueYearSelector) {
            revenueYearSelector.addEventListener('change', function () {
                renderRevenueChart(parseInt(this.value));
            });
        }

        // Theme change observer
        const observer = new MutationObserver(() => {
            if (yearSelector) renderPlatformChart(parseInt(yearSelector.value));
            if (revenueYearSelector) renderRevenueChart(parseInt(revenueYearSelector.value));
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    </script>
@endpush