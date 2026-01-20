@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Greeting Header -->
    <div class="mt-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-200">
            Hai Admin,
        </h2>
        <p class="text-lg text-orange-600 dark:text-orange-400 font-semibold mt-3">
            Selamat Datang di Sukarobot Academy Dashboard!
        </p>
    </div>

    <!-- ===== SECTION 1: RINGKASAN KEUANGAN ===== -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
            <span class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </span>
            Ringkasan Keuangan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Card 1: Pendapatan Bulan -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pendapatan Bulan</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Card 2: Pendapatan Tahun -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pendapatan Tahun</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Card 3: Transaksi Bulan -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Transaksi Bulan</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $monthlyTransactions }}</h4>
                </div>
            </div>

            <!-- Card 4: Transaksi Tahun -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Transaksi Tahun</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $yearlyTransactions }}</h4>
                </div>
            </div>

            <!-- Card 5: Total Kursus -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Kursus</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $totalCourses }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 2: STATISTIK PLATFORM ===== -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
            <span class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </span>
            Statistik Platform
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Card: Total User -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Total User</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $totalUsers }}</h4>
                </div>
            </div>

            <!-- Card: Total Instructor -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Total Instructor</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $totalInstructors }}</h4>
                </div>
            </div>

            <!-- Card: Total Program -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Total Program</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $totalPrograms }}</h4>
                </div>
            </div>

            <!-- Card: Program Aktif -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Program Aktif</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $programsAvailable }}</h4>
                </div>
            </div>

            <!-- Card: Program Non Aktif -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 flex items-start space-x-3">
                <div class="p-3 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Program Non Aktif</p>
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $programsUnavailable }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Trend Chart (NEW - placed above Platform Statistics) -->
    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                📈 Tren Pendapatan
            </h3>
            <div class="flex items-center gap-2">
                <label for="revenueYearSelector" class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun:</label>
                <select id="revenueYearSelector" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 px-3 py-2">
                    @php
                        $revenueYears = array_keys($chartData);
                    @endphp
                    @foreach($revenueYears as $year)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="revenueChart"></div>
    </div>
    <!-- Chart Section -->
    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                Statistik Platform
            </h3>
            <!-- Year Selector Dropdown -->
            <div class="flex items-center gap-2">
                @php
                    $availableYears = array_keys($chartData);
                    $currentYear = date('Y');
                    // Default to current year if it exists, otherwise the year with most data
                    $bestYear = in_array($currentYear, $availableYears) ? $currentYear : $availableYears[0] ?? date('Y');
                    
                    // If current year doesn't have the most data, still prefer it for recency
                    if (!in_array($currentYear, $availableYears)) {
                        $maxDataPoints = 0;
                        foreach($availableYears as $year) {
                            $dataPoints = 0;
                            if (isset($chartData[$year])) {
                                $dataPoints = array_sum($chartData[$year]['users']) + 
                                            array_sum($chartData[$year]['instructors']) + 
                                            array_sum($chartData[$year]['programs']);
                            }
                            if ($dataPoints > $maxDataPoints) {
                                $maxDataPoints = $dataPoints;
                                $bestYear = $year;
                            }
                        }
                    }
                @endphp
                
                <label for="yearSelector" class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun:</label>
                <select id="yearSelector" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 px-3 py-2">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $bestYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="platformChart"></div>
    </div>

    <!-- Review Management Section -->
    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 p-6 mb-8" x-data="{ 
        selectedRating: 'all',
        reviews: {{ Js::from($programReviews) }},
        get filteredReviews() {
            if (this.selectedRating === 'all') return this.reviews;
            return this.reviews.filter(r => r.rating == this.selectedRating);
        }
    }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                ⭐ Ulasan Kursus
            </h3>
            <div class="flex items-center gap-2">
                <label for="ratingFilter" class="text-sm font-medium text-gray-600 dark:text-gray-400">Filter Rating:</label>
                <select x-model="selectedRating" id="ratingFilter" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 px-3 py-2">
                    <option value="all">Semua Rating</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">User</th>
                        <th scope="col" class="px-4 py-3">Kursus</th>
                        <th scope="col" class="px-4 py-3">Rating</th>
                        <th scope="col" class="px-4 py-3">Ulasan</th>
                        <th scope="col" class="px-4 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="filteredReviews.length === 0">
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Belum ada ulasan</p>
                                    <p class="text-sm">Ulasan kursus akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="review in filteredReviews" :key="review.id">
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                        <template x-if="review.student_avatar">
                                            <img :src="'/storage/' + review.student_avatar" class="w-full h-full object-cover" :alt="review.student_name">
                                        </template>
                                        <template x-if="!review.student_avatar">
                                            <span class="text-gray-500 text-xs font-bold" x-text="review.student_name ? review.student_name.charAt(0).toUpperCase() : 'U'"></span>
                                        </template>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="review.student_name"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-900 dark:text-white font-medium" x-text="review.program_name"></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <template x-for="i in 5">
                                        <svg :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </template>
                                    <span class="text-xs text-gray-500 ml-1" x-text="'(' + review.rating + ')'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="truncate" x-text="review.review || '-'" :title="review.review"></p>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500" x-text="new Date(review.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Get chart data from backend
    const chartDataByYear = @json($chartData);
    const availableYears = Object.keys(chartDataByYear).map(Number);
    const latestYear = Math.max(...availableYears);
    
    let currentChart = null;

    // Function to calculate cumulative data
    function calculateCumulativeData(monthlyData) {
        let cumulative = [];
        let sum = 0;
        for (let i = 0; i < monthlyData.length; i++) {
            sum += monthlyData[i];
            cumulative.push(sum);
        }
        return cumulative;
    }

    // Function to render chart for specific year
    function renderChart(year) {
        const currentData = chartDataByYear[year];
        
        const options = {
            series: [
                {
                    name: 'Pengguna',
                    data: currentData.users
                },
                {
                    name: 'Instruktur',
                    data: currentData.instructors
                },
                {
                    name: 'Total Program',
                    data: currentData.programs
                }
            ],
            chart: {
                type: 'line',
                height: 400,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                },
                // Add padding to prevent label cutoff
                sparkline: {
                    enabled: false
                },
                offsetX: 0,
                offsetY: 0,
                parentHeightOffset: 0
            },
            colors: ['#F97316', '#3B82F6', '#A855F7'], // Orange, Blue, orange
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 5,
                colors: ['#F97316', '#3B82F6', '#A855F7'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: currentData.months,
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 600,
                        colors: '#6B7280'
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#E5E7EB'
                },
                axisTicks: {
                    show: true,
                    color: '#E5E7EB'
                }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                title: {
                    text: 'Total Kumulatif',
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#374151'
                    },
                    offsetX: 0
                },
                labels: {
                    style: {
                        fontSize: '12px',
                        colors: '#6B7280'
                    },
                    formatter: function(val) {
                        return Math.floor(val);
                    },
                    offsetX: -10
                }
            },
            grid: {
                borderColor: '#E5E7EB',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px',
                fontWeight: 600,
                labels: {
                    colors: '#374151'
                },
                markers: {
                    width: 12,
                    height: 12,
                    radius: 12
                },
                itemMargin: {
                    horizontal: 15,
                    vertical: 5
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                x: {
                    show: true
                },
                y: {
                    formatter: function(value) {
                        return value + ' Total';
                    }
                },
                marker: {
                    show: true
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        // Destroy previous chart if exists
        if (currentChart) {
            currentChart.destroy();
        }

        // Render new chart
        currentChart = new ApexCharts(document.querySelector("#platformChart"), options);
        currentChart.render();
    }

    // Initial render with latest year
    renderChart(latestYear);

    // Year selector change event
    document.getElementById('yearSelector').addEventListener('change', function() {
        const selectedYear = parseInt(this.value);
        renderChart(selectedYear);
    });

    // ===== REVENUE TREND CHART =====
    let revenueChart = null;

    // Format currency to IDR
    function formatRupiah(amount) {
        return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function renderRevenueChart(year) {
        const currentData = chartDataByYear[year];
        
        const options = {
            series: [{
                name: 'Pendapatan',
                data: currentData.revenue
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#10B981'], // Green
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: currentData.months,
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 600,
                        colors: '#6B7280'
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#E5E7EB'
                }
            },
            yaxis: {
                min: 0,
                forceNiceScale: true,
                title: {
                    text: 'Pendapatan (Rp)',
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#374151'
                    }
                },
                labels: {
                    style: {
                        fontSize: '12px',
                        colors: '#6B7280'
                    },
                    formatter: function(val) {
                        if (val >= 1000000) {
                            return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                        } else if (val >= 1000) {
                            return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                        }
                        return 'Rp ' + val;
                    }
                }
            },
            grid: {
                borderColor: '#E5E7EB',
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                y: {
                    formatter: function(value) {
                        return formatRupiah(value);
                    }
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 280
                    }
                }
            }]
        };

        // Destroy previous chart if exists
        if (revenueChart) {
            revenueChart.destroy();
        }

        // Render new chart
        revenueChart = new ApexCharts(document.querySelector("#revenueChart"), options);
        revenueChart.render();
    }

    // Initial render of revenue chart
    renderRevenueChart(latestYear);

    // Revenue year selector change event
    document.getElementById('revenueYearSelector').addEventListener('change', function() {
        const selectedYear = parseInt(this.value);
        renderRevenueChart(selectedYear);
    });
</script>
@endpush
