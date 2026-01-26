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

        .sort-icon.asc {
            transform: rotate(180deg);
        }

        .table-row-enter {
            animation: fadeIn 0.3s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div x-data="quizTable()" x-init="init()"
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
        <div class="container px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Hero Section with Stats -->
            <div
                class="relative mb-8 overflow-hidden bg-blue-600 dark:bg-slate-800 dark:via-slate-900 dark:to-slate-950 rounded-2xl">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Buat Tugas/Postest
                        </a>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-3 mt-8 sm:gap-4">
                        <div class="p-3 sm:p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-2 bg-green-400/20 rounded-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg sm:text-2xl font-bold text-white" x-text="stats.published">0</p>
                                    <p class="hidden sm:block text-xs sm:text-sm text-blue-200">Dipublikasikan</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="p-2 bg-blue-400/20 rounded-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg sm:text-2xl font-bold text-white" x-text="stats.total">0</p>
                                    <p class="hidden sm:block text-xs sm:text-sm text-blue-200">Total Tugas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div
                class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="filterData()"
                            placeholder="Cari judul tugas, program..."
                            class="search-input w-full py-3 pl-12 pr-10 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                        <button x-show="searchQuery.length > 0" @click="searchQuery = ''; filterData()"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Items per page -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Tampilkan:</span>
                        <select x-model="perPage" @change="filterData()"
                            class="py-1.5 pl-3 pr-8 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters & Results Count -->
                <div
                    class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan <span class="font-semibold text-blue-600" x-text="paginatedData.length"></span> dari
                        <span class="font-semibold" x-text="filteredData.length"></span> tugas
                        <span x-show="searchQuery.length > 0" class="text-gray-500">
                            untuk "<span class="font-medium text-blue-600" x-text="searchQuery"></span>"
                        </span>
                    </p>

                    <div x-show="searchQuery.length > 0" class="flex items-center gap-2">
                        <button @click="searchQuery = ''; filterData()"
                            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div
                class="hidden lg:block mb-8 overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750">
                                <th class="px-6 py-4 text-left">
                                    <button @click="sortBy('title')"
                                        class="inline-flex items-center gap-2 text-xs font-semibold tracking-wider text-gray-600 uppercase hover:text-blue-600 dark:text-gray-400 transition-colors">
                                        Judul Tugas
                                        <svg class="w-4 h-4 sort-icon"
                                            :class="{ 'asc': sortColumn === 'title' && sortDirection === 'asc' }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <span
                                        class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Program</span>
                                </th>
                                <th class="px-6 py-4 text-center">
                                    <span
                                        class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Pertanyaan</span>
                                </th>
                                <th class="px-6 py-4 text-center">
                                    <span
                                        class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Respon</span>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <button @click="sortBy('created_at')"
                                        class="inline-flex items-center gap-2 text-xs font-semibold tracking-wider text-gray-600 uppercase hover:text-blue-600 dark:text-gray-400 transition-colors">
                                        Tanggal
                                        <svg class="w-4 h-4 sort-icon"
                                            :class="{ 'asc': sortColumn === 'created_at' && sortDirection === 'asc' }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-center">
                                    <span
                                        class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <template x-for="(quiz, index) in paginatedData" :key="quiz.id">
                                <tr
                                    class="table-row-enter hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                <span class="text-sm font-bold text-white"
                                                    x-text="quiz.title.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-semibold text-gray-900 dark:text-white truncate"
                                                    x-html="highlightSearch(quiz.title)"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300"
                                            x-html="highlightSearch(quiz.program || 'N/A')"></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400"
                                            x-text="quiz.total_questions"></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400"
                                            x-text="quiz.total_responses"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            <p x-text="formatDate(quiz.created_at)"></p>
                                            <p class="text-xs text-gray-400" x-text="formatTimeAgo(quiz.created_at)"></p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a :href="'/instructor/quizzes/' + quiz.id"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Lihat
                                            </a>
                                            <a :href="'/instructor/quizzes/' + quiz.id + '/edit'"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Edit
                                            </a>
                                            <button @click="confirmDelete(quiz)"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <!-- Empty State -->
                            <template x-if="paginatedData.length === 0 && !loading">
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="empty-state-icon p-4 bg-blue-50 rounded-full mb-4 dark:bg-blue-900/30">
                                                <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1"
                                                x-text="searchQuery ? 'Tidak ada hasil ditemukan' : 'Belum ada tugas'"></h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"
                                                x-text="searchQuery ? 'Coba kata kunci lain' : 'Buat tugas/postest Anda'">
                                            </p>
                                            <a x-show="!searchQuery" href="{{ route('instructor.quizzes.create') }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Buat Tugas Baru
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden space-y-4">
                <template x-for="(quiz, index) in paginatedData" :key="quiz.id">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <span class="text-lg font-bold text-white"
                                    x-text="quiz.title.charAt(0).toUpperCase()"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:text-white truncate" x-text="quiz.title"></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="quiz.program || 'N/A'"></p>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                <span x-text="quiz.total_questions + ' pertanyaan'"></span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span x-text="quiz.total_responses + ' respon'"></span>
                            </span>
                        </div>

                        <div
                            class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <span class="text-xs text-gray-400" x-text="formatDate(quiz.created_at)"></span>
                            <div class="flex items-center gap-2">
                                <a :href="'/instructor/quizzes/' + quiz.id"
                                    class="px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    Lihat
                                </a>
                                <a :href="'/instructor/quizzes/' + quiz.id + '/edit'"
                                    class="px-3 py-1.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                    Edit
                                </a>
                                <button @click="confirmDelete(quiz)"
                                    class="px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State Mobile -->
                <template x-if="paginatedData.length === 0 && !loading">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center border border-gray-100 dark:border-gray-700">
                        <div class="p-4 bg-blue-50 rounded-full mb-4 w-fit mx-auto dark:bg-blue-900/30">
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1"
                            x-text="searchQuery ? 'Tidak ada hasil' : 'Belum ada tugas'"></h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"
                            x-text="searchQuery ? 'Coba kata kunci lain' : 'Buat tugas pertama Anda'"></p>
                        <a x-show="!searchQuery" href="{{ route('instructor.quizzes.create') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Buat Tugas
                        </a>
                    </div>
                </template>
            </div>

            <!-- Pagination -->
            <div x-show="totalPages > 1"
                class="mb-20 lg:mb-0 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 order-2 sm:order-1">
                    Halaman <span class="font-semibold text-blue-600" x-text="currentPage"></span> dari <span
                        class="font-semibold" x-text="totalPages"></span>
                </p>

                <div class="flex items-center gap-1 order-1 sm:order-2">
                    <!-- First Page -->
                    <button @click="goToPage(1)" :disabled="currentPage === 1"
                        class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <!-- Previous Page -->
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                        class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Page Numbers -->
                    <template x-for="page in visiblePages" :key="page">
                        <button @click="page !== '...' && goToPage(page)" :disabled="page === '...'" :class="page === currentPage
                                    ? 'bg-blue-600 text-white'
                                    : page === '...'
                                        ? 'text-gray-400 cursor-default'
                                        : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600'"
                            class="w-10 h-10 flex items-center justify-center text-sm font-medium rounded-lg transition-colors"
                            x-text="page"></button>
                    </template>

                    <!-- Next Page -->
                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                        class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Last Page -->
                    <button @click="goToPage(totalPages)" :disabled="currentPage === totalPages"
                        class="pagination-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                    </button>
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
        <div x-show="showDeleteModal" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
            @click.self="showDeleteModal = false; deleteTarget = null;">
            <div x-show="showDeleteModal" x-cloak style="display: none;"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-6">
                    <div
                        class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-900/30">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
                    <p class="text-sm text-center text-gray-600 dark:text-gray-400 mb-6">
                        Apakah Anda yakin ingin menghapus tugas "<span class="font-medium text-gray-900 dark:text-white"
                            x-text="deleteTarget?.title || ''"></span>"? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex items-center gap-3">
                        <button @click="showDeleteModal = false; deleteTarget = null;" type="button"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <form :action="'/instructor/quizzes/' + (deleteTarget?.id || '')" method="POST" class="flex-1">
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

    @push('scripts')
        <script>
            function quizTable() {
                return {
                    // Data
                    allData: @json($quizzes ?? []),
                    filteredData: [],
                    paginatedData: [],

                    // Search
                    searchQuery: '',

                    // Sort
                    sortColumn: 'created_at',
                    sortDirection: 'desc',

                    // Pagination
                    perPage: 10,
                    currentPage: 1,
                    totalPages: 1,

                    // Stats
                    stats: {
                        published: 0,
                        draft: 0,
                        total: 0
                    },

                    // UI State
                    loading: false,
                    showDeleteModal: false,
                    deleteTarget: null,

                    init() {
                        this.calculateStats();
                        this.filterData();
                        this.showDeleteModal = false;
                    },

                    calculateStats() {
                        this.stats.published = this.allData.filter(item => item.status === 'published').length;
                        this.stats.draft = this.allData.filter(item => item.status === 'draft').length;
                        this.stats.total = this.allData.length;
                    },

                    filterData() {
                        let result = [...this.allData];

                        // Search filter
                        if (this.searchQuery.trim()) {
                            const query = this.searchQuery.toLowerCase().trim();
                            result = result.filter(item => {
                                return (item.title && item.title.toLowerCase().includes(query)) ||
                                    (item.program && item.program.toLowerCase().includes(query));
                            });
                        }

                        // Sort
                        result.sort((a, b) => {
                            let aVal = a[this.sortColumn] || '';
                            let bVal = b[this.sortColumn] || '';

                            if (this.sortColumn === 'created_at') {
                                aVal = new Date(aVal);
                                bVal = new Date(bVal);
                            } else {
                                aVal = aVal.toString().toLowerCase();
                                bVal = bVal.toString().toLowerCase();
                            }

                            if (this.sortDirection === 'asc') {
                                return aVal > bVal ? 1 : -1;
                            } else {
                                return aVal < bVal ? 1 : -1;
                            }
                        });

                        this.filteredData = result;
                        this.totalPages = Math.ceil(this.filteredData.length / this.perPage) || 1;

                        if (this.currentPage > this.totalPages) {
                            this.currentPage = 1;
                        }

                        this.updatePaginatedData();
                    },

                    updatePaginatedData() {
                        const start = (this.currentPage - 1) * this.perPage;
                        const end = start + parseInt(this.perPage);
                        this.paginatedData = this.filteredData.slice(start, end);
                    },

                    sortBy(column) {
                        if (this.sortColumn === column) {
                            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortColumn = column;
                            this.sortDirection = 'asc';
                        }
                        this.filterData();
                    },

                    goToPage(page) {
                        if (page >= 1 && page <= this.totalPages) {
                            this.currentPage = page;
                            this.updatePaginatedData();
                        }
                    },

                    get visiblePages() {
                        const delta = 2;
                        const range = [];
                        const rangeWithDots = [];

                        for (let i = Math.max(2, this.currentPage - delta); i <= Math.min(this.totalPages - 1, this
                            .currentPage + delta); i++) {
                            range.push(i);
                        }

                        if (this.currentPage - delta > 2) {
                            rangeWithDots.push(1, '...');
                        } else {
                            rangeWithDots.push(1);
                        }

                        rangeWithDots.push(...range);

                        if (this.currentPage + delta < this.totalPages - 1) {
                            rangeWithDots.push('...', this.totalPages);
                        } else if (this.totalPages > 1) {
                            rangeWithDots.push(this.totalPages);
                        }

                        return rangeWithDots.filter((item, index, arr) => arr.indexOf(item) === index);
                    },

                    highlightSearch(text) {
                        if (!this.searchQuery.trim() || !text) return text;
                        const regex = new RegExp(`(${this.searchQuery.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        return text.replace(regex, '<span class="search-highlight">$1</span>');
                    },

                    formatDate(dateString) {
                        if (!dateString) return '-';
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                    },

                    formatTimeAgo(dateString) {
                        if (!dateString) return '';
                        const date = new Date(dateString);
                        const now = new Date();
                        const diffMs = now - date;
                        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

                        if (diffDays === 0) return 'Hari ini';
                        if (diffDays === 1) return 'Kemarin';
                        if (diffDays < 7) return `${diffDays} hari lalu`;
                        if (diffDays < 30) return `${Math.floor(diffDays / 7)} minggu lalu`;
                        if (diffDays < 365) return `${Math.floor(diffDays / 30)} bulan lalu`;
                        return `${Math.floor(diffDays / 365)} tahun lalu`;
                    },

                    confirmDelete(quiz) {
                        this.deleteTarget = quiz;
                        this.showDeleteModal = true;
                    }
                }
            }
        </script>
    @endpush
@endsection