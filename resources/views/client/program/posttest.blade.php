@extends('client.main')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program/classroom.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('body')
  <div class="classroom-page min-h-screen bg-slate-50 pt-24 pb-12">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
      @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
          {{ session('error') }}
        </div>
      @endif

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <aside class="lg:col-span-4 xl:col-span-3">
          <div class="classroom-sidebar rounded-2xl border border-slate-200 bg-white shadow-sm lg:sticky lg:top-24">
            <div class="border-b border-slate-100 px-5 py-4">
              <p class="text-[11px] font-bold tracking-[0.18em] text-blue-600 uppercase">KONTEN KURSUS</p>
              <h2 class="mt-2 text-xl font-extrabold text-slate-900">{{ $program->program }}</h2>
              <p class="mt-1 text-xs text-slate-500">{{ $completedCount }}/{{ $totalItems }} Materi Selesai</p>

              <div class="mt-4 h-2 rounded-full bg-slate-100">
                <div id="class-progress-bar" data-progress="{{ $progressPercent }}" class="h-2 w-0 rounded-full bg-blue-600 transition-all"></div>
              </div>
            </div>

            <div class="px-5 py-4 border-b border-slate-100">
              <label for="lesson-search" class="sr-only">Cari Materi</label>
              <div class="relative">
                <input
                  id="lesson-search"
                  type="text"
                  placeholder="Cari materi..."
                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >
                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.999 5.999a7.5 7.5 0 0010.651 10.651z" />
                  </svg>
                </span>
              </div>
            </div>

            <div class="lesson-list px-3 py-3">
              @php $lastSectionId = null; @endphp
              @forelse($syllabusItems as $item)
                @php
                  $isSelected = $selectedItem && $selectedItem['index'] === $item['index'];
                  $itemUrl = route('client.program.classroom', ['slug' => $program->slug, 'item' => $item['index']]);
                @endphp

                @if($lastSectionId !== $item['section_id'])
                  <div class="px-2 pt-2 pb-1">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $item['section_title'] }}</p>
                  </div>
                  @php $lastSectionId = $item['section_id']; @endphp
                @endif

                @if($item['is_locked'])
                  <div data-lesson-title="{{ strtolower($item['title']) }}" class="lesson-entry mb-2 cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 opacity-70">
                    <div class="flex items-start justify-between gap-3">
                      <div>
                        <p class="text-sm font-semibold text-slate-500">{{ $item['title'] }}</p>
                        <p class="mt-1 text-xs font-medium text-slate-400">Terkunci</p>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V7a5 5 0 00-10 0v2H6a2 2 0 00-2 2v6a2 2 0 002 2zm3-8V7a3 3 0 116 0v2H9z" />
                      </svg>
                    </div>
                  </div>
                @else
                  <a
                    href="{{ $itemUrl }}"
                    data-lesson-title="{{ strtolower($item['title']) }}"
                    class="lesson-entry mb-2 block rounded-xl border px-4 py-3 transition {{ $isSelected ? 'border-blue-200 bg-blue-50' : 'border-slate-200 bg-white hover:border-blue-200 hover:bg-slate-50' }}"
                  >
                    <div class="flex items-start justify-between gap-3">
                      <div>
                        <p class="text-sm font-semibold {{ $isSelected ? 'text-blue-700' : 'text-slate-700' }}">{{ $item['title'] }}</p>
                        @if($item['is_done'])
                          <p class="mt-1 text-xs font-semibold text-emerald-600">Selesai</p>
                        @elseif($item['is_current'])
                          <p class="mt-1 text-xs font-semibold text-blue-600">Sedang dipelajari</p>
                        @else
                          <p class="mt-1 text-xs font-semibold text-slate-500">Belum selesai</p>
                        @endif
                      </div>

                      @if($item['is_done'])
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                      @elseif($item['is_current'])
                        <span class="inline-flex h-4 w-4 rounded-full bg-blue-500"></span>
                      @endif
                    </div>
                  </a>
                @endif
              @empty
                <p class="px-2 py-8 text-center text-sm text-slate-500">Materi belum tersedia.</p>
              @endforelse

              @if($hasAssignments)
                <div class="mt-4 border-t border-slate-100 pt-4">
                  <p class="px-2 pb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">POST-TEST</p>
                  <div data-lesson-title="post-test" class="lesson-entry mb-2 block rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
                    <div class="flex items-start justify-between gap-3">
                      <div>
                        <p class="text-sm font-semibold text-blue-700">Post-Test</p>
                        <p class="mt-1 text-xs font-semibold text-slate-500">Kerjakan sekarang</p>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2z" />
                      </svg>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </aside>

        <main class="lg:col-span-8 xl:col-span-9 space-y-6">
          <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
              <div>
                <a href="{{ route('client.program.classroom', ['slug' => $program->slug]) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                  Kembali ke materi
                </a>
                <h1 class="mt-2 text-2xl font-extrabold text-slate-900">Post-Test</h1>
              </div>

              <span class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                Status: {{ $isCourseCompleted ? 'Selesai' : 'Berjalan' }} ({{ $progressPercent }}%)
              </span>
            </div>

            <div class="px-5 py-6 sm:px-8">
              @if($assignments->isNotEmpty())
                <div class="space-y-4">
                  @foreach($assignments as $assignment)
                    @php
                      $submission = $assignmentSubmissions->get($assignment->id);
                      $isLate = $assignment->due_date && now()->greaterThan($assignment->due_date);
                      $allowedExtensions = collect(explode(',', $assignment->allowed_extensions ?? ''))
                        ->map(fn($ext) => trim($ext))
                        ->filter();
                      $accept = $allowedExtensions
                        ->map(fn($ext) => '.' . ltrim($ext, '.'))
                        ->implode(',');
                    @endphp

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                      <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                          <h3 class="text-base font-bold text-slate-900">{{ $assignment->title }}</h3>
                          @if($assignment->description)
                            <p class="mt-2 text-sm text-slate-600">{{ $assignment->description }}</p>
                          @endif
                          <div class="mt-3 flex flex-wrap gap-3 text-xs font-semibold text-slate-500">
                            <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                              </svg>
                              {{ $assignment->allowed_extensions ?: 'Semua format' }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                              {{ $assignment->due_date ? $assignment->due_date->format('d M Y') : 'Tidak ada batas waktu' }}
                            </span>
                            @if($isLate)
                              <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1 text-red-600">
                                Batas waktu lewat
                              </span>
                            @endif
                          </div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs text-slate-600">
                          <p class="font-semibold text-slate-700">Status</p>
                          @if($submission)
                            <p class="mt-1 text-emerald-600">Terkirim {{ optional($submission->submitted_at)->format('d M Y H:i') }}</p>
                            @if(!is_null($submission->grade))
                              <p class="mt-1">Nilai: <span class="font-semibold text-slate-800">{{ $submission->grade }}</span></p>
                            @endif
                            @if(!empty($submission->feedback))
                              <p class="mt-1 text-slate-500">Feedback: {{ $submission->feedback }}</p>
                            @endif
                          @else
                            <p class="mt-1 text-slate-500">Belum dikumpulkan</p>
                          @endif
                        </div>
                      </div>

                      <div class="mt-4">
                        <form action="{{ route('client.program.assignment.submit', ['slug' => $program->slug, 'assignment' => $assignment->id]) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                          @csrf
                          <input type="file" name="assignment_file" accept="{{ $accept }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700" {{ $isLate ? 'disabled' : '' }}>
                          <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-300" {{ $isLate ? 'disabled' : '' }}>
                            {{ $submission ? 'Unggah Ulang' : 'Kirim Tugas' }}
                          </button>
                        </form>
                        <p class="mt-2 text-xs text-slate-500">Maksimal 10MB. Format: {{ $assignment->allowed_extensions ?: 'semua format' }}.</p>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="text-sm text-slate-500">Post-test belum tersedia untuk kelas ini.</p>
              @endif
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script src="{{ asset('assets/elearning/client/js/program/classroom.js') }}"></script>
@endsection
