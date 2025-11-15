@extends('client.layout.main')
@section('body')

<!-- Main Container with Sidebar -->
<div class="flex min-h-screen bg-gray-50">
  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-white shadow-lg fixed h-screen overflow-y-auto transform transition-transform duration-300 ease-in-out z-40">
    <div class="p-5">
      <!-- Sort Options -->
      <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">Urutkan</h3>
        <div class="space-y-2">
          <button class="sort-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition flex items-center space-x-2 text-sm active" data-sort="newest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
            <span>Terbaru</span>
          </button>
          <button class="sort-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition flex items-center space-x-2 text-sm" data-sort="oldest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4 4m0 0l4-4m-4 4v-12"/></svg>
            <span>Terlama</span>
          </button>
          <button class="sort-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition flex items-center space-x-2 text-sm" data-sort="oldest">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <rect x="4" y="4" width="16" height="16" rx="3" ry="3" stroke-width="2"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
            </svg>
            <span>Tersedia</span>
          </button>
        </div>
      </div>

      <!-- Filter Options -->
      <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">Kategori</h3>
        <div class="space-y-2">
          <button class="filter-btn w-full text-left px-3 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600 transition text-sm" data-filter="all">
            Semua Kelas
          </button>
          <button class="filter-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition text-sm" data-filter="kursus">
            Kursus
          </button>
          <button class="filter-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition text-sm" data-filter="pelatihan">
            Pelatihan
          </button>
          <button class="filter-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition text-sm" data-filter="sertifikasi">
            Sertifikasi
          </button>
          <button class="filter-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition text-sm" data-filter="outingclass">
            Outing Class
          </button>
          <button class="filter-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-50 transition text-sm" data-filter="outboard">
            Outboard
          </button>
        </div>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 ml-64">
    <!-- Header with search and total count -->
    <div class="p-6 bg-white shadow-sm mb-5">
      <div class="flex justify-between items-center gap-4">

        <div class="flex gap-2 items-center">
          <!-- sidebar toggle -->
          <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>

          <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 hidden z-30 lg:max-w-screen"></div>

          <!-- Nama Kategori -->
          <h2 class="header text-2xl font-bold text-blue-700">Semua Kelas</h2>
        </div>
        
        
        <!-- Search Box -->
        <div class="relative max-w-md w-full">
          <input type="text" id="kelas-search" placeholder="Cari kelas..."
            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
          <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
      </div>
    </div>

  <!-- Cards -->
  <main class="kelas-container grid gap-6 px-6 pb-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 items-stretch">

  <!-- Card 1 -->
  <a href="{{ url('kelas/dtail_kelas') }}" class="block group kelas-card" data-category="kursus">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=1"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span class="text-white">4.8</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-blue-600 px-2 py-1 rounded">Webinar</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns='http://www.w3.org/2000/svg' class='inline w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 4.636' /></svg>
            <span>Sisa 23 Kuota</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Strategi Digital Marketing</h2>
        <div class="flex items-center mb-2">
          <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Aisya Savitri" class="w-9 h-9 rounded-full mr-2 border-2 border-white shadow">
          <div class="flex-col">
            <p class="text-sm text-gray-600 font-medium">Aisya Savitri</p>
            <p class="text-xs text-gray-600">Digital Marketing</p>
          </div>
        </div>
        <div class="mt-auto">
          <span class="text-orange-600 font-bold">Rp 390.000</span>
        </div>
      </div>
    </article>
  </a>

  <!-- Card 2 -->
  <a href="#" class="block group kelas-card" data-category="pelatihan">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=2"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span class="text-white">4.5</span>
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
            <svg xmlns='http://www.w3.org/2000/svg' class='inline w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 4.636' /></svg>
            <span>Sisa 0 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Mental Tangguh di Dunia Kerja</h2>
        <div class="flex items-center mb-2">
          <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Aisya Savitri" class="w-9 h-9 rounded-full mr-2 border-2 border-white shadow">
          <div class="flex-col">
            <p class="text-sm text-gray-600 font-medium">Aisya Savitri</p>
            <p class="text-xs text-gray-600">Digital Marketing</p>
          </div>
        </div>
        <div class="text-orange-600 font-bold">Gratis</div>
      </div>
    </article>
  </a>

  <!-- Card 3 -->
  <a href="#" class="block group kelas-card" data-category="sertifikasi">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=3"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span class="text-white">4.6</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-green-600 px-2 py-1 rounded">Offline</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns='http://www.w3.org/2000/svg' class='inline w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 4.636' /></svg>
            <span>Sisa 14 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Workshop Branding</h2>
        <div class="flex items-center mb-2">
          <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Aisya Savitri" class="w-9 h-9 rounded-full mr-2 border-2 border-white shadow">
          <div class="flex-col">
            <p class="text-sm text-gray-600 font-medium">Aisya Savitri</p>
            <p class="text-xs text-gray-600">Digital Marketing</p>
          </div>
        </div>
        <div class="text-orange-600 font-bold">Rp 75.000</div>
      </div>
    </article>
  </a>

  <!-- Card 4 -->
  <a href="#" class="block group kelas-card" data-category="outingclass">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=4"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span class="text-white">4.9</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-purple-600 px-2 py-1 rounded">Online</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns='http://www.w3.org/2000/svg' class='inline w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 4.636' /></svg>
            <span>Sisa 28 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Next Level Digital Skill</h2>
        <div class="flex items-center mb-2">
          <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Aisya Savitri" class="w-9 h-9 rounded-full mr-2 border-2 border-white shadow">
          <div class="flex-col">
            <p class="text-sm text-gray-600 font-medium">Aisya Savitri</p>
            <p class="text-xs text-gray-600">Digital Marketing</p>
          </div>
        </div>
        <div class="text-orange-600 font-bold">Gratis</div>
      </div>
    </article>
  </a>

  <!-- Card 5 -->
  <a href="#" class="block group kelas-card" data-category="outboard">
    <article class="rounded-xl shadow-md hover:shadow-lg transition bg-white cursor-pointer h-full overflow-hidden">
      <div class="relative">
        <img src="https://picsum.photos/400/200?random=5"
             class="w-full h-40 object-cover group-hover:opacity-90 transition" />
        <div class="absolute bottom-2 left-2 bg-black/60 text-yellow-400 text-xs px-2 py-0.5 rounded flex items-center space-x-1">
          <span>⭐</span><span class="text-white">4.9</span>
        </div>
      </div>
      <div class="p-4 space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-white bg-purple-600 px-2 py-1 rounded">Online</span>
          <div class="flex items-center text-[11px] font-medium text-gray-700 bg-yellow-100 px-2 py-1 rounded-full">
            <svg xmlns='http://www.w3.org/2000/svg' class='inline w-4 h-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-7.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 4.636' /></svg>
            <span>Sisa 28 Slot</span>
          </div>
        </div>
        <h2 class="text-lg font-semibold group-hover:text-blue-600">Web Programming</h2>
        <div class="flex items-center mb-2">
          <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Aisya Savitri" class="w-9 h-9 rounded-full mr-2 border-2 border-white shadow">
          <div class="flex-col">
            <p class="text-sm text-gray-600 font-medium">Aisya Savitri</p>
            <p class="text-xs text-gray-600">Digital Marketing</p>
          </div>
        </div>
        <div class="text-orange-600 font-bold">Gratis</div>
      </div>
    </article>
  </a>
</main>



@endsection

<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/kelas.css') }}">
<script src="{{ asset('assets/elearning/client/js/kelas.js') }}"></script>
