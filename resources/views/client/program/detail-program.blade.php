@extends('client.main')
@section('css')
  <link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program/detail-program.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
@endsection

@section('body')

  <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10 mt-20">

    <!-- KONTEN KIRI -->
    <div class="lg:col-span-2 space-y-8">
      @include('client.program.partials.location-and-type', [
        'program' => $program,
        'isPurchased' => $isPurchased,
        'isCourseProgram' => $isCourseProgram,
      ])

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
            Jadwal {{ ucfirst($program->category) }}
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
          src="{{ $program->instructor_avatar }}"
          alt="Instruktur" class="w-20 h-20 rounded-full border-4 border-blue-50 shadow-sm">
        <div>
          <h3 class="font-bold text-xl text-gray-900 mb-1">{{ $program->instructor_name ?? 'Sukarobot' }}</h3>
          <p class="text-blue-600 font-medium mb-3">{{ $program->instructor_job ?? 'Instruktur Profesional' }}</p>
          <p class="text-gray-600 text-sm leading-relaxed">{{ $program->instructor_description ?? 'Berpengalaman di bidangnya dan siap membimbing Anda.' }}.</p>
        </div>
      </div>

      <!-- Silabus -->
      <div id="silabus-kelas" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 scroll-mt-32">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
          <h2 class="font-bold text-xl text-gray-900">Silabus Kelas</h2>
        </div>

        <div class="space-y-4">
          @forelse($program->learning_materials as $index => $material)
            <div class="accordion border border-gray-200 rounded-xl p-5 cursor-pointer hover:bg-gray-50 transition-colors duration-300 group">
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
          @php
              $detailImageUrl = ($program->image && str_starts_with($program->image, 'images/'))
                  ? asset($program->image) 
                  : ($program->image ? asset('storage/' . $program->image) : asset('sukarobot.com/source/img/Sukarobot-logo.png'));
          @endphp
          <img src="{{ $detailImageUrl }}" alt="Poster Kelas"
            class="w-full object-cover transform group-hover:scale-105 transition duration-500">
        </div>

        @include('client.program.partials.purchase-cta-button', [
          'isPurchased' => $isPurchased,
          'isCourseProgram' => $isCourseProgram,
          'program' => $program,
        ])

        @if(!$isPurchased)
          <!-- Harga -->
          <div class="mb-6">
            <p class="text-sm text-gray-500 mb-1">Harga {{ ucfirst($program->category) }}</p>
            @if($program->price > 0)
              <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($program->price, 0, ',', '.') }}</p>
            @else
              <p class="text-3xl font-bold text-green-600">GRATIS</p>
            @endif
          </div>
          
          @php
              $now = \Carbon\Carbon::now();
              $startDate = \Carbon\Carbon::parse($program->start_date);
              $endDate = \Carbon\Carbon::parse($program->end_date);
              $isRunning = $now->between($startDate, $endDate);
              $isFinished = $now->gt($endDate);
              $isUpcoming = $now->lt($startDate);
              $isSoldOut = $program->available_slots <= 0;
          @endphp

          @include('client.program.partials.purchase-status-buttons', [
            'isSoldOut' => $isSoldOut,
            'isCourseProgram' => $isCourseProgram,
            'isFinished' => $isFinished,
            'isRunning' => $isRunning,
            'program' => $program,
          ])
        @endif

        @include('client.program.partials.voucher-recommendations', [
          'recommendedVouchers' => $recommendedVouchers,
          'isPurchased' => $isPurchased,
        ])

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