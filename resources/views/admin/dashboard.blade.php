@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Greeting Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-200">
            Hai Admin,
        </h2>
        <p class="text-lg text-purple-600 dark:text-purple-400 font-semibold mt-1">
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

    <!-- Combined Chart Section -->
    <div class="mb-8">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-300">
                        Statistik 12 Bulan Terakhir
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan keuangan, pengguna, instruktur, dan program dalam satu chart
                    </p>
                </div>
            </div>
            <div id="combinedChart"></div>
            <div class="flex justify-start mt-4 space-x-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 mr-1 bg-teal-500 rounded-full"></span>
                    <span>Keuangan (dalam ribuan rupiah)</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 mr-1 bg-orange-500 rounded-full"></span>
                    <span>Pengguna</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 mr-1 bg-blue-500 rounded-full"></span>
                    <span>Instruktur</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 mr-1 bg-purple-500 rounded-full"></span>
                    <span>Program</span>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Data dari controller
    const chartData = @json($chartData ?? []);
    
    // Normalize revenue data untuk chart (dibagi 1000 untuk scaling)
    const normalizedRevenue = chartData.revenue ? chartData.revenue.map(val => Math.round(val / 1000)) : [];
    
    // Combined Chart Configuration
    const combinedChartOptions = {
        series: [
            {
                name: 'Keuangan (dalam ribuan)',
                type: 'column',
                data: normalizedRevenue
            },
            {
                name: 'Pengguna',
                type: 'line',
                data: chartData.users || []
            },
            {
                name: 'Instruktur',
                type: 'line',
                data: chartData.instructors || []
            },
            {
                name: 'Program',
                type: 'line',
                data: chartData.programs || []
            }
        ],
        chart: {
            height: 400,
            type: 'line',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#14b8a6', '#f97316', '#3b82f6', '#a855f7'],
        stroke: {
            width: [0, 3, 3, 3],
            curve: ['smooth', 'smooth', 'smooth', 'smooth']
        },
        fill: {
            type: ['solid', 'gradient', 'gradient', 'gradient'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        markers: {
            size: [0, 4, 4, 4],
            hover: {
                size: 6
            }
        },
        xaxis: {
            categories: chartData.months || [],
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            }
        },
        yaxis: [
            {
                title: {
                    text: 'Keuangan (Rp x1000)',
                    style: {
                        color: '#14b8a6'
                    }
                },
                labels: {
                    style: {
                        colors: '#9ca3af'
                    },
                    formatter: function(value) {
                        return 'Rp. ' + value.toLocaleString('id-ID');
                    }
                }
            },
            {
                opposite: true,
                title: {
                    text: 'Jumlah',
                    style: {
                        color: '#3b82f6'
                    }
                },
                labels: {
                    style: {
                        colors: '#9ca3af'
                    },
                    formatter: function(value) {
                        return value.toLocaleString();
                    }
                }
            }
        ],
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 5
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(value, { seriesIndex }) {
                    if (seriesIndex === 0) {
                        return 'Rp. ' + (value * 1000).toLocaleString('id-ID');
                    }
                    return value.toLocaleString();
                }
            }
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right'
        }
    };

    // Initialize combined chart when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const combinedChart = new ApexCharts(document.querySelector("#combinedChart"), combinedChartOptions);
        combinedChart.render();
    });
</script>
@endpush
