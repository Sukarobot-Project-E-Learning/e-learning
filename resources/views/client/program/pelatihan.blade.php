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
                <a href="{{ url('/program') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-xl flex-shrink-0">Semua Kelas</a>
                <a href="{{ url('/program/kursus') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-xl flex-shrink-0">Kursus</a>
                <a href="{{ url('/program/pelatihan') }}" class="nav-item text-sm font-semibold text-blue-600 border-b-2 border-blue-600 pb-1 whitespace-nowrap transition-colors cursor-pointer text-xl flex-shrink-0">Pelatihan</a>
                <a href="{{ url('/program/sertifikasi') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-xl flex-shrink-0">Sertifikasi</a>
                <a href="{{ url('/program/outing-class') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-xl flex-shrink-0">Outing Class</a>
                <a href="{{ url('/program/outboard') }}" class="nav-item text-sm font-medium text-gray-500 hover:text-gray-900 pb-1 whitespace-nowrap transition-colors cursor-pointer text-xl flex-shrink-0">Outboard</a>
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
    <div class="bg-white py-10 text-center">
        <h1 id="hero-title" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Ikuti pelatihan praktis untuk <br> mengasah keterampilan teknis dan soft skill.</h1>
        <div class="w-24 h-1 bg-blue-500 mx-auto rounded"></div>
    </div>

    <!-- Filter & Sort Section -->
    <div class="container mx-auto px-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 border-b border-gray-200 pb-4">
            <!-- Left side (could be empty or total count) -->
            <div class="text-gray-600 font-medium jumlah-kelas">
                Menampilkan semua program
            </div>

            <!-- Right side: Sort Options -->
            <div class="flex items-center gap-3">
                <span class="text-gray-600 text-sm font-medium">Urutkan:</span>
                <select id="sort-select" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white cursor-pointer hover:border-blue-400 transition">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="available">Tersedia</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Main Content: Card Grid -->
    <div class="container mx-auto px-6 pb-16">
        <div class="kelas-container grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            
            <!-- Card 2 (Sold Out) -->
            <a href="{{ url('program/detail-program') }}" class="block group kelas-card" data-category="pelatihan" data-date="2023-09-15" data-slots="0">
                <article class="h-full flex flex-col bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 grayscale hover:grayscale-0">
                    <div class="relative overflow-hidden">
                        <img src="https://picsum.photos/400/250?random=2" class="w-full h-48 object-cover transform group-hover:scale-105 transition duration-500" alt="Course Image">
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="bg-black/80 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Kuota Habis</span>
                        </div>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition">Mental Tangguh di Dunia Kerja</h3>
                        
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center text-yellow-500 text-sm font-bold">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="ml-1">4.5</span>
                            </div>
                            <span class="text-gray-400 text-xs">•</span>
                            <span class="text-gray-600 text-sm">Menengah</span>
                            <span class="text-gray-400 text-xs">•</span>
                            <span class="text-gray-600 text-sm">45 Jam</span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" class="w-8 h-8 rounded-full border border-gray-200" alt="Instructor">
                                <div class="text-xs">
                                    <p class="font-semibold text-gray-900">Aisya Savitri</p>
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
    window.activeCategory = "pelatihan";
</script>
