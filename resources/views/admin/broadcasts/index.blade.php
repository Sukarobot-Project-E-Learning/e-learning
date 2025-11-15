@extends('admin.layouts.app')

@section('title', 'Broadcast Management')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <!-- Title -->
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Broadcast</h2>
            </div>

            <!-- Filter and Tambah Broadcast Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                <div class="relative" x-data="{ filterOpen: false }">
                    <button @click="filterOpen = !filterOpen" 
                            @keydown.escape.window="filterOpen = false" 
                            type="button" 
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg active:bg-white hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 10h12M9 16h6"></path>
                        </svg>
                        <span>Filter</span>
                    </button>

                    <!-- Filter Dropdown Menu -->
                    <div x-show="filterOpen"
                         x-cloak
                         @click.away="filterOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 z-20 w-72 mt-2 bg-white border border-gray-200 rounded-lg shadow-xl dark:bg-gray-800 dark:border-gray-700">
                        <div class="p-4">
                            <h6 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                Filter
                            </h6>
                            <div class="flex gap-2 pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" @click="filterOpen = false" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    Reset
                                </button>
                                <button type="button" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                    Terapkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tambah Broadcast Button -->
                <button type="button"
                        onclick="window.location.href='{{ route('admin.broadcasts.create') }}'"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Tambah Broadcast
                    <span class="ml-2" aria-hidden="true">+</span>
                </button>
            </div>
        </div>

        <!-- Messages List Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <div class="px-4 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-1">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Pesan</span>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <div class="divide-y dark:divide-gray-700">
                @foreach($broadcasts as $broadcast)
                <div class="px-4 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-start justify-between">
                        <p class="text-sm text-gray-700 dark:text-gray-300 flex-1">
                            {{ $broadcast['message'] }}
                        </p>
                        <div class="flex items-center ml-4" style="gap: 12px;">
                            <!-- Edit Button (Blue Square) -->
                            <a href="{{ route('admin.broadcasts.edit', $broadcast['id']) }}"
                               class="flex items-center justify-center w-8 h-8 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors duration-150"
                               aria-label="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <!-- Delete Button (Red Square) -->
                            <form action="{{ route('admin.broadcasts.destroy', $broadcast['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus broadcast ini?');" class="inline-block m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="flex items-center justify-center w-8 h-8 text-white rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-colors duration-150"
                                        style="background-color: #ef4444 !important;"
                                        onmouseover="this.style.backgroundColor='#dc2626'"
                                        onmouseout="this.style.backgroundColor='#ef4444'"
                                        aria-label="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination Section -->
            <div class="flex flex-col items-center justify-between px-4 py-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 sm:flex-row sm:px-6">
                <!-- Showing Info -->
                <div class="flex items-center mb-4 sm:mb-0">
                    <span class="text-sm text-gray-700 dark:text-gray-400">
                        Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ count($broadcasts) }}</span> of <span class="font-semibold text-gray-900 dark:text-white">58</span> results
                    </span>
                </div>

                <!-- Pagination Controls -->
                <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center -space-x-px">
                        <!-- Previous Button -->
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 ml-0 text-sm leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Previous
                            </a>
                        </li>
                        
                        <!-- Page Numbers -->
                        <li>
                            <a href="#" aria-current="page" class="flex items-center justify-center px-4 py-2 text-sm font-semibold text-white border border-purple-600 bg-purple-600 hover:bg-purple-700 dark:border-purple-500 dark:bg-purple-600 dark:hover:bg-purple-700">1</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">3</a>
                        </li>
                        <li>
                            <span class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">57</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">58</a>
                        </li>
                        
                        <!-- Next Button -->
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                Next
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>

@endsection

