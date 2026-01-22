@extends('instructor.layouts.app')

@section('title', 'Tugas/Postest')

@section('content')
<div x-data="{ 
        showDeleteModal: false, 
        deleteUrl: '', 
        deleteTitle: '',
        openDelete(url, title) {
            this.deleteUrl = url;
            this.deleteTitle = title;
            this.showDeleteModal = true;
        }
    }" 
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
    
    <div class="container px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        
        <!-- Header with Create Button -->
        <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat dan kelola kuis untuk program Anda.</p>
            </div>
            
            <a href="{{ route('instructor.quizzes.create') }}"
               class="hidden md:inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition-all duration-200 bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-lg shadow-blue-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Tugas/Postest
            </a>
        </div>

        <!-- Search and Filter Bar -->
        <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <form method="GET" action="{{ route('instructor.quizzes.index') }}" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                
                <!-- Preserve existing params except search/per_page which are inputs -->
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                @if(request('direction')) <input type="hidden" name="direction" value="{{ request('direction') }}"> @endif

                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul kuis, program..."
                        class="search-input w-full py-3 pl-12 pr-10 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200"
                    >
                    @if(request('search'))
                        <a href="{{ route('instructor.quizzes.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @else
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    @endif
                </div>
                
                <!-- Items per page -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Tampilkan:</span>
                    <select 
                        name="per_page" 
                        onchange="this.form.submit()"
                        class="py-1.5 pl-3 pr-8 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block mb-8 overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750">
                            @php
                                $sortLink = function($col, $label) {
                                    $currentSort = request('sort', 'created_at');
                                    $currentDir = request('direction', 'desc');
                                    $newDir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
                                    $active = $currentSort === $col;
                                    
                                    $url = route('instructor.quizzes.index', array_merge(request()->query(), ['sort' => $col, 'direction' => $newDir]));
                                    
                                    return '<a href="'.$url.'" class="inline-flex items-center gap-2 text-xs font-semibold tracking-wider text-gray-600 uppercase hover:text-blue-600 dark:text-gray-400 transition-colors">
                                        '.$label.'
                                        <svg class="w-4 h-4 '.($active ? ($currentDir === 'asc' ? 'transform rotate-180' : '') : 'text-gray-300').'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>';
                                };
                            @endphp

                            <th class="px-6 py-4 text-left">{!! $sortLink('title', 'Judul Kuis') !!}</th>
                            <th class="px-6 py-4 text-left">{!! $sortLink('program', 'Program') !!}</th>
                            <th class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Pertanyaan</span>
                            </th>
                            <th class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Respon</span>
                            </th>
                            <th class="px-6 py-4 text-left">{!! $sortLink('created_at', 'Tanggal') !!}</th>
                            <th class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold tracking-wider text-gray-600 uppercase dark:text-gray-400">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($quizzes as $quiz)
                            <tr class="hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <span class="text-sm font-bold text-white">{{ strtoupper(substr($quiz->title, 0, 1)) }}</span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $quiz->title }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300">
                                        {{ $quiz->program }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $quiz->total_questions }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $quiz->total_responses }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <p>{{ $quiz->formatted_created_at }}</p>
                                        <p class="text-xs text-gray-400">{{ $quiz->time_ago }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('instructor.quizzes.show', $quiz->id) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Lihat
                                        </a>
                                        <a href="{{ route('instructor.quizzes.edit', $quiz->id) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </a>
                                        <button 
                                            @click="openDelete('{{ route('instructor.quizzes.destroy', $quiz->id) }}', '{{ addslashes($quiz->title) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="empty-state-icon p-4 bg-blue-50 rounded-full mb-4 dark:bg-blue-900/30">
                                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                            {{ request('search') ? 'Tidak ada hasil ditemukan' : 'Belum ada tugas' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            {{ request('search') ? 'Coba kata kunci lain' : 'Buat tugas/postest Anda' }}
                                        </p>
                                        @if(!request('search'))
                                            <a href="{{ route('instructor.quizzes.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Buat Kuis Baru
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4 mb-8">
            @foreach($quizzes as $quiz)
                <div class="mobile-card card-hover bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-4">
                        <!-- Header -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                                    <span class="text-lg font-bold text-white">{{ strtoupper(substr($quiz->title, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $quiz->title }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $quiz->formatted_created_at }} • {{ $quiz->time_ago }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Meta Info -->
                        <div class="flex flex-col gap-2 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Program:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $quiz->program }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Pertanyaan:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $quiz->total_questions }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Total Respon:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $quiz->total_responses }}</span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('instructor.quizzes.show', $quiz->id) }}" 
                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </a>
                            <a href="{{ route('instructor.quizzes.edit', $quiz->id) }}" 
                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                            <button 
                                @click="openDelete('{{ route('instructor.quizzes.destroy', $quiz->id) }}', '{{ addslashes($quiz->title) }}')"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if($quizzes->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada hasil ditemukan.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($quizzes->hasPages())
            <div class="mt-6">
                {{ $quizzes->appends(request()->query())->links() }}
            </div>
        @endif

        <!-- Mobile FAB -->
        <a href="{{ route('instructor.quizzes.create') }}"
           class="md:hidden btn-float inline-flex items-center justify-center w-14 h-14 text-white bg-blue-600 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </a>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-cloak
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.self="showDeleteModal = false">
        <div x-show="showDeleteModal"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-900/30">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
                <p class="text-sm text-center text-gray-600 dark:text-gray-400 mb-6">
                    Apakah Anda yakin ingin menghapus kuis "<span class="font-medium text-gray-900 dark:text-white" x-text="deleteTitle"></span>"? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex items-center gap-3">
                    <button 
                        @click="showDeleteModal = false"
                        type="button"
                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors"
                    >
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors"
                        >
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection