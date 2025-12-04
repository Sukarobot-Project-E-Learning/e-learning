@extends('client.main')
@section('body')

    <!-- Top Navigation Menu -->
    <div class="bg-white border-t border-gray-200 sticky top-0 z-30 mt-24">
        <div class="container mx-auto px-6 relative group">
            <!-- Left Arrow -->
            <button id="nav-scroll-left" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 p-2 rounded-full shadow-md hover:bg-white text-gray-600 hover:text-blue-600 transition-all hidden border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="flex justify-start md:justify-center items-center gap-8 md:gap-12 overflow-x-auto py-4 scrollbar-hide scroll-smooth px-8" id="program-nav">
                <a href="{{ url('/program') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">Semua Kelas</a>
                <a href="{{ url('/program/kursus') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">Kursus</a>
                <a href="{{ url('/program/pelatihan') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">Pelatihan</a>
                <a href="{{ url('/program/sertifikasi') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">Sertifikasi</a>
                <a href="{{ url('/program/outing-class') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">Outing Class</a>
                <a href="{{ url('/program/outboard') }}" class="nav-item text-sm font-bold text-blue-600 border-b-2 border-blue-600 pb-1 whitespace-nowrap transition-colors cursor-pointer text-lg flex-shrink-0">Outboard</a>
            </div>

            <!-- Right Arrow -->
            <button id="nav-scroll-right" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 p-2 rounded-full shadow-md hover:bg-white text-gray-600 hover:text-blue-600 transition-all hidden border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-hidden text-center">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-orange-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-6">
            <h1 id="hero-title" class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                Bangun karakter dan kerjasama tim melalui <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Program Outboard</span>
            </h1>
            <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
                Kegiatan luar ruangan yang menantang untuk meningkatkan kepemimpinan dan soliditas tim.
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
                Menampilkan program Outboard
            </div>

            <!-- Right side: Sort Options -->
            <div class="flex items-center gap-3">
                <span class="text-gray-600 text-sm font-medium">Urutkan:</span>
                <div class="relative">
                    <select id="sort-select" class="appearance-none border border-gray-200 rounded-xl px-4 py-2.5 pr-8 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white cursor-pointer hover:border-blue-400 transition shadow-sm font-medium text-gray-700">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="available">Tersedia</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Card Grid -->
    <div class="container mx-auto px-6 pb-20">
        <div class="kelas-container grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            
             <!-- Card 5 -->
            <a href="{{ url('program/detail-program') }}" class="block group kelas-card" data-category="outboard" data-date="2023-08-10" data-slots="28">
                <article class="h-full flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="relative overflow-hidden">
                        <img src="https://picsum.photos/400/250?random=5" class="w-full h-52 object-cover transform group-hover:scale-105 transition duration-500" alt="Course Image">
                         <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-pink-600 z-20 shadow-sm">
                            Outboard
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="text-sm font-bold text-gray-700">4.9</span>
                            <span class="text-xs text-gray-500">(90 Review)</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">Web Programming</h3>
                        
                        <p class="text-gray-600 text-sm mb-5 line-clamp-2 leading-relaxed">Pelajari dasar hingga mahir pemrograman web dengan kurikulum standar industri.</p>

                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span>Video</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>28 Slot</span>
                            </div>
                        </div>

                        <div class="mt-auto pt-5 border-t border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" class="w-9 h-9 rounded-full border-2 border-white shadow-sm" alt="Instructor">
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900">Aisya Savitri</p>
                                    <p class="text-gray-500">Mentor</p>
                                </div>
                            </div>
                            <span class="text-green-600 font-bold text-sm bg-green-50 px-3 py-1 rounded-full">Gratis</span>
                        </div>
                    </div>
                </article>
            </a>

        </div>
    </div>

@endsection

<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program.css') }}">
<script src="{{ asset('assets/elearning/client/js/program.js') }}"></script>
<script>
    window.activeCategory = "outboard";
</script>
