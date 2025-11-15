@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')

    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <!-- Title -->
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">User</h2>
            </div>

            <!-- Filter and Tambah User Buttons -->
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
                                Filter Status
                            </h6>
                            <ul class="space-y-2.5">
                                <li>
                                    <label class="inline-flex items-center w-full cursor-pointer">
                                        <input type="checkbox" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="inline-flex items-center w-full cursor-pointer">
                                        <input type="checkbox" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Non-Aktif</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="inline-flex items-center w-full cursor-pointer">
                                        <input type="checkbox" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Pending</span>
                                    </label>
                                </li>
                            </ul>
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

                <!-- Tambah User Button -->
                <button type="button"
                        onclick="window.location.href='{{ route('admin.users.create') }}'"
                        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Tambah User
                    <span class="ml-2" aria-hidden="true">+</span>
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">

            <!-- Search Bar Section -->
            <div class="w-full px-4 py-4 bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <div class="relative w-full max-w-xl">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           id="table-search" 
                           class="w-full pl-10 pr-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-gray-100 border-0 rounded-lg dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input" 
                           placeholder="Cari user...">
                </div>
            </div>

            <!-- Table Section -->
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-bold tracking-wide text-left text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <!-- Sortable Headers -->
                            <th class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none" 
                                x-data="{ sortAsc: true }"
                                @click="sortAsc = !sortAsc">
                                <div class="flex items-center gap-1">
                                    <span>NAMA</span>
                                    <div class="flex flex-col -my-1">
                                        <svg class="w-2.5 h-2.5" :class="sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg class="w-2.5 h-2.5" :class="!sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                            <th class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none"
                                x-data="{ sortAsc: true }"
                                @click="sortAsc = !sortAsc">
                                <div class="flex items-center gap-1">
                                    <span>EMAIL</span>
                                    <div class="flex flex-col -my-1">
                                        <svg class="w-2.5 h-2.5" :class="sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg class="w-2.5 h-2.5" :class="!sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                            <th class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none"
                                x-data="{ sortAsc: true }"
                                @click="sortAsc = !sortAsc">
                                <div class="flex items-center gap-1">
                                    <span>TELEPON</span>
                                    <div class="flex flex-col -my-1">
                                        <svg class="w-2.5 h-2.5" :class="sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg class="w-2.5 h-2.5" :class="!sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                            <th class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none"
                                x-data="{ sortAsc: true }"
                                @click="sortAsc = !sortAsc">
                                <div class="flex items-center gap-1">
                                    <span>FOTO</span>
                                    <div class="flex flex-col -my-1">
                                        <svg class="w-2.5 h-2.5" :class="sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg class="w-2.5 h-2.5" :class="!sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                            <th class="px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none"
                                x-data="{ sortAsc: true }"
                                @click="sortAsc = !sortAsc">
                                <div class="flex items-center gap-1">
                                    <span>STATUS</span>
                                    <div class="flex flex-col -my-1">
                                        <svg class="w-2.5 h-2.5" :class="sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        <svg class="w-2.5 h-2.5" :class="!sortAsc ? 'text-gray-600' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                            <th class="px-4 py-3">ACTION</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach($users as $user)
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-4 py-3 text-sm">
                                {{ $user['name'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user['email'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user['phone'] }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    @if(isset($user['photo']) && $user['photo'])
                                        <img src="{{ $user['photo'] }}" 
                                             alt="{{ $user['name'] }}" 
                                             class="w-10 h-10 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                                    @else
                                        <!-- Placeholder dengan angka 0 -->
                                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-300 dark:border-gray-500">
                                            <span class="text-gray-600 dark:text-gray-300 text-sm font-medium">0</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded dark:bg-green-700 dark:text-green-100">
                                    {{ $user['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center" style="gap: 12px;">
                                    <!-- Edit Button (Blue Square) -->
                                    <button class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors duration-150" 
                                            aria-label="Edit"
                                            onclick="window.location.href='{{ route('admin.users.edit', $user['id']) }}'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>

                                    <!-- Delete Button (Red Square) -->
                                    <form action="{{ route('admin.users.destroy', $user['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');" class="inline-block m-0">
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
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            <div class="flex flex-col items-center justify-between px-4 py-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 sm:flex-row sm:px-6">
                <!-- Showing Info -->
                <div class="flex items-center mb-4 sm:mb-0">
                    <span class="text-sm text-gray-700 dark:text-gray-400">
                        Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ count($users) }}</span> of <span class="font-semibold text-gray-900 dark:text-white">100</span> results
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
                            <a href="#" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">4</a>
                        </li>
                        <li>
                            <span class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">67</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">68</a>
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
