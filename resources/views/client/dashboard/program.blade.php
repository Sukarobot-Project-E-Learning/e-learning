<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/elearning/client/js/dashboard/program.js') }}"></script>

@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Program -->
    <section id="program" class="section">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Program Saya</h2>
      <div class="relative group mb-6">
          <!-- Left Arrow -->
          <button id="scroll-left" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 backdrop-blur-sm shadow-md rounded-full p-1.5 text-gray-600 hover:text-blue-600 hidden md:hidden lg:hidden transition-opacity duration-300 opacity-0 pointer-events-none flex items-center justify-center" aria-label="Scroll Left">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
          </button>

          <!-- Scrollable Container -->
          <div id="program-nav" class="flex overflow-x-auto gap-2 scrollbar-hide snap-x">
              <button data-filter="Semua Program" class="filter-btn px-4 py-2 text-sm font-medium rounded-full bg-blue-500 text-white whitespace-nowrap snap-start shrink-0 transition-colors duration-200 cursor-pointer">Semua Program</button>
              <button data-filter="Kursus" class="filter-btn px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0 transition-colors duration-200 cursor-pointer">Kursus</button>
              <button data-filter="Pelatihan" class="filter-btn px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0 transition-colors duration-200 cursor-pointer">Pelatihan</button>
              <button data-filter="Sertifikasi" class="filter-btn px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0 transition-colors duration-200 cursor-pointer">Sertifikasi</button>
              <button data-filter="Outing Class" class="filter-btn px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0 transition-colors duration-200 cursor-pointer">Outing Class</button>
              <button data-filter="Outboard" class="filter-btn px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0 transition-colors duration-200 cursor-pointer">Outboard</button>
          </div>

          <!-- Right Arrow -->
          <button id="scroll-right" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 backdrop-blur-sm shadow-md rounded-full p-1.5 text-gray-600 hover:text-blue-600 lg:hidden flex items-center justify-center" aria-label="Scroll Right">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
      </div>

      <style>
          .scrollbar-hide::-webkit-scrollbar {
              display: none;
          }
          .scrollbar-hide {
              -ms-overflow-style: none;
              scrollbar-width: none;
          }
      </style>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($enrollments as $enrollment)
          <div class="program-card flex flex-col rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-lg transition-shadow duration-300" data-category="{{ $enrollment->category }}">
            <div class="relative">
              @php
                  $enrollmentImageUrl = ($enrollment->image && str_starts_with($enrollment->image, 'images/'))
                      ? asset($enrollment->image) 
                      : ($enrollment->image ? asset('storage/' . $enrollment->image) : asset('assets/elearning/client/img/home1.jpeg'));
              @endphp
              <div class="w-full bg-center bg-no-repeat aspect-[16/9] bg-cover" 
                   data-alt="{{ $enrollment->program_name }}" 
                   style='background-image: url("{{ $enrollmentImageUrl }}");'>
              </div>
              <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-blue-600 z-20">
                  {{ $enrollment->category }}
              </span>
            </div>
            <div class="flex flex-col p-5 gap-4 flex-1">
              <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">{{ $enrollment->program_name }}</h2>
              <p class="text-gray-500 text-sm font-normal leading-normal">Dibeli pada: {{ \Carbon\Carbon::parse($enrollment->created_at)->translatedFormat('d M Y') }}</p>
              
              {{-- Tombol Detail Kelas - selalu tampil untuk melihat detail program --}}
              <a href="{{ route('client.program.detail', $enrollment->slug) }}" class="flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-100 text-gray-700 text-sm font-medium leading-normal hover:bg-gray-200 border border-gray-300 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span class="truncate">Detail Kelas</span>
              </a>

              @if($enrollment->proof_id)
                  <a href="{{ route('client.program.detail', $enrollment->slug) }}" class="flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-500 text-white text-sm font-medium leading-normal hover:bg-gray-600 focus:ring-4 focus:ring-primary/30">
                    <span class="truncate">Lihat Detail</span>
                  </a>
              @else
                  <a href="{{ route('client.program.proof', $enrollment->slug) }}" class="flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-blue-600 text-white text-sm font-medium leading-normal hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 shadow-md shadow-blue-500/20">
                    <span class="truncate">Kirim Bukti Program</span>
                  </a>
              @endif
            </div>
          </div>
        @endforeach
        
        <div id="no-program-message" class="col-span-1 lg:col-span-2 bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100 {{ $enrollments->isEmpty() ? '' : 'hidden' }}">
          <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada program</h3>
          <p class="text-gray-500 mb-8 max-w-sm mx-auto">Anda belum terdaftar di program apapun untuk kategori ini.</p>
          <a href="{{ route('client.program.semua-kelas') }}" class="inline-flex items-center justify-center bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
            Jelajahi Program
          </a>
        </div>
      </div>
      <!-- <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0 mt-10 pt-6">
          <div class="-mt-px flex w-0 flex-1">
            <a class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">
            <span class="material-symbols-outlined mr-3 h-5 w-5"><</span>Sebelumnya</a>
          </div>
          <div class="hidden md:-mt-px md:flex">
            <a aria-current="page" class="inline-flex items-center border-t-2 border-primary px-4 pt-4 text-sm font-medium text-primary" href="#">1</a>
            <a class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">2</a>
            <a class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">3</a>
          </div>
          <div class="-mt-px flex w-0 flex-1 justify-end">
            <a class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 " href="#">Selanjutnya<span class="material-symbols-outlined ml-3 h-5 w-5">></span></a>
          </div>
      </nav> -->
    </section>
@endsection
