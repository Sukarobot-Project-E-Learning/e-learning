@extends('panel.layouts.app')

@section('title', 'Dashboard Instruktur')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8">

        <!-- Blue Header Band + Vertically Split Hero -->
        <div class="relative">
            <!-- Blue Background Band -->
            <div class="bg-blue-700 dark:bg-blue-600 rounded-2xl sm:rounded-3xl lg:rounded-[2.5rem] h-56 sm:h-64 lg:h-72 w-full absolute top-0 left-0 right-0 overflow-hidden shadow-xl">
                
            </div>

            <!-- Content over the band -->
            <div class="relative z-10 pt-6 sm:pt-8 lg:pt-10 px-4 sm:px-6 lg:px-8">

                <!-- Greeting Row -->
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-8 sm:mb-10">
                    <div class="space-y-2">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                            Dashboard <span class="text-blue-100">{{ Auth::user()->name }}</span>
                        </h2>
                        <p class="text-sm sm:text-base text-blue-100 dark:text-blue-200 font-large max-w-lg">
                            Selamat datang kembali, mari kelola dan pantau aktivitas pembelajaran Anda hari ini.
                        </p>
                    </div>

                    <!-- Right Action Button -->
                    <div class="mt-2 lg:mt-0 lg:shrink-0">
                        <a href="{{ route('instructor.programs.create') }}" class="inline-flex items-center justify-center px-5 py-3 sm:px-6 sm:py-3.5 text-sm sm:text-base font-semibold rounded-xl text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-700 focus:ring-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajukan Program Baru
                        </a>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">

                    <!-- Card 1 - Program Saya -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl p-4 shadow-md border border-gray-100 dark:border-slate-800">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Program Saya</p>
                            <div class="shrink-0 w-8 h-8 flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPrograms ?? 0) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Total program aktif</p>
                    </div>

                    <!-- Card 2 - Tugas/Postest -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl p-4 shadow-md border border-gray-100 dark:border-slate-800">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Postest</p>
                            <div class="shrink-0 w-8 h-8 flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalQuizzes ?? 0) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tugas yang dibuat</p>
                    </div>

                    <!-- Card 3 - Total Siswa -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl p-4 shadow-md border border-gray-100 dark:border-slate-800">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Total Siswa</p>
                            <div class="shrink-0 w-8 h-8 flex items-center justify-center bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalStudents ?? 0) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Siswa terdaftar</p>
                    </div>

                    <!-- Card 4 - Terkumpul -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl p-4 shadow-md border border-gray-100 dark:border-slate-800">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide leading-tight">Terkumpul</p>
                            <div class="shrink-0 w-8 h-8 flex items-center justify-center bg-gradient-to-br from-violet-400 to-violet-600 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalSubmissions ?? 0) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tugas dikumpulkan</p>
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
                <a href="{{ route('instructor.programs.create') }}" class="group relative block bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-md dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 overflow-hidden">

                    <!-- Hover Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-600/0 group-hover:from-blue-500/5 group-hover:to-blue-600/10 dark:group-hover:from-blue-500/10 dark:group-hover:to-blue-600/20 transition-all duration-300"></div>

                    <div class="relative z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h4 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Tambah Program</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Ajukan program baru anda</p>

                        <!-- Arrow Icon -->
                        <div class="absolute top-5 sm:top-6 right-5 sm:right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-gray-500 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-all duration-300">
                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Action Card 2 - Create Quiz -->
                <a href="{{ route('instructor.quizzes.create') }}" class="group relative block bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-md dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 overflow-hidden">

                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-600/0 group-hover:from-blue-500/5 group-hover:to-blue-600/10 dark:group-hover:from-blue-500/10 dark:group-hover:to-blue-600/20 transition-all duration-300"></div>

                    <div class="relative z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Buat Tugas/Quiz</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Buat tugas untuk program anda</p>

                        <div class="absolute top-5 sm:top-6 right-5 sm:right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-gray-500 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-all duration-300">
                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-500/5 dark:bg-blue-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                </a>

                <!-- Action Card 3 - Edit Profile -->
                <a href="{{ route('client.dashboard') }}" data-turbo="false" class="group relative block bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-md dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-900 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 overflow-hidden sm:col-span-2 lg:col-span-1">

                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-emerald-600/0 group-hover:from-emerald-500/5 group-hover:to-emerald-600/10 dark:group-hover:from-emerald-500/10 dark:group-hover:to-emerald-600/20 transition-all duration-300"></div>

                    <div class="relative z-10">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/25 group-hover:shadow-emerald-500/40 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-gray-900 dark:text-white text-base sm:text-lg font-bold mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Edit Profil</h4>
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

        <!-- Program Saya Section -->
        <div class="space-y-4 sm:space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">
                    Program Saya
                </h3>
                <a href="{{ route('instructor.programs.index') }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    Lihat Semua →
                </a>
            </div>

            @if(isset($programs) && $programs->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($programs as $program)
                        <div class="group bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl shadow-md dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">

                            <!-- Program Image -->
                            <div class="relative h-40 sm:h-48 overflow-hidden">
                                @if($program->image)
                                    <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-800 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white/40" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Category Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/90 dark:bg-slate-900/90 text-blue-700 dark:text-blue-400 backdrop-blur-sm shadow-sm">
                                        {{ $program->category ?? 'Umum' }}
                                    </span>
                                </div>

                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @php
                                        $statusType = $program->status ?? 'draft';
                                        $statusColor = match($statusType) {
                                            'approved', 'active' => 'bg-emerald-500',
                                            'pending' => 'bg-amber-500',
                                            'rejected' => 'bg-red-500',
                                            default => 'bg-gray-500',
                                        };
                                        $statusText = match($statusType) {
                                            'approved', 'active' => 'Diterima',
                                            'pending' => 'Menunggu',
                                            'rejected' => 'Ditolak',
                                            default => 'Draft',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold text-white {{ $statusColor }} shadow-sm">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 sm:p-5 flex flex-col flex-1">

                                <!-- Title -->
                                <h4 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $program->title }}
                                </h4>

                                <!-- Description -->
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2 flex-1">
                                    {{ $program->description ?? 'Tidak ada deskripsi' }}
                                </p>

                                <!-- Info Grid -->
                                <div class="grid grid-cols-2 gap-x-4 gap-y-3 mb-4">
                                    <!-- Jenis Pelaksanaan -->
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Pelaksanaan</p>
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-0.5">{{ $program->execution_type ?? 'Online' }}</p>
                                    </div>
                                    <!-- Harga -->
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Harga</p>
                                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mt-0.5">Rp {{ number_format($program->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <!-- Jadwal -->
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Mulai</p>
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-0.5">
                                            {{ $program->start_date ? \Carbon\Carbon::parse($program->start_date)->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Berakhir</p>
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-0.5">
                                            {{ $program->end_date ? \Carbon\Carbon::parse($program->end_date)->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="border-t border-gray-100 dark:border-slate-800 pt-3">
                                    <div class="flex items-center justify-between">
                                        <!-- Total Siswa -->
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">Total Siswa</p>
                                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($program->students_count ?? 0) }}</p>
                                            </div>
                                        </div>

                                        <!-- Detail Button -->
                                        <a href="{{ route('instructor.programs.show', $program->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                            Detail
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl p-8 sm:p-12 shadow-md dark:shadow-slate-900/50 border border-gray-100 dark:border-slate-800 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-400 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Belum Ada Program</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5 max-w-sm mx-auto">Anda belum memiliki program. Mulai buat program pertama Anda sekarang.</p>
                    <a href="{{ route('instructor.programs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-300 hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Program
                    </a>
                </div>
            @endif
        </div>

    </div>
@endsection
