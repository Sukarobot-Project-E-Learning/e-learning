<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
  <span class="px-4 py-1.5 text-sm bg-blue-50 text-blue-700 rounded-full font-bold">{{ ucfirst($program->category) }}</span>

  @if($program->type == 'online')
    <p class="mt-4 text-sm text-gray-500 flex items-center gap-2">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
        </path>
      </svg>
      <span class="font-medium text-gray-700">Pertemuan Virtual via Zoom</span>
    </p>

    @include('client.program.partials.zoom-link-card', [
      'isPurchased' => $isPurchased,
      'isCourseProgram' => $isCourseProgram,
      'program' => $program,
    ])
  @else
    <p class="mt-4 text-sm text-gray-500 flex items-center gap-2">
      <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
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
