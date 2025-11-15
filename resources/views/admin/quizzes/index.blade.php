@extends('admin.layouts.app')

@section('title', 'Tugas/Postest')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Tugas/Postest</h2>
                <a href="{{ route('admin.quizzes.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Tugas/Postest
                </a>
            </div>
        </div>

        <!-- Quizzes Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Instruktur</th>
                            <th class="px-4 py-3">Program</th>
                            <th class="px-4 py-3">Jumlah Pertanyaan</th>
                            <th class="px-4 py-3">Total Respon</th>
                            <th class="px-4 py-3">Tanggal Dibuat</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($quizzes as $quiz)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $quiz['title'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $quiz['instructor'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $quiz['program'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $quiz['total_questions'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $quiz['total_responses'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $quiz['created_at'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.quizzes.show', $quiz['id']) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Lihat</a>
                                    <a href="{{ route('admin.quizzes.edit', $quiz['id']) }}" class="text-green-600 hover:text-green-800 dark:text-green-400">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada tugas/postest. <a href="{{ route('admin.quizzes.create') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400">Buat tugas/postest pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $quizzes ?? null])
        </div>
    </div>
@endsection

