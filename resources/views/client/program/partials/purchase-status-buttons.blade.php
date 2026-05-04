<div class="flex flex-col gap-3 mb-8">
  @if($isSoldOut)
    <button disabled
      class="w-full bg-red-400 text-white py-3.5 rounded-xl font-bold cursor-not-allowed text-center opacity-80">
      Kuota Habis
    </button>
  @elseif(!$isCourseProgram && $isFinished)
    <button disabled
      class="w-full bg-gray-400 text-white py-3.5 rounded-xl font-bold cursor-not-allowed text-center opacity-80">
      Program Selesai
    </button>
  @elseif(!$isCourseProgram && $isRunning)
    <button disabled
      class="w-full bg-blue-300 text-white py-3.5 rounded-xl font-bold cursor-not-allowed text-center opacity-80">
      Pendaftaran Ditutup (Sedang Berjalan)
    </button>
  @else
    <a href="{{ route('client.pembayaran', ['programSlug' => $program->slug]) }}"
      class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg hover:shadow-blue-600/30 text-center">
      Beli {{ ucfirst($program->category) }} Sekarang
    </a>
  @endif
</div>
