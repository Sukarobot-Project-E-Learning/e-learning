@extends('instructor.layouts.app')

@section('title', 'Dashboard Instruktur')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8">

        <!-- Hero Section -->
        <div class="relative w-full bg-blue-600 dark:bg-slate-800 dark:via-slate-900 dark:to-slate-950 rounded-2xl sm:rounded-3xl lg:rounded-[2.5rem] p-3 sm:p-5 lg:p-7 overflow-hidden shadow-2xl transition-all duration-500">
            
            <div class="flex flex-col lg:flex-row items-center justify-between relative z-10 gap-8">
                
                <!-- Left Content -->
                <div class="w-full lg:w-3/5 space-y-6 sm:space-y-8 text-center lg:text-left">
                    
                    <!-- Greeting -->
                    <div class="space-y-3">                        
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                            Hai, <span class="text-orange-300 dark:text-orange-400">{{ Auth::user()->name }}</span>
                        </h2>
                        <p class="text-base sm:text-lg text-blue-100 dark:text-slate-300 font-medium max-w-md mx-auto lg:mx-0">
                            Selamat datang kembali! Mari kelola dan pantau aktivitas pembelajaran Anda hari ini.
                        </p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                        
                        <!-- Stat Card 1 -->
                        <div class="group relative bg-white/10 dark:bg-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-white/20 dark:border-white/10 hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl shadow-lg shadow-orange-500/25 group-hover:shadow-orange-500/40 transition-shadow">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ number_format($totalPrograms ?? 0) }}</p>
                                    <p class="text-xs sm:text-sm text-blue-100 dark:text-slate-300 font-medium">Program Saya</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stat Card 2 -->
                        <div class="group relative bg-white/10 dark:bg-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-white/20 dark:border-white/10 hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-blue-300 to-blue-500 rounded-xl shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 transition-shadow">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ number_format($totalQuizzes ?? 0) }}</p>
                                    <p class="text-xs sm:text-sm text-blue-100 dark:text-slate-300 font-medium">Tugas/Postest</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stat Card 3 -->
                        <div class="group relative bg-white/10 dark:bg-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-white/20 dark:border-white/10 hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl shadow-lg shadow-emerald-500/25 group-hover:shadow-emerald-500/40 transition-shadow">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ number_format($totalStudents ?? 0) }}</p>
                                    <p class="text-xs sm:text-sm text-blue-100 dark:text-slate-300 font-medium">Total Siswa</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stat Card 4 -->
                        <div class="group relative bg-white/10 dark:bg-white/5 backdrop-blur-lg rounded-xl sm:rounded-2xl p-4 sm:p-5 border border-white/20 dark:border-white/10 hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-violet-400 to-violet-600 rounded-xl shadow-lg shadow-violet-500/25 group-hover:shadow-violet-500/40 transition-shadow">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ number_format($totalSubmissions ?? 0) }}</p>
                                    <p class="text-xs sm:text-sm text-blue-100 dark:text-slate-300 font-medium">Dikumpulkan</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Illustration -->
                <div class="hidden lg:flex w-2/5 justify-center items-end relative">
                    <div class="relative">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-t from-orange-400/20 to-transparent rounded-full blur-3xl scale-110"></div>
                        
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/business-woman-standing-with-folder-illustration-download-in-svg-png-gif-file-formats--young-female-pose-happy-3d-character-pack-people-illustrations-4735503.png" 
                             alt="Instructor Avatar" 
                             class="relative object-contain h-64 xl:h-80 drop-shadow-2xl hover:scale-105 transition-transform duration-500 ease-out"
                             loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Manage Activities Section -->
        <div class="space-y-4 sm:space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">
                    Kelola Aktivitas
                </h3>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                
                <!-- Action Card 1 - Add Program -->
                <a href="{{ route('instructor.programs.create') }}" class="group relative block bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-lg dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 hover:border-orange-200 dark:hover:border-orange-900 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    
                    <!-- Hover Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-orange-600/0 group-hover:from-orange-500/5 group-hover:to-orange-600/10 dark:group-hover:from-orange-500/10 dark:group-hover:to-orange-600/20 transition-all duration-300"></div>
                    
                    <div class="relative z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-orange-500/25 group-hover:shadow-orange-500/40 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h4 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold mb-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Tambah Program</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Buat materi pembelajaran baru untuk siswa</p>
                        
                        <!-- Arrow Icon -->
                        <div class="absolute top-5 sm:top-6 right-5 sm:right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-gray-500 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/30 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-all duration-300">
                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Decorative Circle -->
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-500/5 dark:bg-orange-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                </a>

                <!-- Action Card 2 - Create Quiz -->
                <a href="{{ route('instructor.quizzes.create') }}" class="group relative block bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-lg dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 overflow-hidden">
                    
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-600/0 group-hover:from-blue-500/5 group-hover:to-blue-600/10 dark:group-hover:from-blue-500/10 dark:group-hover:to-blue-600/20 transition-all duration-300"></div>
                    
                    <div class="relative z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Buat Tugas/Quiz</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Evaluasi dan ukur hasil belajar siswa</p>
                        
                        <div class="absolute top-5 sm:top-6 right-5 sm:right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-gray-500 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-all duration-300">
                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-500/5 dark:bg-blue-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                </a>

                <!-- Action Card 3 - Edit Profile -->
                <a href="{{ route('client.dashboard') }}" data-turbo="false" class="group relative block bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-lg dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-900 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 overflow-hidden sm:col-span-2 lg:col-span-1">
                    
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-emerald-600/0 group-hover:from-emerald-500/5 group-hover:to-emerald-600/10 dark:group-hover:from-emerald-500/10 dark:group-hover:to-emerald-600/20 transition-all duration-300"></div>
                    
                    <div class="relative z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/25 group-hover:shadow-emerald-500/40 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Edit Profile</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Perbarui informasi akun anda</p>
                        
                        <div class="absolute top-5 sm:top-6 right-5 sm:right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-gray-500 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/30 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-all duration-300">
                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                </a>

            </div>
        </div>

    </div>
@endsection
