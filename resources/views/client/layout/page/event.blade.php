@extends('client.layout.main')
@section('body')

<!-- Hero Section -->
<section class="max-w-7xl mx-auto px-6 py-12 relative">
    <div class="absolute inset-0 wave -z-10 rounded-b-[3rem]"></div>
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-lg p-8 md:p-12 grid md:grid-cols-2 gap-8 items-center relative z-10">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-blue-900 leading-tight animate-slideup">
          Event <span class="text-orange-500">Robotics & Coding</span>
        </h1>
        <p class="mt-3 text-gray-600 text-lg animate-fadein">
          Ikuti event terbaru seputar teknologi, robotik, dan coding. Belajar, kolaborasi, dan inovasi di satu platform interaktif.
        </p>
        <a href="#events" class="mt-6 inline-block px-6 py-3 bg-orange-500 text-white rounded-lg shadow hover:bg-orange-600 transition animate-slideup">
          Lihat Semua Event
        </a>
      </div>
      <div class="relative w-full h-64 md:h-72 overflow-hidden rounded-xl shadow-lg animate-float">
        <div id="slider" class="flex w-full h-full transition-transform duration-700">
          <img src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}" class="w-full h-full object-cover flex-shrink-0" />
          <img src="{{ asset('assets/elearning/client/img/posterevent1.jpeg') }}" class="w-full h-full object-cover flex-shrink-0" />
          <img src="{{ asset('assets/elearning/client/img/posterevent2.jpeg') }}" class="w-full h-full object-cover flex-shrink-0" />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
      </div>
    </div>
  </section>

  <!-- Pre-Event Highlight Section -->
  <section class="max-w-7xl mx-auto px-6 py-12 -mt-10 relative">
    <div class="shape w-60 h-60 bg-orange-300 top-0 left-0 animate-float"></div>
    <div class="shape w-56 h-56 bg-blue-300 bottom-0 right-0 animate-float"></div>
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-lg p-8 md:p-12 grid md:grid-cols-2 gap-8 items-center relative z-10">
      <div class="space-y-4">
        <h2 class="text-3xl md:text-4xl font-bold text-blue-900 leading-snug animate-slideup">
          Jangan Lewatkan <span class="text-orange-500">Event Terbaru</span>
        </h2>
        <p class="text-gray-600 text-lg animate-fadein">
          Workshop, seminar, dan hackathon terbaru di bidang robotics, coding, dan AI. Upgrade skill, bertemu inovator, dan ikuti tren teknologi masa depan.
        </p>
        <a href="#events" class="inline-block px-6 py-3 bg-orange-500 text-white rounded-lg shadow hover:bg-orange-600 transition animate-slideup">
          Jelajahi Event
        </a>
      </div>
      <div class="flex justify-center md:justify-end">
        <img src="{{ asset('assets/elearning/client/img/home3.jpeg') }}" alt="Illustration" class="w-56 h-56 object-contain animate-fadein">
      </div>
    </div>
  </section>

  <!-- Filter + Event Section -->
  <section id="events" class="max-w-7xl mx-auto px-6 py-12 relative">
      <div class="shape w-40 h-40 bg-orange-100 top-10 right-10 animate-float"></div>
      <div class="shape w-32 h-32 bg-blue-100 bottom-10 left-10 animate-float"></div>

      <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10 relative z-10">
        <h2 class="text-3xl font-bold text-blue-900 mb-6 text-center animate-slideup">Jelajahi Event</h2>

    <!-- Filter -->
  <div class="w-full overflow-x-auto scrollbar-hide mb-6 animate-fadein px-4">
    <div class="flex flex-nowrap gap-3 justify-center md:justify-center items-center min-w-max pb-1 snap-x snap-mandatory">
      <button onclick="filterEvents('all')" class="flex-shrink-0 snap-start px-5 py-2 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm font-medium transition">
        Semua
      </button>
      <button onclick="filterEvents('robotic')" class="flex-shrink-0 snap-start px-5 py-2 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm font-medium transition">
        Authing Clas
      </button>
      <button onclick="filterEvents('coding')" class="flex-shrink-0 snap-start px-5 py-2 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm font-medium transition">
        Oliboard
      </button>
      <button onclick="filterEvents('ai')" class="flex-shrink-0 snap-start px-5 py-2 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm font-medium transition">
        Kompetisi
      </button>
    </div>
  </div>




        <!-- Event Grid -->
        <div id="eventGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-fr">

          <!-- Example Card -->
          <a href="{{ url('/dtail_event') }}" class="event robotic block h-full">
            <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
              <img src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}" class="w-full h-36 object-cover" />
              <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
                <div>
                  <div class="flex items-center gap-1 flex-wrap mb-1">
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Robotic</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
                  </div>
                  <h3 class="text-md md:text-lg font-semibold text-blue-900">Workshop Robotik #2</h3>
                  <p class="text-gray-500 text-sm mt-1 line-clamp-3">
                    Belajar membangun robot dari awal dengan mentor berpengalaman. Sesi praktikum langsung.
                  </p>
                </div>
              </div>
            </div>
          </a>

          <!-- Example Card -->
