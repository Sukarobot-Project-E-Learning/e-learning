@if($isPurchased && !$isCourseProgram && !empty($program->zoom_link))
  <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
    <div class="flex flex-col sm:flex-row items-center gap-3">
      <div class="flex items-center gap-3 w-full sm:w-auto overflow-hidden">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
            </path>
          </svg>
        </div>
        <div class="flex-1 min-w-0 overflow-hidden">
          <p class="text-sm font-semibold text-blue-800 mb-1">Link Meeting Zoom</p>
          <a href="{{ $program->zoom_link }}" target="_blank" rel="noopener noreferrer"
             class="text-sm text-blue-600 hover:text-blue-800 hover:underline truncate block">
            {{ $program->zoom_link }}
          </a>
        </div>
      </div>

      <a href="{{ $program->zoom_link }}" target="_blank" rel="noopener noreferrer"
        class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center flex-shrink-0 mt-2 sm:mt-0">
        Join Meeting
      </a>
    </div>
  </div>
@endif
