@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Greeting Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-200">
            Hai Admin,
        </h2>
        <p class="text-lg text-purple-600 dark:text-purple-400 font-semibold mt-3">
            SELAMAT DATANG DI PEMBELAJARAN ELEKTRONIK!
        </p>
    </div>

    <!-- Statistic Cards Grid - 5 Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-5">
        <!-- Card 1: Pengguna -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Pengguna
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalUsers ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 2: Instruktur -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Instruktur
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalInstructors ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 3: Total Program -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Total Program
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalPrograms ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 4: Program Tersedia -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Program Tersedia
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($programsAvailable ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 5: Program Tidak Tersedia -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Program Tidak Tersedia
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($programsUnavailable ?? 0) }}
                </p>
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
