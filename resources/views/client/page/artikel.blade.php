@extends('client.main')
@section('body')

<!-- Hero -->
<main class="max-w-6xl mx-auto px-6 pt-28 pb-12">

  <!-- Section Hero -->
  <section class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
    <div class="md:col-span-2">
      <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-900">
        Berita & Wawasan
        <span class="bg-gradient-to-r from-orange-500 to-blue-900 bg-clip-text text-transparent">
          Robotics
        </span> untuk Masa Depan
      </h1>
      <p class="mt-4 text-gray-600 max-w-2xl">
        RoboNews menyajikan artikel, riset, dan pengumuman produk dari tim kami — profesional, minimalis, dan mudah dibaca. Tetap terdepan dalam inovasi robotika.
      </p>

      <div class="mt-6 flex flex-wrap gap-3">
        <a href="#articles" class="px-4 py-2 rounded-md bg-gradient-to-r from-orange-500 to-blue-900 text-white font-semibold shadow">
          Lihat Berita
        </a>
        <a href="#about" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700">
          Tentang Kami
        </a>
      </div>
    </div>

    <div class="hidden md:flex items-center justify-center">
      <div class="w-full h-56 rounded-2xl overflow-hidden bg-gradient-to-br from-orange-200 to-blue-200 border border-gray-200 shadow-lg flex items-center justify-center">
        <img src="{{ asset('assets/elearning/client/img/blogilustrator.jpeg') }}"
             alt="robot-illustration" class="w-full h-full object-cover opacity-90" />
      </div>
    </div>
  </section>

  <!-- Articles Grid Header -->
  <section id="articles" class="mt-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
      <h2 class="text-3xl font-bold text-gray-900 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
        Kumpulan Berita
      </h2>

      <div class="flex items-center gap-3 w-full sm:w-auto">
        <!-- Custom Dropdown -->
        <div class="relative inline-block w-full sm:w-auto">
          <button id="categoryTrigger" class="flex items-center justify-between w-full sm:w-48 bg-white border border-gray-200 text-sm rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200 hover:shadow-md hover:border-gray-300 text-gray-700">
            <span id="selectedCategory">Semua Kategori</span>
            <svg class="w-4 h-4 ml-2 transition-transform duration-200" id="dropdownIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <!-- Dropdown Menu -->
          <ul id="categoryMenu" class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible transform scale-95 translate-y-1 transition-all duration-300 ease-out z-10 overflow-hidden">
            <li><button data-value="all" class="block w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">Semua Kategori</button></li>
            <li><button data-value="research" class="block w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">Riset & AI</button></li>
            <li><button data-value="product" class="block w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">Produk</button></li>
            <li><button data-value="event" class="block w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">Event</button></li>
          </ul>
        </div>

        <button id="sortBtn" class="text-sm px-4 py-2.5 rounded-lg bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white font-medium shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-blue-800 transition-all duration-200 transform hover:scale-105">
          Terbaru
        </button>
      </div>
    </div>

    <!-- Grid Berita -->
    <div id="articlesGrid" class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>

    <div class="mt-10 flex justify-center">
      <button id="loadMore" class="px-8 py-3 rounded-lg bg-gradient-to-r from-orange-500 via-orange-600 to-blue-900 text-white font-medium shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 cursor-pointer">
        Muat lebih banyak
      </button>
    </div>
  </section>

  <!-- Template Card Berita -->
<template id="cardTpl">
    <a data-link href="{{ url('/berita') }}" class="block bg-white rounded-xl overflow-hidden hover:shadow-xl transition-all duration-200 hover:scale-[1.02] focus:outline-none">
      <img src="" alt="news-image" class="w-full h-40 object-cover">
      <div class="p-4">
        <span data-cat class="text-xs font-semibold text-orange-600"></span>
        <h3 data-title class="text-lg font-bold text-gray-900 mt-1"></h3>
        <p data-excerpt class="text-sm text-gray-600 mt-1"></p>
        <p data-date class="text-xs text-gray-400 mt-2"></p>
      </div>
    </a>
  </template>
</main>

<!-- Script -->
<script src="{{ asset('assets/elearning/client/js/artikel.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/artikel.css') }}">

@include('client.partials.footer')
@endsection
