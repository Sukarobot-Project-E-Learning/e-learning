@extends('client.main')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/elearning/client/css/home.css') }}">
@endsection

@section('body')

    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- SwiperJS Library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Popup Overlay -->
    <!-- <div id="popup" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden
                 w-[85%] max-w-[280px] sm:max-w-[320px] md:max-w-[360px] lg:max-w-[400px]
                 transform transition-all duration-300 scale-90"> -->
            <!-- Tombol X -->
            <!-- <button id="closePopup"
                class="absolute top-2 right-2 p-1.5 sm:p-2 rounded-full z-1 text-gray-700 font-bold text-lg leading-none hover:bg-gray-200">
                ✕
            </button> -->

            <!-- Poster -->
            <!-- <img src="{{ asset('assets/elearning/client/img/blog1.jpeg') }}" alt="Poster Event"
                class="w-full h-auto rounded-b-2xl object-cover scale-100" />
        </div>
    </div> -->

    <!-- Script -->

    <!-- Hero Section -->
    <section class="hero pt-32 pb-20 bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div
            class="absolute top-20 right-0 w-[500px] h-[500px] bg-orange-200/20 rounded-full blur-[100px] animate-pulse pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-200/20 rounded-full blur-[100px] animate-pulse pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-8 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative z-10">
            <!-- Left Content -->
            <div data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-6">
                    🚀 Platform Belajar Skill Masa Depan No.1 di Indonesia
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight text-gray-900 mb-6">
                    Belajar Skill <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Masa Depan</span>
                    dengan Mudah
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-xl">
                    Ratusan program untuk pelajar, mahasiswa, guru, dan umum. Tingkatkan kompetensi Anda di berbagai bidang
                    bersama para ahli. Dapatkan Sertifikat Resmi dari Lembaga Terakreditasi dan BNSP.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#category"
                        class="px-8 py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-blue-500/30 hover:scale-105 transition-all duration-300">
                        Jelajahi Program
                    </a>
                    <a href="#event"
                        class="px-8 py-4 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                        Lihat Kompetisi
                    </a>
                </div>
            </div>

            <!-- Right Image -->
            <div class="relative" data-aos="fade-left" data-aos-duration="1000">
                <div
                    class="relative z-10 bg-white p-4 rounded-3xl shadow-2xl transform rotate-2 hover:rotate-0 transition-all duration-500">
                    <img src="{{ asset('assets/elearning/client/img/hero.jpg') }}" alt="Learning Illustration"
                        class="w-full rounded-2xl object-cover h-[400px] lg:h-[500px]">

                    <!-- Floating Card -->
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl flex items-center gap-4 animate-bounce"
                        style="animation-duration: 3s;">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Dapatkan</p>
                            <p class="font-bold text-gray-900">Sertifikat Resmi</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Dots -->
                <div class="absolute -top-10 -right-10 grid grid-cols-5 gap-2 opacity-20">
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Proposition Section -->
    <section class="py-12 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8" data-aos="fade-up">
                <!-- Item 1 -->
                <div
                    class="flex flex-col items-center text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">Sertifikat Resmi</h3>
                    <p class="text-xs text-gray-500">Dari Lembaga Terakreditasi & BNSP</p>
                </div>

                <!-- Item 2 -->
                <div
                    class="flex flex-col items-center text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div
                        class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 mb-4 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">Instruktur Ahli</h3>
                    <p class="text-xs text-gray-500">Berpengalaman > 5 tahun</p>
                </div>

                <!-- Item 3 -->
                <div
                    class="flex flex-col items-center text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div
                        class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">Akses Fleksibel</h3>
                    <p class="text-xs text-gray-500">Belajar kapanpun dimanapun</p>
                </div>

                <!-- Item 4 -->
                <div
                    class="flex flex-col items-center text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div
                        class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">Materi Lengkap</h3>
                    <p class="text-xs text-gray-500">Video, E-book, & Kuis</p>
                </div>

                <!-- Item 5 -->
                <div
                    class="flex flex-col items-center text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div
                        class="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center text-pink-600 mb-4 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-1">Akses Multi-Device</h3>
                    <p class="text-xs text-gray-500">Smartphone, Tablet, Laptop</p>
                </div>
            </div>
        </div>
    </section>



    <!-- Category Navigator Section -->
    <section id="category" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-8 lg:px-12">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900">Jelajahi Kategori Program</h2>
                <p class="text-gray-600 mt-2">Pilih kategori yang sesuai dengan kebutuhan belajar Anda</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" data-aos="fade-up">
                <!-- Category 1 -->
                <a href="{{ url('/program?category=kursus') }}"
                    class="group category-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 text-center relative overflow-hidden h-48 flex flex-col items-center justify-center">
                    <div class="category-content transition-all duration-300 transform group-hover:-translate-y-2">
                        <div
                            class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-blue-600 transition-colors">Kursus</h3>
                    </div>
                    <div
                        class="category-desc absolute bottom-0 left-0 w-full p-4 bg-white/95 backdrop-blur-sm transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-sm text-gray-600">Pelajari skill baru dengan kurikulum terstruktur.</p>
                    </div>
                </a>

                <!-- Category 2 -->
                <a href="{{ url('/program?category=pelatihan') }}"
                    class="group category-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 text-center relative overflow-hidden h-48 flex flex-col items-center justify-center">
                    <div class="category-content transition-all duration-300 transform group-hover:-translate-y-2">
                        <div
                            class="w-16 h-16 mx-auto bg-orange-100 rounded-full flex items-center justify-center text-orange-500 mb-4 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-orange-500 transition-colors">Pelatihan
                        </h3>
                    </div>
                    <div
                        class="category-desc absolute bottom-0 left-0 w-full p-4 bg-white/95 backdrop-blur-sm transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-sm text-gray-600">Tingkatkan keahlian praktis Anda.</p>
                    </div>
                </a>

                <!-- Category 3 -->
                <a href="{{ url('/program?category=sertifikasi') }}"
                    class="group category-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 text-center relative overflow-hidden h-48 flex flex-col items-center justify-center">
                    <div class="category-content transition-all duration-300 transform group-hover:-translate-y-2">
                        <div
                            class="w-16 h-16 mx-auto bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-purple-600 transition-colors">
                            Sertifikasi</h3>
                    </div>
                    <div
                        class="category-desc absolute bottom-0 left-0 w-full p-4 bg-white/95 backdrop-blur-sm transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-sm text-gray-600">Dapatkan sertifikasi resmi dari BNSP.</p>
                    </div>
                </a>

                <!-- Category 4 -->
                <a href="{{ url('/program?category=outing-class') }}"
                    class="group category-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 text-center relative overflow-hidden h-48 flex flex-col items-center justify-center">
                    <div class="category-content transition-all duration-300 transform group-hover:-translate-y-2">
                        <div
                            class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-4 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-green-600 transition-colors">Outing
                            Class</h3>
                    </div>
                    <div
                        class="category-desc absolute bottom-0 left-0 w-full p-4 bg-white/95 backdrop-blur-sm transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-sm text-gray-600">Belajar seru di luar ruangan.</p>
                    </div>
                </a>

                <!-- Category 5 -->
                <a href="{{ url('/program?category=outboard') }}"
                    class="group category-card bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 text-center relative overflow-hidden h-48 flex flex-col items-center justify-center">
                    <div class="category-content transition-all duration-300 transform group-hover:-translate-y-2">
                        <div
                            class="w-16 h-16 mx-auto bg-pink-100 rounded-full flex items-center justify-center text-pink-600 mb-4 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-pink-600 transition-colors">Outboard
                        </h3>
                    </div>
                    <div
                        class="category-desc absolute bottom-0 left-0 w-full p-4 bg-white/95 backdrop-blur-sm transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-sm text-gray-600">Eksplorasi teknologi tanpa batas.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Popular Programs Section -->
    <section id="program" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Program Terpopuler</h2>
                    <p class="text-gray-600 mt-2">Kelas pilihan yang paling banyak diminati siswa</p>
                </div>
                <!-- <a href="{{ url('/program') }}"
                    class="text-blue-600 font-semibold hover:text-blue-700 flex items-center gap-2">
                    Lihat Semua Program
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                        </path>
                    </svg>
                </a> -->
            </div>

            <div class="relative">
                <div class="swiper programSwiper pb-12 px-4">
                    <div class="swiper-wrapper">
                        @forelse ($popularPrograms as $program)
                            <!-- Program Card: {{ $program->program }} -->
                            <div class="swiper-slide h-auto">
                                <a href="{{ route('client.program.detail', $program->slug) }}"
                                    class="block program-card bg-white rounded-2xl shadow-sm transition-all duration-300 border border-gray-100 overflow-hidden group h-full flex flex-col">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $programImageUrl = ($program->image && str_starts_with($program->image, 'images/'))
                                                ? asset($program->image) 
                                                : ($program->image ? asset('storage/' . $program->image) : asset('assets/elearning/client/img/home1.jpeg'));
                                            
                                            $now = \Carbon\Carbon::now();
                                            $startDate = \Carbon\Carbon::parse($program->start_date);
                                            $endDate = \Carbon\Carbon::parse($program->end_date);
                                            $isRunning = $now->between($startDate, $endDate);
                                            $isFinished = $now->gt($endDate);
                                        @endphp
                                        <img src="{{ $programImageUrl }}"
                                            alt="{{ $program->program }}"
                                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                                            
                                        @if($program->available_slots == 0)
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-20">
                                                <span class="bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-lg">
                                                    Kuota Habis
                                                </span>
                                            </div>
                                        @elseif($isFinished)
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-20">
                                                <span class="bg-gray-600 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-lg">
                                                    Selesai
                                                </span>
                                            </div>
                                        @elseif($isRunning)
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-20">
                                                <span class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-lg">
                                                    Sedang Berjalan
                                                </span>
                                            </div>
                                        @endif

                                        <div
                                            class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-blue-600 z-20">
                                            {{ ucfirst($program->category) }}
                                        </div>
                                        <!-- Hover Description Overlay -->
                                        <div
                                            class="absolute inset-0 bg-black/80 p-6 translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex items-center justify-center text-center z-10">
                                            <p class="text-white text-sm leading-relaxed line-clamp-4">
                                                {{ $program->description }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="p-5 flex-grow flex flex-col">
                                        <div class="flex items-center gap-1 mb-2">
                                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span
                                                class="text-sm font-bold text-gray-700">{{ number_format($program->rating, 1) }}</span>
                                            <span class="text-xs text-gray-500">({{ $program->total_reviews }} Review)</span>
                                        </div>
                                        <h3
                                            class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            {{ $program->program }}
                                        </h3>
                                        <div class="text-sm text-gray-500 mb-4 space-y-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                                <span>Oleh: {{ $program->instructor_name ?? 'Sukarobot' }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                <span>Kuota: {{ $program->available_slots }} Slot</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <span>Pelaksanaan: {{ ucfirst($program->type) }}</span>
                                            </div>
                                        </div>
                                        <div
                                            class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between relative z-20 bg-white">
                                            <span class="text-lg font-bold text-orange-500">Rp
                                                {{ number_format($program->price, 0, ',', '.') }}</span>
                                            <div
                                                class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <!-- No Programs -->
                            <div class="swiper-slide h-auto">
                                <div class="text-center py-12 text-gray-500">
                                    Belum ada program tersedia
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button
                    class="program-prev absolute top-1/2 -left-4 lg:-left-6 z-30 w-10 h-10 lg:w-12 lg:h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:bg-blue-600 hover:text-white transition-all duration-300 focus:outline-none transform -translate-y-1/2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button
                    class="program-next absolute top-1/2 -right-4 lg:-right-6 z-30 w-10 h-10 lg:w-12 lg:h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:bg-blue-600 hover:text-white transition-all duration-300 focus:outline-none transform -translate-y-1/2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-blue-800 relative overflow-hidden">
        <!-- Decorative Circles -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
        </div>
        <div
            class="absolute bottom-0 right-0 w-64 h-64 bg-orange-500/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2">
        </div>

        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                Siap Meningkatkan Skill Anda?
            </h2>
            <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan siswa lainnya dan mulai perjalanan belajar Anda hari ini.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <!-- <a href="#alumni"
                    class="px-8 py-4 bg-orange-500 text-white font-bold rounded-xl shadow-lg hover:shadow-orange-500/30 hover:scale-105 transition-all duration-300">
                    Testimoni Alumni
                </a> -->
                <a href="#instructor"
                    class="px-8 py-4 bg-white/10 text-white backdrop-blur-sm border border-white/20 font-bold rounded-xl hover:bg-white/20 hover:scale-105 transition-all duration-300">
                    Instruktur Kami
                </a>
            </div>
        </div>
    </section>


    <!-- Promo/Voucher Section -->
    <!-- <section class="py-16 bg-orange-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900">Voucher Diskon Hari Ini</h2>
                <p class="text-gray-600 mt-2">Gunakan kode voucher di bawah ini untuk mendapatkan potongan harga spesial</p>
            </div>
    
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> -->
                <!-- Voucher 1 -->
                <!-- <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">
                        Terbatas
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-xl">
                            %
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Diskon 50%</h3>
                            <p class="text-xs text-gray-500">Untuk pendaftar pertama</p>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 rounded-xl p-3 flex justify-between items-center border border-dashed border-gray-300 mb-4">
                        <span class="font-mono font-bold text-gray-400">******</span>
                        <span class="text-xs text-gray-400 italic">Klaim untuk lihat</span>
                    </div>
                    <button
                        class="w-full py-2 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors">
                        Klaim Sekarang
                    </button>
                </div> -->

                <!-- Voucher 2 -->
                <!-- <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-blue-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">
                        Spesial
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl">
                            %
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Potongan 20K</h3>
                            <p class="text-xs text-gray-500">Min. pembelian 100K</p>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 rounded-xl p-3 flex justify-between items-center border border-dashed border-gray-300 mb-4">
                        <span class="font-mono font-bold text-gray-400">******</span>
                        <span class="text-xs text-gray-400 italic">Klaim untuk lihat</span>
                    </div>
                    <button
                        class="w-full py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
                        Klaim Sekarang
                    </button>
                </div> -->

                <!-- Voucher 3 -->
                <!-- <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-purple-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-xl">
                            %
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Bundle Hemat</h3>
                            <p class="text-xs text-gray-500">Beli 2 kursus sekaligus</p>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 rounded-xl p-3 flex justify-between items-center border border-dashed border-gray-300 mb-4">
                        <span class="font-mono font-bold text-gray-400">******</span>
                        <span class="text-xs text-gray-400 italic">Klaim untuk lihat</span>
                    </div>
                    <button
                        class="w-full py-2 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors">
                        Klaim Sekarang
                    </button>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Competition Section -->
    <section id="event" class="py-20 bg-white overflow-hidden">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-gray-900">Kompetisi Robotik</h2>
            <p class="text-gray-600 mt-2">Tunjukkan bakatmu dan raih prestasi di tingkat nasional</p>
        </div>

        <div class="max-w-7xl mx-auto px-8 lg:px-12 relative">
            <div class="swiper mySwiper pb-12" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper">
                    <!-- SRC Card -->
                    <div class="swiper-slide">
                        <div
                            class="bg-white rounded-3xl border border-gray-100 overflow-hidden group h-full flex flex-col lg:flex-row">
                            <div class="relative lg:w-1/2 h-64 lg:h-auto overflow-hidden">
                                <img src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}" alt="SRC"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent lg:bg-gradient-to-r">
                                </div>
                            </div>
                            <div class="p-8 lg:w-1/2 flex flex-col justify-center">
                                <h3 class="text-3xl font-bold text-gray-900 mb-2">SRC (Sukabumi Robotic Competition)</h3>
                                <p class="text-blue-600 font-semibold mb-6">Tingkat Nasional</p>
                                <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                                    SRC merupakan kompetisi tahunan yang luar biasa. Karena kegiatan ini bukan hanya tentang
                                    teknologi dan robot, tetapi juga tentang kolaborasi, inovasi dan semangat untuk belajar.
                                    Selain itu hadiah menarik dan penghargaan yang menanti untuk para pemenang!
                                </p>
                                <div class="flex items-center">
                                    <a href="https://src.sukarobot.com/"
                                        class="px-8 py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors shadow-lg hover:shadow-orange-500/30">
                                        Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BRC Card -->
                    <div class="swiper-slide">
                        <div
                            class="bg-white rounded-3xl border border-gray-100 overflow-hidden group h-full flex flex-col lg:flex-row">
                            <div class="relative lg:w-1/2 h-64 lg:h-auto overflow-hidden">
                                <img src="{{ asset('assets/elearning/client/img/banner.jpeg') }}" alt="BRC"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent lg:bg-gradient-to-r">
                                </div>
                            </div>
                            <div class="p-8 lg:w-1/2 flex flex-col justify-center">
                                <h3 class="text-3xl font-bold text-gray-900 mb-2">BRC (Botani Robotic Competition)</h3>
                                <p class="text-blue-600 font-semibold mb-6">Tingkat Nasional</p>
                                <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                                    BRC merupakan kompetisi tahunan yang luar biasa. Karena kegiatan ini bukan hanya tentang
                                    teknologi dan robot, tetapi juga tentang kolaborasi, inovasi dan semangat untuk belajar.
                                    Selain itu hadiah menarik dan penghargaan yang menanti untuk para pemenang!
                                </p>                                
                                    <div class="flex items-center">
                                        <a href="https://brc.sukarobot.com/"
                                            class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg hover:shadow-blue-600/30">
                                            Daftar Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                </div>
            </div>

            <!-- Custom Navigation -->
            <button id="prevBtn"
                class="absolute top-1/2 left-0 lg:left-4 z-10 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:bg-blue-600 hover:text-white transition-all duration-300 focus:outline-none transform -translate-y-1/2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button id="nextBtn"
                class="absolute top-1/2 right-0 lg:right-4 z-10 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:bg-blue-600 hover:text-white transition-all duration-300 focus:outline-none transform -translate-y-1/2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </section>


    <!-- Instructors Section -->
    <section id="instructor" class="py-20 bg-gray-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-14" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-900">Instruktur & Pengajar Terbaik</h2>
                <p class="text-gray-600 mt-2 max-w-2xl mx-auto">
                    Belajar langsung dari para pendidik profesional yang berpengalaman.
                </p>
            </div>

            <div class="relative">
                <div class="swiper instructorSwiper pb-12 px-4">
                    <div class="swiper-wrapper">
                        @foreach ($instructors as $instructor)
                            <!-- Instructor {{ $instructor->nama }} -->
                            <div class="swiper-slide h-auto">
                                <div
                                    class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center h-full flex flex-col">
                                    <div class="relative w-32 h-32 mx-auto mb-6">
                                        <div
                                            class="absolute inset-0 bg-blue-100 rounded-full scale-110 group-hover:scale-125 transition-transform  duration-300">
                                        </div>
                                        <img src="{{ $instructor->foto }}"
                                            alt="{{ $instructor->nama }}"
                                            class="relative w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">
                                        {{ $instructor->nama }}
                                    </h3>
                                    <p
                                        class="text-blue-600 font-medium text-sm mb-3">
                                        {{ $instructor->jabatan }}
                                    </p>
                                    <p class="text-gray-500 text-sm leading-relaxed flex-grow line-clamp-3">
                                        {{ $instructor->deskripsi }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button
                    class="instr-prev absolute top-1/2 -left-4 lg:-left-6 z-30 w-10 h-10 lg:w-12 lg:h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:bg-blue-600 hover:text-white transition-all duration-300 focus:outline-none transform -translate-y-1/2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button
                    class="instr-next absolute top-1/2 -right-4 lg:-right-6 z-30 w-10 h-10 lg:w-12 lg:h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:bg-blue-600 hover:text-white transition-all duration-300 focus:outline-none transform -translate-y-1/2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ url('/instruktur') }}"
                    class="inline-flex items-center gap-2 text-blue-600 font-bold hover:text-blue-700 transition-colors">
                    Lihat Semua Instruktur
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Partner Section -->
    <section id="partner" class="py-16 bg-gray-50 border-y border-gray-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 mb-10 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Bekerjasama Dengan</h2>
        </div>

        <div class="partner-wrapper relative">
            <!-- Track 1 -->
            <div class="partner-track flex items-center track-left mb-8">
                <img src="/assets/elearning/client/img/nusaputra.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Universitas Nusa Putra">
                <img src="/assets/elearning/client/img/almatuq.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Madrasah Al-Matuq">
                <img src="/assets/elearning/client/img/smpkhalifah.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SMP Khalifah">
                <img src="/assets/elearning/client/img/attartil.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SDIT Qu Attartil">
                <img src="/assets/elearning/client/img/attakwin.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SDIT At Takwin">
                <img src="/assets/elearning/client/img/islamfathia.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SD & SMP Islam Fathia">
                <img src="/assets/elearning/client/img/bpkpenabur.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="TKK, SDK, SMK BPK Penabur Sukabumi">
                <img src="/assets/elearning/client/img/mardiwaluya.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SD Mardi Waluya Sukabumi">
                <img src="/assets/elearning/client/img/none.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SD Aisyiyah Sukabumi">
                <img src="/assets/elearning/client/img/sekolahalamindonesia.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Sekolah Alam Indonesia Sukabumi">
                <img src="/assets/elearning/client/img/yuwabhakti.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SD Yuwati Bhakti Sukabumi">
                <img src="/assets/elearning/client/img/sitadzkia.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SDIT & SMPIT Adzkia Sukabumi">
                <img src="/assets/elearning/client/img/alumanaa.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SMP & SMA Al-Umanaa Sukabumi">
                <img src="/assets/elearning/client/img/sditinsani.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SDIT Insani Sukabumi">
                <img src="/assets/elearning/client/img/none.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SDIT Andalusia Sukabumi">
                <img src="/assets/elearning/client/img/hayatanthayyibah.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SMPIT Hayatan Thayyibah Sukabumi">
                <img src="/assets/elearning/client/img/none.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="SMK Ibadurrahman Sukabumi">
            </div>

            <!-- Track 2 (Reverse) -->
            <div class="partner-track flex items-center track-right">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/21/Lambang_Kota_Sukabumi_Vektor.svg?uselang=id"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Kota Sukabumi">
                <img src="https://upload.wikimedia.org/wikipedia/commons/archive/6/6a/20170428132038%21Lambang_Kab_Sukabumi.svg?uselang=id"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Kabupaten Sukabumi">
                <img src="/assets/elearning/client/img/dispusipda.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="DISPUSIPDA Kota Sukabumi">
                <img src="/assets/elearning/client/img/sdmtik.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="LSP SDMTIK">
                <img src="/assets/elearning/client/img/perpuscisarua.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Perpustakaan Kelurahan Cisarua">
                <img src="/assets/elearning/client/img/arei.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Asosiasi Robotik Edukasi Indonesia">
                <img src="/assets/elearning/client/img/dinaspendidikan.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Dinas Pendidikan Kota Sukabumi">
                <img src="/assets/elearning/client/img/lspteknologidigital.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="LSP Teknologi Digital">
                <img src="/assets/elearning/client/img/lspmsdmasphri.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="LSP MSDM Asphri">
                <img src="/assets/elearning/client/img/lsppelatinas.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="LSP Pelatinas">                
                <img src="/assets/elearning/client/img/PRSI.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Persatuan Robotika Seluruh Indonesia">
                <img src="/assets/elearning/client/img/botanisquare.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="Botani Mal Square">
                <img src="/assets/elearning/client/img/PTSTG.png"
                    class="partner-logo h-8 md:h-10 mx-8 opacity-50 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0"
                    alt="PT Suka Teknologi Global">
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div data-aos="fade-right">
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Punya Pertanyaan? <br> <span
                            class="text-blue-600">Diskusikan dengan Kami</span></h2>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                        Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan tentang program, pendaftaran, atau
                        kerjasama. Tim kami siap membantu Anda.
                    </p>

                    <div class="mt-5 flex justify-center md:justify-end">
                        <img src="{{ asset('assets/elearning/client/img/eventilustrator.jpeg') }}" alt="Ilustrasi Tanya"
                            class="w-56 sm:w-64 md:w-72 rounded-xl select-none" />
                    </div>
                </div>

                <!-- Right Form -->
                <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100" data-aos="fade-left">
                    {{-- @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif --}}

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form id="contactForm" action="{{ route('client.contact.send') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                                placeholder="Masukkan nama Anda">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                                    placeholder="alamat@email.com">
                            </div> --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP</label>
                                <input type="tel" name="phone" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                                    placeholder="0812xxxx">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pertanyaan</label>
                            <textarea rows="4" name="message" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                                placeholder="Tuliskan pertanyaan Anda disini..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-blue-600/30 transition-all duration-300">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check session success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Berhasil mengirim pertanyaan',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            // Handle form submission
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    @if(!auth()->check())
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Login Diperlukan',
                            text: 'Silahkan login terlebih dahulu untuk mengajukan pertanyaan',
                            confirmButtonText: 'Login Sekarang',
                            showCancelButton: true,
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            //if (result.isConfirmed) {
                                //window.location.href = "{{ route('login') }}";
                            //}
                        });
                    @endif
                });
            }
        });
    </script>

    <script src="{{ asset('assets/elearning/client/js/home.js') }}"></script>

@endsection