<a href="{{ url('/dtail_event') }}" class="event robotic block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Robotic</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Workshop Robotik #2</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Belajar membangun robot dari awal dengan mentor berpengalaman. Sesi praktikum langsung.
          </p>
        </div>
      </div>
    </div>
  </a>

  <a href="{{ url('/dtail_event') }}" class="event robotic block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent1.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Robotic</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Workshop Robotik #2</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Belajar membangun robot dari awal dengan mentor berpengalaman. Sesi praktikum langsung.
          </p>
        </div>
      </div>
    </div>
  </a>

  <a href="{{ url('/dtail_event') }}" class="event robotic block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent2.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Robotic</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Workshop Robotik #2</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Belajar membangun robot dari awal dengan mentor berpengalaman. Sesi praktikum langsung.
          </p>
        </div>
      </div>
    </div>
  </a>

  <a href="{{ url('/dtail_event') }}" class="event robotic block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Robotic</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Workshop Robotik #2</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Belajar membangun robot dari awal dengan mentor berpengalaman. Sesi praktikum langsung.
          </p>
        </div>
      </div>
    </div>
  </a>

  <a href="{{ url('/dtail_event') }}" class="event coding block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent2.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">Coding</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Hackathon Coding #1</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Kompetisi coding intensif 24 jam. Bangun solusi digital bersama tim global.
          </p>
        </div>
      </div>
    </div>
  </a>

  <a href="{{ url('/dtail_event') }}" class="event coding block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">Coding</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Hackathon Coding #1</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Kompetisi coding intensif 24 jam. Bangun solusi digital bersama tim global.
          </p>
        </div>
      </div>
    </div>
  </a>

  <a href="{{ url('/dtail_event') }}" class="event coding block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent1.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">Coding</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Online</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Hackathon Coding #1</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Kompetisi coding intensif 24 jam. Bangun solusi digital bersama tim global.
          </p>
        </div>
      </div>
    </div>
  </a>

  <a href="{{ url('/dtail_event') }}" class="event ai block h-full">
    <div class="bg-white border rounded-xl hover:shadow-2xl hover:scale-105 transition overflow-hidden h-full flex flex-col">
      <img src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}" class="w-full h-36 object-cover" />
      <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-1 flex-wrap mb-1">
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">AI</span>
            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Offline</span>
          </div>
          <h3 class="text-md md:text-lg font-semibold text-blue-900">Seminar AI #1</h3>
          <p class="text-gray-500 text-sm mt-1 line-clamp-3">
            Eksplorasi kecerdasan buatan terbaru dan dampaknya terhadap industri teknologi.
          </p>
        </div>
      </div>
    </div>
  </a>


          <!-- Tambahkan card lain sesuai kebutuhan, semua pastikan class event + kategori benar -->

        </div>

        <div class="text-center mt-6 animate-fadein">
          <button id="viewMoreBtn" class="px-8 py-3 bg-gradient-to-r from-orange-500 to-blue-600 text-white rounded-lg shadow hover:opacity-90 transition">
            View More Event
          </button>
        </div>
      </div>
    </section>

@endsection

<link rel="stylesheet" href="{{  asset('assets/elearning/client/css/event.css') }}">
<script src="{{ asset('assets/elearning/client/js/event.js') }}"></script>
