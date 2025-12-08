@extends('client.main')

@section('css')
<link rel="stylesheet" href="{{ 'client/css/instruktur.css' }}">
@endsection

@section('body')
<!-- HERO SECTION -->
<section class="py-16 bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-visible text-center pt-34">
    <!-- Background Elements -->
    <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-orange-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-200/20 rounded-full blur-[80px] animate-pulse pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
            Instruktur <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Profesional & Berpengalaman</span>
        </h1>
        <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
            Belajar langsung dari para ahli yang siap membimbingmu mencapai potensi terbaik.
        </p>
        
        <!-- Custom dropdown -->
        <div class="relative w-full max-w-xs mx-auto z-20">
            <button id="dropdownBtn" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-700 bg-white shadow-sm flex justify-between items-center hover:border-blue-400 transition font-medium">
                <span>Semua Keahlian</span>
                <svg id="dropdownIcon" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6" />
                </svg>
            </button>
            <ul id="dropdownMenu" class="absolute left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl hidden overflow-auto z-[9999] py-2">
                <li class="px-4 py-2.5 hover:bg-blue-50 hover:text-blue-600 cursor-pointer transition-colors text-sm font-medium text-gray-600" data-value="all">Semua Keahlian</li>
            </ul>
        </div>
    </div>
</section>

<!-- GRID INSTRUKTUR + PAGINATION -->
<div class="container mx-auto px-6 py-16 max-w-7xl">
  <div id="instructorGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8"></div>
  <div id="pagination" class="flex justify-center mt-12 space-x-2 flex-wrap"></div>
</div>

<!-- MODAL SCROLLABLE -->
<div class="fixed inset-0 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden z-[9999]" id="modalBg">
  <div class="bg-white rounded-2xl max-w-md w-full relative flex flex-col shadow-2xl overflow-hidden transform transition-all scale-100">
    <button class="absolute top-3 right-3 bg-white/80 p-1 rounded-full text-gray-500 hover:text-red-500 transition-colors z-10" id="modalClose">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <img id="modalImage" class="w-full h-56 object-cover" src="" alt="Instruktur">
    <div id="modalBody" class="p-6 overflow-y-auto max-h-[60vh]"></div>
  </div>
</div>

<script>
  // Inject data from Controller
  const instructors = {!! json_encode($instructors) !!};
</script>
<script src="{{ asset('assets/elearning/client/js/instruktur.js') }}"></script>

@endsection
