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

    <!-- Statistic Cards Grid - 6 Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
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

        <!-- Card 2: Modul Aktif -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Modul Aktif
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalLevels ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 3: Konten Siswa -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2h-1.528A6 6 0 004 9.528V4z"></path>
                    <path fill-rule="evenodd" d="M8 10a4 4 0 00-3.446 6.032l-1.261 1.26a1 1 0 101.414 1.415l1.261-1.261A4 4 0 108 10zm-2 4a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Konten Siswa
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalStudents ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 4: Total Admin -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Total Admin
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalAdmins ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 5: Total Program -->
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

        <!-- Card 6: Total Keuntungan -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-pink-500 bg-pink-100 rounded-full dark:text-pink-100 dark:bg-pink-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Total Keuntungan
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    @if($totalRevenue > 0)
                        Rp. {{ number_format($totalRevenue, 0, ',', '.') }}
                    @else
                        Rp. 0
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <!-- Revenue Chart -->
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-300">
                        Keuntungan
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan total keuntungan dalam 12 bulan terakhir
                    </p>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <span id="revenueYearText">2024</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-10 dark:bg-gray-700">
                        <div class="py-1">
                            <a href="#" @click.prevent="changeRevenueYear(2024); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2024</a>
                            <a href="#" @click.prevent="changeRevenueYear(2023); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2023</a>
                            <a href="#" @click.prevent="changeRevenueYear(2022); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2022</a>
                            <a href="#" @click.prevent="changeRevenueYear(2021); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2021</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="revenueChart"></div>
            <div class="flex justify-start mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 mr-1 bg-teal-500 rounded-full"></span>
                    <span>Januari - Desember <span id="revenueYearLabel">2024</span></span>
                </div>
            </div>
        </div>

        <!-- Users Chart -->
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-300">
                        Pengguna
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan pertumbuhan pengguna dalam 12 bulan terakhir
                    </p>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <span id="usersYearText">2024</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-10 dark:bg-gray-700">
                        <div class="py-1">
                            <a href="#" @click.prevent="changeUsersYear(2024); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2024</a>
                            <a href="#" @click.prevent="changeUsersYear(2023); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2023</a>
                            <a href="#" @click.prevent="changeUsersYear(2022); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2022</a>
                            <a href="#" @click.prevent="changeUsersYear(2021); open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">2021</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="usersChart"></div>
            <div class="flex justify-start mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 mr-1 bg-blue-500 rounded-full"></span>
                    <span>Januari - Desember <span id="usersYearLabel">2024</span></span>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Data untuk berbagai tahun (simulasi data dalam juta rupiah)
    const revenueData = {
        2024: [1500000, 2000000, 1800000, 2200000, 2500000, 2700000, 2400000, 2900000, 3100000, 2800000, 3200000, 3500000],
        2023: [1200000, 1800000, 1600000, 2000000, 2200000, 2400000, 2100000, 2600000, 2800000, 2500000, 2900000, 3200000],
        2022: [1000000, 1500000, 1400000, 1700000, 1900000, 2100000, 1800000, 2300000, 2500000, 2200000, 2600000, 2800000],
        2021: [800000, 1200000, 1100000, 1400000, 1600000, 1800000, 1500000, 2000000, 2200000, 1900000, 2300000, 2500000]
    };

    const usersData = {
        2024: [120, 180, 220, 280, 350, 420, 480, 550, 620, 680, 750, 820],
        2023: [100, 150, 180, 230, 290, 350, 400, 460, 520, 570, 630, 690],
        2022: [80, 120, 150, 190, 240, 290, 330, 380, 430, 470, 520, 570],
        2021: [60, 90, 120, 150, 190, 230, 260, 300, 340, 370, 410, 450]
    };

    let revenueChart, usersChart;

    // ApexCharts configuration for Keuntungan (Revenue)
    const revenueOptions = {
        series: [{
            name: 'Keuntungan',
            data: revenueData[2024]
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        colors: ['#14b8a6'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#9ca3af'
                },
                formatter: function(value) {
                    // Format ke Rupiah
                    return 'Rp. ' + value.toLocaleString('id-ID');
                }
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 5
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function(value) {
                    // Format ke Rupiah dengan detail
                    return 'Rp. ' + value.toLocaleString('id-ID');
                }
            }
        }
    };

    // ApexCharts configuration for Pengguna (Users)
    const usersOptions = {
        series: [{
            name: 'Pengguna',
            data: usersData[2024]
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        colors: ['#3b82f6'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#9ca3af'
                },
                formatter: function(value) {
                    return value.toLocaleString();
                }
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 5
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function(value) {
                    return value.toLocaleString();
                }
            }
        }
    };

    // Function to change revenue year
    window.changeRevenueYear = function(year) {
        document.getElementById('revenueYearText').textContent = year;
        document.getElementById('revenueYearLabel').textContent = year;

        revenueChart.updateSeries([{
            name: 'Keuntungan',
            data: revenueData[year]
        }]);
    };

    // Function to change users year
    window.changeUsersYear = function(year) {
        document.getElementById('usersYearText').textContent = year;
        document.getElementById('usersYearLabel').textContent = year;

        usersChart.updateSeries([{
            name: 'Pengguna',
            data: usersData[year]
        }]);
    };

    // Initialize charts when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revenueChart.render();

        // Users Chart
        usersChart = new ApexCharts(document.querySelector("#usersChart"), usersOptions);
        usersChart.render();
    });
</script>
@endpush
