@extends('instructor.layouts.app')

@section('title', 'Dashboard Instruktur')

@section('content')
    <!-- Greeting Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-700 dark:text-gray-200">
            Hai Instruktur,
        </h2>
        <p class="text-lg text-purple-600 dark:text-purple-400 font-semibold mt-1">
            SELAMAT DATANG DI DASHBOARD INSTRUKTUR!
        </p>
    </div>

    <!-- Statistic Cards Grid - 4 Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card 1: Total Program -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Program Saya
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalPrograms ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 2: Total Tugas/Postest -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Tugas/Postest
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalQuizzes ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 3: Total Siswa -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Total Siswa
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalStudents ?? 0) }}
                </p>
            </div>
        </div>

        <!-- Card 4: Tugas Dikumpulkan -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                    Tugas Dikumpulkan
                </p>
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-200">
                    {{ number_format($totalSubmissions ?? 0) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
        <!-- Quick Action: Tambah Program -->
        <a href="{{ route('instructor.programs.create') }}"
            class="flex items-center p-6 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200 cursor-pointer">
            <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Tambah Program Baru
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Buat program pembelajaran baru
                </p>
            </div>
        </a>

        <!-- Quick Action: Buat Tugas/Postest -->
        <a href="{{ route('instructor.quizzes.create') }}"
            class="flex items-center p-6 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200 cursor-pointer">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Buat Tugas/Postest
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Buat tugas atau postest baru
                </p>
            </div>
        </a>

        <!-- Quick Action: Edit Profile -->
        <a href="{{ route('instructor.profile.edit') }}"
            class="flex items-center p-6 bg-white rounded-lg shadow-md dark:bg-gray-800 hover:shadow-lg transition-shadow duration-200 cursor-pointer">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Edit Profile
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Update informasi profile Anda
                </p>
            </div>
        </a>
@endsection