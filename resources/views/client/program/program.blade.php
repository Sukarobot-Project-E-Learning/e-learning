@extends('client.main')
@section('body')

    @php
    // Get current active category
    $currentCategory = $activeCategory ?? 'all';
    
    // Category display names
    $categoryNames = [
        'all' => 'Semua Kelas',
        'kursus' => 'Kursus',
        'pelatihan' => 'Pelatihan',
        'sertifikasi' => 'Sertifikasi',
        'outing-class' => 'Outing Class',
        'outboard' => 'Outboard'
    ];

    // Hero Content Data
    $heroContent = [
        'all' => [
            'title' => 'Kelas di E-Learning tersedia dari level <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Dasar hingga Profesional</span>',
            'description' => 'Tingkatkan kompetensi Anda sesuai kebutuhan industri terkini dengan kurikulum yang terstruktur dan mentor berpengalaman.'
        ],
        'kursus' => [
            'title' => 'Tingkatkan keahlianmu dengan berbagai <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Kursus Intensif</span>',
            'description' => 'Materi dirancang oleh ahli untuk pemula hingga profesional.'
        ],
        'pelatihan' => [
            'title' => 'Ikuti pelatihan praktis untuk <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Mengasah Keterampilan</span>',
            'description' => 'Tingkatkan soft skill dan hard skill Anda untuk dunia kerja.'
        ],
        'sertifikasi' => [
            'title' => 'Dapatkan pengakuan profesional melalui <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Program Sertifikasi</span>',
            'description' => 'Validasi keahlian Anda dengan sertifikat berstandar industri nasional dan internasional.'
        ],
        'outing-class' => [
            'title' => 'Belajar di luar kelas dengan <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Pengalaman Langsung</span>',
            'description' => 'Kegiatan edukatif yang menyenangkan dan interaktif untuk semua usia.'
        ],
        'outboard' => [
            'title' => 'Bangun karakter dan kerjasama tim melalui <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Program Outboard</span>',
            'description' => 'Kegiatan luar ruangan yang menantang untuk meningkatkan kepemimpinan dan soliditas tim.'
        ]
    ];
    @endphp

    <!-- Top Navigation Menu -->
    <div class="bg-white border-t border-gray-200 sticky top-0 z-30 mt-24">
        <div class="container mx-auto px-6 relative group">
            <!-- Left Arrow -->
            <button id="nav-scroll-left"
                class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 p-2 rounded-full shadow-md hover:bg-white text-gray-600 hover:text-blue-600 transition-all hidden border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="flex justify-start md:justify-center items-center gap-8 md:gap-12 overflow-x-auto py-4 scrollbar-hide scroll-smooth px-8" id="program-nav">
                <button type="button" data-filter="all" 
                   class="nav-item text-sm {{ $currentCategory === 'all' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-gray-500 hover:text-gray-900' }} pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">
                    Semua Kelas
                </button>
                <button type="button" data-filter="kursus" 
                   class="nav-item text-sm {{ $currentCategory === 'kursus' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-gray-500 hover:text-gray-900' }} pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">
                    Kursus
                </button>
                <button type="button" data-filter="pelatihan" 
                   class="nav-item text-sm {{ $currentCategory === 'pelatihan' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-gray-500 hover:text-gray-900' }} pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">
                    Pelatihan
                </button>
                <button type="button" data-filter="sertifikasi" 
                   class="nav-item text-sm {{ $currentCategory === 'sertifikasi' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-gray-500 hover:text-gray-900' }} pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">
                    Sertifikasi
                </button>
                <button type="button" data-filter="outing-class" 
                   class="nav-item text-sm {{ $currentCategory === 'outing-class' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-gray-500 hover:text-gray-900' }} pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">
                    Outing Class
                </button>
                <button type="button" data-filter="outboard" 
                   class="nav-item text-sm {{ $currentCategory === 'outboard' ? 'font-bold text-blue-600 border-b-2 border-blue-600' : 'font-medium text-gray-500 hover:text-gray-900' }} pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">
                    Outboard
                </button>
            </div>

            <!-- Right Arrow -->
            <button id="nav-scroll-right"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 p-2 rounded-full shadow-md hover:bg-white text-gray-600 hover:text-blue-600 transition-all hidden border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="py-8 bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-hidden text-center">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-orange-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl mx-auto px-6">
            <h1 id="hero-title" class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-6 leading-tight">
                {!! $heroContent[$currentCategory]['title'] ?? $heroContent['all']['title'] !!}
            </h1>
            <p id="hero-description" class="text-gray-600 text-sm mb-8 max-w-2xl mx-auto">
                {{ $heroContent[$currentCategory]['description'] ?? $heroContent['all']['description'] }}
            </p>
            <div class="w-24 h-1.5 bg-gradient-to-r from-blue-600 to-orange-500 mx-auto rounded-full"></div>
        </div>
    </section>

    <!-- Filter & Sort Section -->
    <div class="container mx-auto px-6 mb-8 mt-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 border-b border-gray-100 pb-6">
            <!-- Left side -->
            <div class="text-gray-600 font-medium jumlah-kelas flex items-center gap-2">
                <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                Menampilkan {{ $currentCategory === 'all' ? 'semua program' : 'program ' . strtolower($categoryNames[$currentCategory] ?? $currentCategory) }}
            </div>

            <!-- Right side: Sort Options & Search -->
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" id="program-search-input" placeholder="Cari program..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    <span class="text-gray-600 text-sm font-medium whitespace-nowrap">Urutkan:</span>
                    <div class="relative w-full md:w-auto">
                        <select id="sort-select" class="w-full md:w-auto appearance-none border border-gray-200 rounded-xl px-4 py-2.5 pr-8 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white cursor-pointer hover:border-blue-400 transition shadow-sm font-medium text-gray-700">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="available">Tersedia</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Card Grid -->
    <div class="container mx-auto px-6 pb-20">
        <div class="kelas-container grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($programs as $program)
                <!-- Program Card: {{ $program->program }} -->
                @php
                    $programImageUrl = ($program->image && str_starts_with($program->image, 'images/'))
                        ? asset($program->image) 
                        : ($program->image ? asset('storage/' . $program->image) : 'https://picsum.photos/400/250?random=' . $program->id);
                    $now = \Carbon\Carbon::now();
                    $startDate = \Carbon\Carbon::parse($program->start_date);
                    $endDate = \Carbon\Carbon::parse($program->end_date);
                    $isRunning = $now->between($startDate, $endDate);
                    $isFinished = $now->gt($endDate);
                @endphp
                <a href="{{ route('client.program.detail', $program->slug) }}" class="block group kelas-card"
                    data-category="{{ $program->category }}" data-date="{{ $program->created_at }}"
                    data-slots="{{ $program->available_slots }}"
                    data-is-running="{{ $isRunning ? 'true' : 'false' }}"
                    data-is-finished="{{ $isFinished ? 'true' : 'false' }}">
                    <article
                        class="h-full flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 {{ $program->available_slots == 0 ? 'grayscale opacity-80 hover:opacity-100' : '' }}">
                        <div class="relative overflow-hidden">
                            <img src="{{ $programImageUrl }}"
                                class="w-full h-52 object-cover transform group-hover:scale-105 transition duration-500"
                                alt="{{ $program->program }}">
                            @if($program->available_slots == 0)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-20">
                                    <span
                                        class="bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-lg">Kuota
                                        Habis</span>
                                </div>
                            @elseif($isFinished)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-20">
                                    <span
                                        class="bg-gray-600 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-lg">Selesai</span>
                                </div>
                            @elseif($isRunning)
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-20">
                                    <span
                                        class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-lg">Sedang Berjalan</span>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-blue-600 z-20 shadow-sm">
                                {{ ucfirst($program->category) }}
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-1 mb-3">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-sm font-bold text-gray-700">{{ number_format($program->rating, 1) }}</span>
                                <span class="text-xs text-gray-500">({{ $program->total_reviews }} Review)</span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                {{ $program->program }}</h3>
                            <p class="text-gray-600 text-[10px] mb-5 line-clamp-2 leading-relaxed">{{ $program->description }}</p>
                            
                            <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <span>{{ ucfirst($program->type) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 {{ $program->available_slots == 0 ? 'text-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="{{ $program->available_slots == 0 ? 'text-red-500 font-bold' : '' }}">{{ $program->available_slots }} Slot</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-5 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $program->instructor_avatar ? asset('storage/' . $program->instructor_avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($program->instructor_name ?? 'Instructor') . '&background=random' }}" class="w-9 h-9 rounded-full border-2 border-white shadow-sm" alt="Instructor">
                                    <div class="text-xs">
                                        <p class="font-bold text-gray-900">{{ $program->instructor_name ?? 'Sukarobot' }}</p>
                                        <p class="text-gray-500">{{ $program->instructor_job ?? 'Mentor' }}</p>
                                    </div>
                                </div>
                                @if($program->price > 0)
                                <span class="text-lg font-bold text-orange-500">Rp {{ number_format($program->price, 0, ',', '.') }}</span>
                                @else
                                <span class="text-lg font-bold text-green-500">Gratis</span>
                                @endif
                            </div>
                        </div>
                    </article>
                </a>
            @empty
                <!-- No programs found -->
                <!-- No programs found -->
                <div class="col-span-full w-full flex flex-col items-center justify-center py-20 text-center">
                    <div class="relative w-48 h-48 mb-6 animate-bounce" style="animation-duration: 3s;">
                        <!-- Animated Illustration (Robotic/Tech Theme) -->
                        <svg class="w-full h-full drop-shadow-xl" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Floating Elements -->
                            <circle cx="100" cy="100" r="80" class="fill-blue-50 animate-pulse" style="animation-duration: 4s;"/>
                            
                            <!-- Robot Head / Search Icon Composite -->
                            <path d="M60 90C60 67.9086 77.9086 50 100 50C122.091 50 140 67.9086 140 90V130C140 141.046 131.046 150 120 150H80C68.9543 150 60 141.046 60 130V90Z" class="fill-white"/>
                            <rect x="75" y="80" width="15" height="15" rx="7.5" class="fill-blue-200"/>
                            <rect x="110" y="80" width="15" height="15" rx="7.5" class="fill-blue-200"/>
                            <path d="M85 115C85 115 90 122 100 122C110 122 115 115 115 115" stroke="#93C5FD" stroke-width="4" stroke-linecap="round"/>
                            
                            <!-- Gear Icon (Rotating) -->
                            <g class="origin-center animate-[spin_10s_linear_infinite]" style="transform-box: fill-box;">
                                <path d="M160 50L170 45L165 35L155 40L160 50Z" class="fill-orange-400"/>
                                <circle cx="160" cy="45" r="3" class="fill-white"/>
                            </g>

                            <!-- Search Magnifier -->
                            <path d="M130 130L150 150" stroke="#F97316" stroke-width="8" stroke-linecap="round"/>
                            <circle cx="125" cy="125" r="15" class="stroke-orange-500 fill-white" stroke-width="4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Program</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-8 text-lg">
                        Saat ini kami sedang menyiapkan program terbaik untuk kategori ini. <br>Silakan cek kategori lainnya!
                    </p>
                </div>
            @endforelse
        </div>
    </div>

@endsection

<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program/program.css') }}">
<script src="{{ asset('assets/elearning/client/js/program/program.js') }}"></script>
<script>
    // Inline script removed - logic moved to program.js
</script>