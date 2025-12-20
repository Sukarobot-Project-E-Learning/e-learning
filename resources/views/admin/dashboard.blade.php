@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Greeting Header -->
    <div class="mt-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-200">
            Hai Admin,
        </h2>
        <p class="text-lg text-purple-600 dark:text-purple-400 font-semibold mt-3">
            Selamat Datang di Sukarobot Academy Dashboard!
        </p>
    </div>

    <!-- Statistic Cards Grid - 5 Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total User -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex items-start space-x-4 rounded-lg">
            <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total User</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalUsers }}</h4>
            </div>
        </div>

        <!-- Card 2: Total Instructor -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex items-start space-x-4 rounded-lg">
            <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Instructor</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalInstructors }}</h4>
            </div>
        </div>

        <!-- Card 3: Total Program -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex items-start space-x-4 rounded-lg">
            <div class="p-3 rounded-lg bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Program</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalPrograms }}</h4>
            </div>
        </div>

        <!-- Card 4: Program Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex items-start space-x-4 rounded-lg">
            <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Program Aktif</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $programsAvailable }}</h4>
            </div>
        </div>

        <!-- Card 5: Program Non Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex items-start space-x-4 rounded-lg">
            <div class="p-3 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Program Non Aktif</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $programsUnavailable }}</h4>
            </div>
        </div>
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
                    $latestYear = max($availableYears);
                @endphp
                
                <label for="yearSelector" class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun:</label>
                <select id="yearSelector" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 px-3 py-2">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $latestYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="platformChart"></div>
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
        
        const cumulativeUsers = calculateCumulativeData(currentData.users);
        const cumulativeInstructors = calculateCumulativeData(currentData.instructors);
        const cumulativePrograms = calculateCumulativeData(currentData.programs);

        const options = {
            series: [
                {
                    name: 'Pengguna',
                    data: cumulativeUsers
                },
                {
                    name: 'Instruktur',
                    data: cumulativeInstructors
                },
                {
                    name: 'Total Program',
                    data: cumulativePrograms
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
                }
            },
            colors: ['#F97316', '#3B82F6', '#A855F7'], // Orange, Blue, Purple
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
                title: {
                    text: 'Total Kumulatif',
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
                        return Math.floor(val);
                    }
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
</script>
@endpush
