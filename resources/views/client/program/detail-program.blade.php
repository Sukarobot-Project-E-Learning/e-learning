@extends('client.main')
@section('css')
  <link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program/detail-program.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
@endsection

@section('body')

  <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10 mt-20">

    <!-- KONTEN KIRI -->
    <div class="lg:col-span-2 space-y-8">
      <!-- Lokasi -->
      <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <span
          class="px-4 py-1.5 text-sm bg-blue-50 text-blue-700 rounded-full font-bold">{{ ucfirst($program->category) }}</span>

        @if($program->type == 'online')
          <p class="mt-4 text-sm text-gray-500 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
              </path>
            </svg>
            <span class="font-medium text-gray-700">Pertemuan Virtual via Zoom</span>
          </p>
          
          {{-- Link Zoom - Hanya tampil untuk user yang sudah membeli program --}}
          @if($isPurchased && !empty($program->zoom_link))
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-blue-800 mb-1">Link Meeting Zoom</p>
                  <a href="{{ $program->zoom_link }}" target="_blank" rel="noopener noreferrer"
                     class="text-sm text-blue-600 hover:text-blue-800 hover:underline break-all">
                    {{ $program->zoom_link }}
                  </a>
                </div>
                <a href="{{ $program->zoom_link }}" target="_blank" rel="noopener noreferrer"
                   class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors flex-shrink-0">
                  Join Meeting
                </a>
              </div>
            </div>
          @endif
        @else
          <p class="mt-4 text-sm text-gray-500 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
              </path>
            </svg>
            <span class="font-medium text-gray-700">
              @php
                $locationParts = array_filter([
                  $program->village ?? null,
                  $program->district ?? null,
                  $program->city ?? null,
                  $program->province ?? null
                ]);
              @endphp

              @if(!empty($locationParts))
                {{ implode(', ', $locationParts) }}
              @else
                Lokasi Offline
              @endif
            </span>
          </p>
          @if(!empty($program->full_address))
            <p class="text-xs text-gray-500 mt-2 ml-7 leading-relaxed">{{ $program->full_address }}</p>
          @endif
        @endif

        <p class="mt-6 text-gray-700 leading-relaxed text-base">
          {{ $program->description }}
        </p>
      </div>

      <!-- Jadwal -->
      <div
        class="bg-gradient-to-r from-blue-600 to-orange-500 p-8 rounded-2xl shadow-lg text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div class="relative z-10">
          <h2 class="font-bold text-xl mb-4 flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Jadwal Pelatihan
          </h2>
          <p class="text-blue-50 text-lg mb-6">
            {{ $program->start_date == $program->end_date ? \Carbon\Carbon::parse($program->start_date)->format('d M Y') : \Carbon\Carbon::parse($program->start_date)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($program->end_date)->format('d M Y') }} |
            {{ \Carbon\Carbon::parse($program->start_time)->format('H:i') }} -
            {{ \Carbon\Carbon::parse($program->end_time)->format('H:i') }} WIB
          </p>
          <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="font-bold">Sisa Slot: {{ $program->available_slots }} Kursi</span>
          </div>
        </div>
      </div>

      <!-- Instruktur -->
      <div
        class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
        <img
          src="{{ $program->instructor_avatar ? asset('storage/' . $program->instructor_avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($program->instructor_name ?? 'Instructor') . '&background=random' }}"
          alt="Instruktur" class="w-20 h-20 rounded-full border-4 border-blue-50 shadow-sm">
        <div>
          <h3 class="font-bold text-xl text-gray-900 mb-1">{{ $program->instructor_name ?? 'Sukarobot' }}</h3>
          <p class="text-blue-600 font-medium mb-3">{{ $program->instructor_job ?? 'Instruktur Profesional' }}</p>
          <p class="text-gray-600 text-sm leading-relaxed">{{ $program->instructor_description ?? 'Berpengalaman di bidangnya dan siap membimbing Anda.' }}.</p>
        </div>
      </div>

      <!-- Materi dengan Dropdown -->
      <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="font-bold text-xl mb-6 text-gray-900">Materi Pembelajaran</h2>
        <div class="space-y-4">
          @forelse($program->learning_materials as $index => $material)
            <div
              class="accordion border border-gray-200 rounded-xl p-5 cursor-pointer hover:bg-gray-50 transition-colors duration-300 group">
              <div class="flex justify-between items-center">
                <h4 class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                  {{ $material['title'] ?? 'Hari ' . ($index + 1) }}
                </h4>
                <span class="toggle text-xl text-gray-400 group-hover:text-blue-600 transition-colors">+</span>
              </div>
              <div class="accordion-content mt-4 text-gray-600 space-y-4">
                @if(!empty($material['duration']))
                  <div class="flex items-center gap-2 text-sm text-gray-500 bg-gray-50 p-2 rounded-lg w-fit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @php
                      $durationParts = explode(':', $material['duration']);
                      $hours = intval($durationParts[0] ?? 0);
                      $minutes = intval($durationParts[1] ?? 0);
                      $durationText = '';
                      if ($hours > 0) $durationText .= $hours . ' Jam';
                      if ($minutes > 0) $durationText .= ($hours > 0 ? ' ' : '') . $minutes . ' Menit';
                      if (empty($durationText)) $durationText = '0 Menit';
                    @endphp
                    Durasi: {{ $durationText }}
                  </div>
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
    <div class="lg:col-span-1 space-y-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 sticky top-28">
        <!-- Poster -->
        <div class="relative overflow-hidden rounded-xl mb-6 group">
          <img src="{{ asset($program->image ?? 'sukarobot.com/source/img/Sukarobot-logo.png') }}" alt="Poster Kelas"
            class="w-full object-cover transform group-hover:scale-105 transition duration-500">
        </div>

        @if($isPurchased)
          <!-- Tombol -->
          <div class="flex flex-col gap-3 mb-8">
            <a href="{{ route('client.dashboard.program') }}"
                class="w-full bg-green-600 text-white py-3.5 rounded-xl font-bold hover:bg-green-700 transition-colors shadow-lg hover:shadow-green-600/30 text-center flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                </svg>
                Lihat Kelas Saya
              </a>
          </div>
        @else
          <!-- Harga -->
          <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">Harga Kelas</p>
            @if($program->price > 0)
              <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($program->price, 0, ',', '.') }}</p>
            @else
              <p class="text-3xl font-bold text-green-600">GRATIS</p>
            @endif
          </div>
          <div class="flex flex-col gap-3 mb-8">
            <a href="{{ route('client.pembayaran', ['programSlug' => $program->slug]) }}"
              class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg hover:shadow-blue-600/30 text-center">
              Beli Kelas Sekarang
            </a>
            <button
              class="w-full border-2 border-gray-200 text-gray-700 py-3.5 rounded-xl font-bold hover:border-blue-600 hover:text-blue-600 transition-colors">
              Tukar Voucher
            </button>
          </div>
        @endif

        <!-- Benefit -->
        <div class="mb-8">
          <h3 class="font-bold mb-4 text-gray-900">Kamu Akan Mendapatkan:</h3>
          <div class="flex flex-wrap gap-2">
            @forelse($program->benefits as $benefit)
              <span
                class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">{{ $benefit }}</span>
            @empty
              <span class="text-gray-500 text-sm">Belum ada benefit</span>
            @endforelse
          </div>
        </div>

        <!-- Tools -->
        <div>
          <h3 class="font-bold mb-4 text-gray-900">Tools yang Digunakan</h3>
          <div class="space-y-3">
            @forelse($program->tools as $tool)
              <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <span class="text-sm font-bold text-gray-700">{{ $tool }}</span>
              </div>
            @empty
              <p class="text-gray-500 text-sm">Belum ada tools</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('js')
  <script src="{{ asset('assets/elearning/client/js/program/detail-program.js') }}"></script>
@endsection