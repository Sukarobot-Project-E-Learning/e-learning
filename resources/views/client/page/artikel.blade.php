@extends('client.main')
@section('body')

<!-- Hero -->
<main class="max-w-7xl mx-auto px-6 pt-32 pb-12">

  <!-- Section Hero -->
  <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
    <div class="order-2 md:order-1">
      <span class="inline-block py-1 px-3 rounded-full bg-blue-50 text-blue-600 text-sm font-bold mb-4 border border-blue-100">
          RoboNews
      </span>
      <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-gray-900 mb-6">
        Wawasan Terkini <br>
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">
          Dunia Robotika
        </span>
      </h1>
      <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-lg">
        Dapatkan informasi terbaru seputar teknologi, tutorial, dan perkembangan industri robotika langsung dari para ahli.
      </p>

      <div class="flex flex-wrap gap-4">
        <a href="#articles" class="px-8 py-4 rounded-xl bg-blue-600 text-white font-bold shadow-lg hover:shadow-blue-600/30 hover:scale-105 transition-all duration-300">
          Jelajahi Artikel
        </a>
        <a href="{{ url('/tentang') }}" class="px-8 py-4 rounded-xl border-2 border-gray-200 text-gray-700 font-bold hover:border-blue-600 hover:text-blue-600 transition-all duration-300">
          Tentang Kami
        </a>
      </div>
    </div>

    <div class="order-1 md:order-2 relative">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-orange-100 rounded-3xl transform rotate-3 scale-105 -z-10"></div>
        <div class="w-full h-[400px] rounded-2xl overflow-hidden shadow-2xl border border-white">
            <img src="{{ asset('assets/elearning/client/img/blogilustrator.jpeg') }}"
                 alt="robot-illustration" class="w-full h-full object-cover hover:scale-110 transition duration-700" 
                 onerror="this.src='https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&w=800&q=80'"/>
        </div>
    </div>
  </section>

  <!-- Articles Grid Header -->
  <section id="articles" class="mt-12">
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-10 border-b border-gray-100 pb-8">
      <div>
          <h2 class="text-3xl font-bold text-gray-900">Artikel Terbaru</h2>
          <p class="text-gray-500 mt-1">Temukan tulisan menarik yang telah kami kurasi</p>
      </div>

      <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
        <!-- Custom Dropdown -->
        <div class="relative w-full md:w-48 z-20">
          <button id="categoryTrigger" class="w-full flex items-center justify-between bg-white border border-gray-200 text-sm font-medium rounded-xl px-4 py-3 hover:border-blue-400 transition-colors shadow-sm text-gray-700">
            <span id="selectedCategory">Semua Kategori</span>
            <svg class="w-4 h-4 ml-2 text-gray-400 transition-transform duration-200" id="dropdownIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <!-- Dropdown Menu -->
          <ul id="categoryMenu" class="absolute right-0 mt-2 w-full bg-white border border-gray-100 rounded-xl shadow-xl hidden overflow-hidden z-50 py-2">
            <li><button data-value="all" class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors font-medium">Semua Kategori</button></li>
            <li><button data-value="Riset & AI" class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors font-medium">Riset & AI</button></li>
            <li><button data-value="Produk" class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors font-medium">Produk</button></li>
            <li><button data-value="Event" class="block w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors font-medium">Event</button></li>
          </ul>
        </div>

        <button id="sortBtn" class="px-6 py-3 rounded-xl bg-gray-900 text-white text-sm font-bold shadow-lg hover:bg-gray-800 transition-all transform hover:-translate-y-0.5">
          Terbaru
        </button>
      </div>
    </div>

    <!-- Grid Berita -->
    <div id="articlesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

    <div class="mt-16 flex justify-center">
      <button id="loadMore" class="px-8 py-3.5 rounded-xl border-2 border-gray-200 text-gray-600 font-bold hover:border-blue-600 hover:text-blue-600 transition-all duration-300">
        Muat Lebih Banyak
      </button>
    </div>
  </section>

  <!-- Template Card Berita -->
<template id="cardTpl">
    <a data-link href="{{ url('/berita') }}" class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-1 h-full flex flex-col">
      <div class="relative overflow-hidden h-52">
          <img src="" alt="news-image" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
          <div class="absolute top-4 left-4">
              <span data-cat class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-blue-600 shadow-sm"></span>
          </div>
      </div>
      <div class="p-6 flex flex-col flex-grow">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span data-date></span>
        </div>
        <h3 data-title class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors"></h3>
        <div class="mt-auto pt-4 border-t border-gray-50 flex items-center text-blue-600 font-bold text-sm">
            Baca Selengkapnya
            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </div>
      </div>
    </a>
  </template>
</main>

<!-- Script -->
<script src="{{ asset('assets/elearning/client/js/artikel.js') }}"></script>

@endsection
