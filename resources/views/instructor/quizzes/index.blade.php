@extends('panel.layouts.app')

@section('title', 'Tugas/Postest')

@push('styles')
    <style>
        .search-highlight {
            background-color: rgba(59, 130, 246, 0.2);
            padding: 0 2px;
            border-radius: 2px;
        }
        .sort-icon {
            transition: transform 0.2s ease;
        }
        .sort-icon.rotate-180 {
            transform: rotate(180deg);
        }
        .table-row-enter {
            animation: fadeIn 0.3s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
        <div class="container px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Hero Section with Stats -->
            <div class="relative mb-8 overflow-hidden bg-blue-600 dark:bg-slate-800 dark:via-slate-900 dark:to-slate-950 rounded-2xl">
                <div class="relative px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="text-white max-w-2xl">
                            <p class="text-sm text-blue-100 sm:text-base">
                                Buat dan kelola tugas/postest untuk program Anda.
                            </p>
                        </div>

                        <!-- Desktop CTA Button -->
                        <a href="{{ route('instructor.quizzes.create') }}"
                            class="hidden md:inline-flex items-center gap-2 px-4 py-2 sm:px-6 sm:py-3 text-sm font-semibold text-blue-600 transition-all duration-200 bg-white rounded-xl hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 shadow-lg hover:shadow-xl dark:bg-slate-800 dark:via-slate-900 dark:to-slate-950 dark:hover:bg-slate-700/50 dark:text-white dark:hover:text-white">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Tugas/Postest
                        </a>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-3 mt-8 sm:gap-4">
                        <div class="p-3 sm:p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-2 bg-green-400/20 rounded-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p id="stat-published" class="text-lg sm:text-2xl font-bold text-white">0</p>
                                    <p class="hidden sm:block text-xs sm:text-sm text-blue-200">Dipublikasikan</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-2 bg-blue-400/20 rounded-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p id="stat-total" class="text-lg sm:text-2xl font-bold text-white">0</p>
                                    <p class="hidden sm:block text-xs sm:text-sm text-blue-200">Total Tugas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input" placeholder="Cari judul tugas, program..."
                            class="search-input w-full py-3 pl-12 pr-10 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                        <button id="search-clear" class="hidden absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Items per page -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Tampilkan:</span>
                        <select id="per-page-select" class="py-1.5 pl-3 pr-8 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters & Results Count -->
                <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan <span id="results-showing" class="font-semibold text-blue-600">0</span> dari
                        <span id="results-total" class="font-semibold">0</span> tugas
                        <span id="search-term-display" class="hidden text-gray-500">
                            untuk "<span class="search-term font-medium text-blue-600"></span>"
                        </span>
                    </p>

                    <div id="active-filters" class="hidden flex items-center gap-2">
                        <button id="reset-filter-btn" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block mb-8 overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750">
                                <th class="px-6 py-4 text-left">
                                    <button data-sort="title" class="inline-flex items-center gap-2 text-xs font-semibold tracking-wider text-gray-600 uppercase hover:text-blue-600 dark:text-gray-400 transition-colors">
                                        Judul Tugas
                                        <svg class="w-4 h-4 sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <span class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Program</span>
                                </th>
                                <th class="px-6 py-4 text-center">
                                    <span class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Pertanyaan</span>
                                </th>
                                <th class="px-6 py-4 text-center">
                                    <span class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Respon</span>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <button data-sort="created_at" class="inline-flex items-center gap-2 text-xs font-semibold tracking-wider text-gray-600 uppercase hover:text-blue-600 dark:text-gray-400 transition-colors">
                                        Tanggal
                                        <svg class="w-4 h-4 sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-center">
                                    <span class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="desktop-tbody" class="divide-y divide-gray-100 dark:divide-gray-700">
                            <!-- Rows will be rendered by JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State Desktop -->
                <div id="empty-state-desktop" class="hidden px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <div class="empty-state-icon p-4 bg-blue-50 rounded-full mb-4 dark:bg-blue-900/30">
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="empty-title text-lg font-semibold text-gray-900 dark:text-white mb-1">Belum ada tugas</h3>
                        <p class="empty-desc text-sm text-gray-500 dark:text-gray-400 mb-4">Buat tugas/postest Anda</p>
                        <a href="{{ route('instructor.quizzes.create') }}" class="empty-create-btn inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Tugas Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div id="mobile-list" class="lg:hidden space-y-4 mb-8">
                <!-- Cards will be rendered by JavaScript -->
            </div>
            
            <!-- Mobile Empty State -->
            <div id="empty-state-mobile" class="hidden lg:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center mb-8">
                <div class="flex flex-col items-center">
                    <div class="p-4 bg-blue-50 rounded-full mb-4 dark:bg-blue-900/30">
                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="empty-title text-lg font-semibold text-gray-900 dark:text-white mb-1">Belum ada tugas</h3>
                    <p class="empty-desc text-sm text-gray-500 dark:text-gray-400 mb-4">Buat tugas/postest Anda</p>
                </div>
            </div>
            <!-- Pagination -->
            <div id="pagination-container" class="hidden mb-20 lg:mb-0 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p id="pagination-info" class="text-sm text-gray-600 dark:text-gray-400 order-2 sm:order-1">
                    Halaman <span class="font-semibold text-blue-600">1</span> dari <span class="font-semibold">1</span>
                </p>
                <div id="pagination-buttons" class="flex items-center gap-1 order-1 sm:order-2">
                    <!-- Pagination buttons will be rendered by JavaScript -->
                </div>
            </div>

            <!-- Mobile FAB -->
            <a href="{{ route('instructor.quizzes.create') }}"
                class="md:hidden btn-float inline-flex items-center justify-center w-14 h-14 text-white bg-blue-600 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>

        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity duration-300">
            <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-900/30">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
                    <p class="text-sm text-center text-gray-600 dark:text-gray-400 mb-6">
                        Apakah Anda yakin ingin menghapus tugas "<span id="delete-target-title" class="font-medium text-gray-900 dark:text-white"></span>"? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex items-center gap-3">
                        <button id="delete-cancel" data-action="cancel" type="button"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <form id="delete-form" action="" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz data for JavaScript -->
    <script type="application/json" id="quizzesData">@json($quizzes ?? [])</script>

    @push('scripts')
        <script src="{{ asset('assets/elearning/instructor/js/table-core.js') }}"></script>
        <script src="{{ asset('assets/elearning/instructor/js/quizzes/index.js') }}"></script>
    @endpush
@endsection