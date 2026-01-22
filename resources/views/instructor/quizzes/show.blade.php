@extends('instructor.layouts.app')

@section('title', 'Detail Tugas/Postest')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Detail informasi tugas/postest.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('instructor.quizzes.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('instructor.quizzes.edit', $quiz->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <div class="px-6 py-6 space-y-6">

                <!-- Informasi Dasar -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Judul Tugas</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $quiz->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Program</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $quiz->program_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $quiz->type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $quiz->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($quiz->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                        <p class="mt-1 text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $quiz->description ?? '-' }}</p>
                    </div>
                </div>

                <!-- Pertanyaan -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Daftar Pertanyaan ({{ count($questions) }})</h3>
                    
                    <div class="space-y-4">
                        @forelse($questions as $index => $question)
                            <div class="p-4 border border-gray-200 rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-blue-600 rounded-full">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="text-xs font-medium px-2 py-0.5 rounded bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                            </span>
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                {{ $question->points }} Poin
                                            </span>
                                        </div>
                                        <p class="text-gray-800 dark:text-gray-200 font-medium mb-3">{{ $question->question }}</p>
                                        
                                        @if($question->type === 'multiple_choice' || $question->type === 'true_false')
                                            <div class="ml-8 space-y-2">
                                                @if($question->options)
                                                    @foreach($question->options as $optIndex => $option)
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center {{ $question->correct_answer == $option ? 'bg-green-500 border-green-500' : '' }}">
                                                                @if($question->correct_answer == $option)
                                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                                @endif
                                                            </div>
                                                            <span class="text-sm {{ $question->correct_answer == $option ? 'text-green-700 font-medium dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                                {{ $option }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Belum ada pertanyaan.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
