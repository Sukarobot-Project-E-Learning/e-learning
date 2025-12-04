@extends('client.main')
@section('css')
  <link rel="stylesheet" href="{{ asset('assets/elearning/client/css/detail-program.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
@endsection

@section('body')

  <div class="max-w-7xl mx-auto px-4 md:px-6 py-10 grid grid-cols-1 lg:grid-cols-3 gap-8 mt-15">

    <!-- KONTEN KIRI -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Lokasi/Type -->
      <div class="bg-white p-5 rounded-xl shadow">
        <span class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-full font-medium">
          {{ ucfirst($program->category) }}
        </span>
        @if($program->type == 'online')
          <p class="mt-3 text-sm text-gray-600">📍 Pertemuan Virtual via Zoom</p>
        @else
          <p class="mt-3 text-sm text-gray-600">📍 {{ $program->city }}, {{ $program->province }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ $program->full_address }}</p>
        @endif
        <p class="mt-4 text-gray-700 leading-relaxed">
          {{ $program->description }}
        </p>
      </div>

      <!-- Jadwal -->
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-5 rounded-xl shadow text-white">
        <h2 class="font-semibold text-lg mb-3">📅 Jadwal Pelatihan</h2>
        <p class="text-sm">
          {{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }} -
          {{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }} |
          {{ \Carbon\Carbon::parse($program->start_time)->format('H:i') }} -
          {{ \Carbon\Carbon::parse($program->end_time)->format('H:i') }} WIB
        </p>
        <div class="mt-3">
          <span class="slot-badge">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 11c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM19 11c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM8 16c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM16 16c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2z" />
            </svg>
            Sisa Slot: {{ $program->available_slots }}
          </span>
        </div>
      </div>

      <!-- Instruktur -->
      <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
        <img src="{{ $program->instructor_avatar ?? 'https://randomuser.me/api/portraits/women/44.jpg' }}"
          alt="Instruktur" class="w-16 h-16 rounded-full">
        <div>
          <h3 class="font-semibold text-lg text-blue-700">{{ $program->instructor_name ?? 'Sukarobot' }}</h3>
          <p class="text-sm text-gray-600">{{ $program->instructor_job ?? 'Instruktur Profesional' }}</p>
        </div>
      </div>

      <!-- Materi dengan Dropdown -->
      <div class="bg-white p-5 rounded-xl shadow">
        <h2 class="font-semibold text-lg mb-4 text-gray-800">Materi Pembelajaran</h2>
        <div class="space-y-4">

          @forelse($program->learning_materials as $index => $material)
            <!-- Hari {{ $index + 1 }} -->
            <div class="accordion border border-blue-200 rounded-lg p-4 cursor-pointer hover:bg-blue-50 transition">
              <div class="flex justify-between items-center">
                <h4 class="font-medium text-blue-700">{{ $material['title'] ?? 'Hari ' . ($index + 1) }}</h4>
                <span class="toggle text-xl text-orange-500">+</span>
              </div>
              <div class="accordion-content mt-3 text-sm text-gray-600 space-y-3">
                @if(!empty($material['duration']))
                  <p class="text-gray-500">Durasi: {{ $material['duration'] }}</p>
                @endif
                @if(!empty($material['description']))
                  <p>{!! nl2br(e($material['description'])) !!}</p>
                @endif
              </div>
            </div>
          @empty
            <p class="text-gray-500 text-sm">Materi pembelajaran belum tersedia</p>
          @endforelse

        </div>
      </div>

    </div>

    <!-- KONTEN KANAN -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white p-6 rounded-xl shadow space-y-5">
        <!-- Poster -->
        <img src="{{ asset($program->image ?? 'sukarobot.com/source/img/Sukarobot-logo.png') }}" alt="Poster Kelas"
          class="rounded-lg w-full mb-3">

        <!-- Harga -->
        @if($program->price > 0)
          <p class="text-orange-600 font-semibold text-xl">Rp {{ number_format($program->price, 0, ',', '.') }}</p>
        @else
          <p class="text-green-600 font-semibold text-xl">GRATIS</p>
        @endif

        <!-- Tombol -->
        <div class="flex flex-col gap-2">
          <a href="{{ url('pembayaran') }}"><button
              class="w-full bg-gradient-to-r from-orange-500 to-blue-600 text-white py-2 rounded-lg font-medium hover:opacity-90 transition cursor-pointer">Beli
              Kelas</button></a>
          <button
            class="w-full border border-blue-400 text-blue-600 py-2 rounded-lg font-medium hover:bg-blue-50 transition cursor-pointer">Tukar
            Voucher Kelas</button>
        </div>

        <!-- Benefit -->
        <div>
          <h3 class="font-semibold mb-2 text-blue-700">Kamu Akan Mendapatkan:</h3>
          <div class="flex flex-wrap gap-2">
            @forelse($program->benefits as $benefit)
              <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg text-xs">{{ $benefit }}</span>
            @empty
              <span class="text-gray-500 text-sm">Belum ada benefit</span>
            @endforelse
          </div>
        </div>

        <!-- Tools -->
        <div>
          <h3 class="font-semibold mb-3 text-blue-700">Tools yang Digunakan</h3>
          <div class="flex flex-col gap-3">
            @forelse($program->tools as $tool)
              <div class="flex items-center gap-3 tool-item">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">{{ $tool }}</span>
              </div>
            @empty
              <p class="text-gray-500 text-sm">Belum ada tools</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/elearning/client/js/detail-program.js') }}"></script>

@endsection