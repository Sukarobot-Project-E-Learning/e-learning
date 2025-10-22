@extends('client.layout.main')
@section('body')

<!-- Filter Section -->
<section class="px-6 pt-4 pb-2 bg-white shadow-sm" aria-label="Filter Kelas">
  <!-- Filter Buttons -->
  <div class="flex items-center gap-3 overflow-x-auto scrollbar-hide pb-2">
    <div class="flex flex-nowrap gap-3">
      <button
        class="filter-btn flex-shrink-0 px-5 py-2 rounded-full font-semibold text-white bg-orange-500 border border-transparent hover:opacity-90 transition"
        data-filter="all">
        Semua Kelas
      </button>
      <button
        class="filter-btn flex-shrink-0 px-5 py-2 rounded-full font-semibold text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white transition"
        data-filter="Pelatihan">
        Pelatihan
      </button>
      <button
        class="filter-btn flex-shrink-0 px-5 py-2 rounded-full font-semibold text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white transition"
        data-filter="Training">
        Training
      </button>
      <button
        class="filter-btn flex-shrink-0 px-5 py-2 rounded-full font-semibold text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white transition"
        data-filter="Sertifikasi">
        Sertifikasi
      </button>
    </div>
  </div>
</section>

<!-- Jumlah Kelas -->
<div class="mt-6 mb-2 ml-6 text-left text-gray-400 text-sm sm:text-base font-medium">
    <span class="jumlah-kelas">0 Kelas</span>
  </div>

  <!-- Cards -->
  <main class="kelas-container grid gap-6 px-6 pb-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 items-stretch">

  <!-- Card 1 -->
  <a href="{{ url('dtail_kelas') }}" class="block group kelas-card" data-category="Pelatihan">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=1"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span>4.8</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-blue-600 px-2 py-1 rounded">Webinar</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 class="w-4 h-4 mr-1 text-yellow-700">
              <path fill-rule="evenodd"
                    d="M3 13a1 1 0 011-1h1V7a1 1 0 112 0v5h2V4a1 1 0 112 0v8h2V9a1 1 0 112 0v3h1a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span>Sisa 23 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Strategi Digital Marketing</h2>
        <p class="text-sm text-gray-600">Pengajar: Aisya Savitri</p>
        <div class="text-orange-600 font-bold">Rp 39.000</div>
      </div>
    </article>
  </a>

  <!-- Card 2 -->
  <a href="#" class="block group kelas-card" data-category="Training">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=2"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span>4.5</span>
        </div>
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-center justify-center">
          <span class="bg-black/70 text-white text-sm font-semibold px-3 py-1 rounded-full">Kuota Habis</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-orange-500 px-2 py-1 rounded">Seminar</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 class="w-4 h-4 mr-1 text-yellow-700">
              <path fill-rule="evenodd"
                    d="M3 13a1 1 0 011-1h1V7a1 1 0 112 0v5h2V4a1 1 0 112 0v8h2V9a1 1 0 112 0v3h1a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span>Sisa 7 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Mental Tangguh di Dunia Kerja</h2>
        <p class="text-sm text-gray-600">Instruktur: Dinar Latifah</p>
        <div class="text-orange-600 font-bold">Gratis</div>
      </div>
    </article>
  </a>

  <!-- Card 3 -->
  <a href="#" class="block group kelas-card" data-category="Pelatihan">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=3"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span>4.6</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-green-600 px-2 py-1 rounded">Offline</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 class="w-4 h-4 mr-1 text-yellow-700">
              <path fill-rule="evenodd"
                    d="M3 13a1 1 0 011-1h1V7a1 1 0 112 0v5h2V4a1 1 0 112 0v8h2V9a1 1 0 112 0v3h1a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span>Sisa 14 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Workshop Branding</h2>
        <p class="text-sm text-gray-600">Pengajar: Iskandar Roni</p>
        <div class="text-orange-600 font-bold">Rp 75.000</div>
      </div>
    </article>
  </a>

  <!-- Card 4 -->
  <a href="#" class="block group kelas-card" data-category="Sertifikasi">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=4"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span>4.9</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-purple-600 px-2 py-1 rounded">Online</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                 class="w-4 h-4 mr-1 text-yellow-700">
              <path fill-rule="evenodd"
                    d="M3 13a1 1 0 011-1h1V7a1 1 0 112 0v5h2V4a1 1 0 112 0v8h2V9a1 1 0 112 0v3h1a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span>Sisa 28 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Next Level Digital Skill</h2>
        <p class="text-sm text-gray-600">Pengajar: Nadya Pratiwi</p>
        <div class="text-orange-600 font-bold">Gratis</div>
      </div>
    </article>
  </a>

</main>

@endsection

<link rel="stylesheet" href="{{ asset('client/css/kelas.css') }}">
<script src="{{ asset('client/js/kelas.js') }}"></script>
