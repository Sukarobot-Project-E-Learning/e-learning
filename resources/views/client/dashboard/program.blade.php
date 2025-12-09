<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
              <button class="px-4 py-2 text-sm font-medium rounded-full bg-blue-500 text-white whitespace-nowrap snap-start shrink-0">Semua Program</button>
              <button class="px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0">Kursus</button>
              <button class="px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0">Pelatihan</button>
              <button class="px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0">Sertifikasi</button>
              <button class="px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0">Outing Class</button>
              <button class="px-4 py-2 text-sm font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 whitespace-nowrap snap-start shrink-0">Outboard</button>
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

      <script>
          document.addEventListener('DOMContentLoaded', () => {
              const scrollContainer = document.getElementById('program-nav');
              const leftBtn = document.getElementById('scroll-left');
              const rightBtn = document.getElementById('scroll-right');

              if(scrollContainer && leftBtn && rightBtn) {
                  const checkScroll = () => {
                      // Show/hide left button
                      if (scrollContainer.scrollLeft > 0) {
                          leftBtn.classList.remove('opacity-0', 'pointer-events-none');
                          leftBtn.classList.remove('hidden'); // ensure it's visible if we removed hidden class manually
                      } else {
                          leftBtn.classList.add('opacity-0', 'pointer-events-none');
                      }

                      // Show/hide right button
                      if (scrollContainer.scrollLeft < (scrollContainer.scrollWidth - scrollContainer.clientWidth - 10)) {
                          rightBtn.classList.remove('opacity-0', 'pointer-events-none');
                      } else {
                          rightBtn.classList.add('opacity-0', 'pointer-events-none');
                      }
                  };

                  leftBtn.addEventListener('click', () => {
                      scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
                  });

                  rightBtn.addEventListener('click', () => {
                      scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
                  });

                  scrollContainer.addEventListener('scroll', checkScroll);
                  
                  // Initial check
                  checkScroll();
                  // Also check on resize
                  window.addEventListener('resize', checkScroll);
              }
          });
      </script>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="flex flex-col rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
            <div class="w-full bg-center bg-no-repeat aspect-[16/9] bg-cover" data-alt="Abstract graphic design elements" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCEJSQL2WBpLKYVxdc66LfZFXWcBZ4tFSK2YLrw4b9sdXkFl-9gYbt97SdL_uoiwb4FOYkbMml8NbErMXrFFw3nJGik1qzXyaRXkDU_ccXAIhr71b6tE-azDFOVrZJuzFXVpTqUTKDOaHa-REhNqYNnmTrNAp0qtswvW7aH5BRjtGh6SbqPrpl1Q2Lj8vTE-HSlAVI4AEfkX3H7kqq3p73g958N7sXnwCw0Jr2dZsMskU0S6dKdG94V0CA3FpAu5QngCQxvIRSTU7s");'></div>
            <span class="absolute top-3 left-3 bg-yellow-400/20 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">Kursus</span>
            </div>
            <div class="flex flex-col p-5 gap-4 flex-1">
              <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">Dasar-dasar Desain Grafis dengan Figma</h2>
              <p class="text-gray-500 text-sm font-normal leading-normal">Dibeli pada: 15 Okt 2023</p>
              <div class="w-full">
              <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-600">Progres</span>
                <span class="text-sm font-medium text-primary">75%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-orange-500 h-2 rounded-full" style="width: 75%"></div>
              </div>
              </div>
              <button class="mt-auto flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-orange-500 text-white text-sm font-medium leading-normal hover:bg-orange-600 focus:ring-4 focus:ring-primary/30">
              <span class="truncate">Lanjutkan Belajar</span>
              </button>
            </div>
          </div>
          <div class="flex flex-col rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-lg transition-shadow duration-300">
          <div class="relative">
          <div class="w-full bg-center bg-no-repeat aspect-[16/9] bg-cover" data-alt="People collaborating around a table" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC8Gtm3Pa4CMSXP81AuBjbYpl0Ih3j7KPFCufGVliiH02zWLsM6gCOjDpsxv7Jg3kirPg9zLdKJTLPdjq0Y0J1vJ7asGBgHiq8uF02v4D55KrMQuDaXBnWOOghuLX-51KcpCBePEp8_PoGugahjG9MoLyXLx_2o-ufqmpsJ1gGrptAmyA1ytywyKuIBqj9jr7Nl8NaVuVt0B5w2_BGkNhquCexZbreBFSJ2opwc63XDB85hAQbqcJV_MgjGyq7bn8nlC8RuKXs6cB4");'></div>
          <span class="absolute top-3 left-3 bg-purple-400/20 text-purple-800 text-xs font-semibold px-2.5 py-1 rounded-full">Pelatihan</span>
          </div>
            <div class="flex flex-col p-5 gap-4 flex-1">
            <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">Manajemen Proyek Agile untuk Pemula</h2>
            <p class="text-gray-500 text-sm font-normal leading-normal">Dibeli pada: 12 Sep 2023</p>
            <div class="w-full">
              <div class="flex justify-between mb-1">
                <span class="text-sm font-medium text-gray-600">Progres</span>
                <span class="text-sm font-medium text-primary">40%</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-orange-500 h-2 rounded-full" style="width: 40%"></div>
              </div>
            </div>
            <button class="mt-auto flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-orange-500 text-white text-sm font-medium leading-normal hover:bg-orange-600 focus:ring-4 focus:ring-primary/30">
            <span class="truncate">Lanjutkan Belajar</span>
            </button>
            </div>
          </div>
            <div class="flex flex-col rounded-xl overflow-hidden shadow-sm bg-white border border-gray-200 hover:shadow-lg transition-shadow duration-300">
            <div class="relative">  
              <div class="w-full bg-center bg-no-repeat aspect-[16/9] bg-cover" data-alt="Illustration of a certificate with a seal" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCxlEy-QNmZuN4IkQii4igq5qMoK-qPbVtADXDas6HAuJ2KvICP9bj9oOvt6WGgDdt89TVzP8TJfIAplMnjfm_dE9WDrglNNH4DNjghuBwah2qCcGtDP5Zq34edOfyFzlqfb4Q3gWsV_0tuhOlmMPugb9ApANNOet9Ct0bAn7AJLkpbcYUZ23RmMRiP2wyCPlsdtIkQuHATR6TEHF0Lg41ouOeQnT-vI9UqGKU4WeDYpt_uhCE0Iy6IyHYFRaioYcss1-nR-LUOnlA");'></div>
              <span class="absolute top-3 left-3 bg-green-400/20 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Sertifikasi</span>
            </div>
            <div class="flex flex-col p-5 gap-4 flex-1">
              <h2 class="text-gray-900 text-lg font-bold leading-tight tracking-[-0.015em]">Sertifikasi Digital Marketing Profesional</h2>
              <p class="text-gray-500 text-sm font-normal leading-normal">Dibeli pada: 05 Agu 2023</p>
              <div class="w-full">
                <div class="flex justify-between mb-1">
                  <span class="text-sm font-medium text-gray-600">Progres</span>
                  <span class="text-sm font-medium text-green-600">100% Selesai</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                </div>
              </div>
              <button class="mt-auto flex w-full min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-500 text-white text-sm font-medium leading-normal hover:bg-gray-600 focus:ring-4 focus:ring-gray-300">
              <span class="truncate">Lihat Detail</span>
              </button>
            </div>
          </div>
      </div>
      <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0 mt-10 pt-6">
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
      </nav>
    </section>
@endsection
