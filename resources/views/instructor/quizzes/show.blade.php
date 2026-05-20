@extends('panel.layouts.app')

@section('title', 'Tugas Akhir')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100">
        <div class="container px-6 py-6 mx-auto max-w-4xl">
            <div class="mb-6">
                <a href="{{ route('instructor.quizzes.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Tugas Akhir</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Detail tugas akhir dapat dilihat pada halaman Tugas Akhir per program.
                    Gunakan halaman tersebut untuk meninjau dan memberi nilai.
                </p>
                <div class="mt-6">
                    <a href="{{ route('instructor.assignments.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Kelola Tugas Akhir
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
