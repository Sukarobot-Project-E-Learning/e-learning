@extends('client.layout.main')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/home.css') }}">
@endsection

@section('body')

<!-- AOS Library -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<!-- SwiperJS Library -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Popup Overlay -->
  <div
    id="popup"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  >
    <div
      class="relative bg-white rounded-2xl shadow-xl overflow-hidden
             w-[85%] max-w-[280px] sm:max-w-[320px] md:max-w-[360px] lg:max-w-[400px]
             transform transition-all duration-300 scale-90"
    >
      <!-- Tombol X -->
      <button
        id="closePopup"
        class="absolute top-2 right-2 p-1.5 sm:p-2 rounded-full z-1 text-gray-700 font-bold text-lg leading-none hover:bg-gray-200"
      >
        ✕
      </button>

      <!-- Poster -->
      <img
        src="{{ asset('assets/elearning/client/img/blog1.jpeg') }}"
        alt="Poster Event"
        class="w-full h-auto rounded-b-2xl object-cover scale-100"
      />
    </div>
  </div>

 <!-- Script -->

<!-- Hero -->
<section
 class="hero pt-28 pb-16 bg-gradient-to-r from-blue-50 via-white to-orange-50 relative overflow-hidden"
>
 <div
   class="absolute top-8 sm:top-20 right-6 sm:right-20 w-40 sm:w-72 h-40 sm:h-72 bg-orange-200/30 rounded-full blur-3xl animate-pulse pointer-events-none"
 ></div>
 <div
   class="absolute -bottom-8 sm:-bottom-10 left-6 sm:left-20 w-56 sm:w-96 h-56 sm:h-96 bg-blue-200/30 rounded-full blur-3xl animate-pulse pointer-events-none"
 ></div>

 <div
   class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 items-center relative z-10"
 >
   <div
     data-aos="fade-up"
     data-aos-duration="900"
     class="text-center md:text-left"
   >
     <h1
       class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight"
     >
       <span class="block text-blue-700">Selamat Datang,</span>
       <span class="block text-orange-500">Di Dunia Robotik</span>
     </h1>

     <p
       class="mt-4 text-base sm:text-lg text-gray-600 max-w-xl mx-auto md:mx-0 leading-relaxed"
     >
       <span class="block">Mulai perjalanan upgrade skill</span>
       <span class="block"
         >bersama komunitas robotik terbesar di Indonesia.</span
       >
     </p>

     <div
       class="mt-6 flex flex-wrap justify-center md:justify-start gap-3"
       data-aos="zoom-in-up"
       data-aos-delay="150"
     >
       <a
         href="{{ url('#program') }}"
         class="px-6 py-3 bg-orange-500 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition"
         >Kategori Program</a
       >
       <a
         href="{{ url('#event') }}"
         class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition"
         >Lihat Kompetisi</a
       >
     </div>

     <div
       class="mt-8 flex flex-col sm:flex-row items-center gap-4 justify-center md:justify-start"
       data-aos="fade-up"
       data-aos-delay="200"
     >
       <div class="flex -space-x-3">
         <img
           src="https://randomuser.me/api/portraits/men/32.jpg"
           alt="user1"
           class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-md"
         />
         <img
           src="https://randomuser.me/api/portraits/women/45.jpg"
           alt="user2"
           class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-md"
         />
         <img
           src="https://randomuser.me/api/portraits/men/14.jpg"
           alt="user3"
           class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-md"
         />
         <img
           src="https://randomuser.me/api/portraits/women/25.jpg"
           alt="user4"
           class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-md"
         />
         <img
           src="https://randomuser.me/api/portraits/men/6.jpg"
           alt="user5"
           class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-md"
         />
       </div>

       <p class="text-sm text-gray-700 leading-snug mt-3 sm:mt-0">
         <span class="font-bold">180.000+ peserta</span> sudah membuktikan
         perjalanan mereka bersama Sukarobot.<br />
         <span class="text-blue-600 font-semibold"
           >Saatnya giliranmu!</span
         >
       </p>
     </div>

     <a
       href="#alumni"
       data-aos="fade-up"
       data-aos-delay="250"
       class="mt-4 inline-block text-orange-600 font-semibold hover:underline"
       >Lihat Cerita Mereka →</a
     >
   </div>
   <div
   data-aos="zoom-in-up"
   data-aos-duration="1000"
   class="flex justify-center md:justify-end"
 >
   <img
     src="{{ asset('assets/elearning/client/img/ilustrator.jpeg') }}"
     alt="Ilustrasi Robotik"
     class="w-full max-w-xs sm:max-w-md md:max-w-full h-auto max-h-[400px] object-contain transition-transform hover:scale-105"
   />
 </div>

 </div>
</section>

<!-- Carousel Section -->
<section id="carousel" class="py-16 bg-gradient-to-b from-white to-orange-50">
 <div class="max-w-7xl mx-auto px-6 text-center mb-10" data-aos="fade-up">
   <h2 class="text-3xl font-bold text-gray-800">
     🎉 Jangan Kelewatan Promo Menarik
   </h2>
   <p class="text-gray-600 mt-2">
     Temukan berbagai promo program yang kami selenggarakan.
   </p>
 </div>

 <div
   class="relative max-w-7xl mx-auto px-4 sm:px-6"
   data-aos="zoom-in-up"
 >
   <div class="swiper mySwiper rounded-2xl overflow-hidden shadow-lg">
     <div class="swiper-wrapper">
       <!-- Banner 1 -->
       <div class="swiper-slide">
         <img
         src="{{ asset('assets/elearning/client/img/banner.jpeg') }}"
           class="w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[280px] xl:h-[300px] object-cover"
           alt="Event 1"
         />
       </div>

       <!-- Banner 2 -->
       <div class="swiper-slide">
         <img
          src="{{ asset('assets/elearning/client/img/event.jpeg') }}"
           class="w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[280px] xl:h-[300px] object-cover"
           alt="Event 2"
         />
       </div>

       <!-- Banner 3 -->
       <div class="swiper-slide">
         <img
           src="{{ asset('assets/elearning/client/img/banner (2).jpeg') }}"
           class="w-full h-[220px] sm:h-[240px] md:h-[260px] lg:h-[280px] xl:h-[300px] object-cover"
           alt="Event 3"
         />
       </div>
     </div>

     <button
       class="hidden md:flex items-center justify-center absolute top-1/2 -translate-y-1/2 left-6 z-10 w-11 h-11 rounded-full border-2 border-orange-400 text-orange-400 bg-white/80 shadow-md hover:bg-gradient-to-r hover:from-orange-400 hover:to-blue-400 hover:text-white active:scale-95 transition-all duration-300 ease-out"
       id="prevBtn"
     >
       <svg
         xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="2.3"
         stroke="currentColor"
         class="w-5 h-5"
       >
         <path
           stroke-linecap="round"
           stroke-linejoin="round"
           d="M15.75 19.5L8.25 12l7.5-7.5"
         />
       </svg>
     </button>

     <!-- Tombol Navigasi Kanan -->
     <button
       class="hidden md:flex items-center justify-center absolute top-1/2 -translate-y-1/2 right-6 z-10 w-11 h-11 rounded-full border-2 border-blue-400 text-blue-400 bg-white/80 shadow-md hover:bg-gradient-to-r hover:from-blue-400 hover:to-orange-400 hover:text-white active:scale-95 transition-all duration-300 ease-out"
       id="nextBtn"
     >
       <svg
         xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="2.3"
         stroke="currentColor"
         class="w-5 h-5"
       >
         <path
           stroke-linecap="round"
           stroke-linejoin="round"
           d="M8.25 4.5l7.5 7.5-7.5 7.5"
         />
       </svg>
     </button>

     <!-- Pagination -->
     <div class="swiper-pagination mt-4"></div>
   </div>
 </div>
</section>

<!-- Kenapa -->
<section class="py-20 bg-gradient-to-r from-blue-50 to-orange-50">
    <div class="max-w-7xl mx-auto px-6 text-center mb-8" data-aos="fade-up">
      <h2 class="text-3xl font-bold text-gray-800">
        Kenapa Harus Belajar di Sukarobot?
      </h2>
      <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
        Di <span class="font-semibold text-blue-700">Sukarobot</span>, belajar bukan sekadar teori —
        tapi petualangan seru untuk menyalakan imajinasi, menantang rasa ingin tahu,
        dan menciptakan teknologi masa depan lewat pengalaman nyata dan mentor hebat
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-10 max-w-6xl mx-auto px-6">
      <div data-aos="zoom-in" data-aos-delay="100"
        class="flex gap-4 items-start p-5 rounded-xl bg-white shadow hover:shadow-md transition">
        <img src="{{ asset('assets/elearning/client/img/home4.jpeg') }}" class="w-14 sm:w-16 md:w-20 object-cover rounded" alt="Kurikulum" />
        <p class="text-gray-700">
          <span class="font-semibold text-blue-700">Kurikulum Terkini:</span>
          Belajar dengan materi yang selalu berkembang mengikuti teknologi robotik terbaru
        </p>
      </div>

      <div data-aos="zoom-in" data-aos-delay="200"
        class="flex gap-4 items-start p-5 rounded-xl bg-white shadow hover:shadow-md transition">
        <img src="{{ asset('assets/elearning/client/img/home3.jpeg') }}" class="w-14 sm:w-16 md:w-20" alt="Mentor" />
        <p class="text-gray-700">
          <span class="font-semibold text-blue-700">Mentor Profesional:</span>
          Dipandu langsung oleh para ahli dan praktisi industri robotik
        </p>
      </div>

      <div data-aos="zoom-in" data-aos-delay="300"
        class="flex gap-4 items-start p-5 rounded-xl bg-white shadow hover:shadow-md transition">
        <img src="{{ asset('assets/elearning/client/img/home5.jpeg') }}" class="w-14 sm:w-16 md:w-20" alt="Komunitas" />
        <p class="text-gray-700">
          <span class="font-semibold text-blue-700">Komunitas Solid:</span>
          Tumbuh dan belajar bersama ribuan pelajar kreatif dari seluruh Indonesia
        </p>
      </div>

      <div data-aos="zoom-in" data-aos-delay="400"
        class="flex gap-4 items-start p-5 rounded-xl bg-white shadow hover:shadow-md transition">
        <img src="{{ asset('assets/elearning/client/img/home1.jpeg') }}" class="w-14 sm:w-16 md:w-20" alt="Proyek" />
        <p class="text-gray-700">
          <span class="font-semibold text-blue-700">Proyek Nyata:</span>
          Bangun robot impianmu lewat proyek langsung yang seru dan menantang
        </p>
      </div>
    </div>
  </section>



  <!-- Program Section -->
  <section id="program" class="py-20 bg-gradient-to-b from-white to-orange-50 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 relative z-10">
      <!-- Category cards (kursus / pelatihan / sertifikasi / outing class / outboard) -->
       
      <div class="max-w-7xl mx-auto px-6 mb-8" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 text-center mb-8" data-aos="fade-up">
          <h2 class="text-3xl font-bold text-gray-800">
            Apa yang Ingin Anda Pelajari?
          </h2>
          <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
            Pilih kategori di bawah ini untuk mempeajari semua program
          </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-6">
          <!-- Card 1 -->
          <a href="{{ asset('/elearning/kelas#filter=kursus') }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
            <div class="space-y-2">
              <h3 class="text-lg md:text-xl font-extrabold text-gray-900">Kursus</h3>
              <p class="text-gray-600 text-sm leading-relaxed">Kegiatan pembelajaran terstruktur yang fokus pada penguasaan pengetahuan atau keterampilan tertentu dalam jangka waktu tertentu</p>
              <img src="{{ asset('assets/elearning/client/img/home4.jpeg') }}" alt="Bootcamp" class="w-32 h-32 mx-auto mt-4 object-contain rounded-md" />
            </div>
          </a>

          <!-- Card 2 -->
          <a href="{{ asset('/elearning/kelas#filter=pelatihan') }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
            <div class="space-y-2">
              <h3 class="text-lg md:text-xl font-extrabold text-gray-900">Pelatihan</h3>
              <p class="text-gray-600 text-sm leading-relaxed">Kegiatan untuk meningkatkan keterampilan praktis (terutama yang digunakan dalam pekerjaan)</p>
              <img src="{{ asset('assets/elearning/client/img/home3.jpeg') }}" alt="Kursus" class="w-32 h-32 mx-auto mt-4 object-contain rounded-md" />
            </div>
          </a>

          <!-- Card 3 -->
          <a href="{{ asset('/elearning/kelas#filter=sertifikasi') }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
            <div class="space-y-2">
              <h3 class="text-lg md:text-xl font-extrabold text-gray-900">Sertifikasi</h3>
              <p class="text-gray-600 text-sm leading-relaxed">Proses penilaian kompetensi untuk membuktikan seseorang memenuhi standar tertentu dalam bidang tertentu</p>
              <img src="{{ asset('assets/elearning/client/img/home5.jpeg') }}" alt="Corporate" class="w-32 h-32 mx-auto mt-4 object-contain rounded-md" />
            </div>
          </a>

          <!-- Card 4 -->
          <a href="{{ asset('/elearning/kelas#filter=outingclass') }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
            <div class="space-y-2">
              <h3 class="text-lg md:text-xl font-extrabold text-gray-900">Outing Class</h3>
              <p class="text-gray-600 text-sm leading-relaxed">Kegiatan pembelajaran di luar kelas yang bertujuan memperluas wawasan dengan pengalaman langsung</p>
              <img src="{{ asset('assets/elearning/client/img/home1.jpeg') }}" alt="CDHX" class="w-32 h-32 mx-auto mt-4 object-contain rounded-md" />
            </div>
          </a>

          <!-- Card 5 -->
          <a href="{{ asset('/elearning/kelas#filter=outboard') }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition p-6">
            <div class="space-y-2">
              <h3 class="text-lg md:text-xl font-extrabold text-gray-900">Outboard</h3>
              <p class="text-gray-600 text-sm leading-relaxed">Kegiatan pengembangan diri dan tim (team building) yang dilakukan di luar ruangan dengan permainan dan simulasi</p>
              <img src="{{ asset('assets/elearning/client/img/home2.jpeg') }}" alt="Millennial" class="w-32 h-32 mx-auto mt-4 object-contain rounded-md" />
            </div>
          </a>
          </a>
        </div>
      </div>

      <div class="mt-14 text-center" data-aos="zoom-in" data-aos-delay="200">
          <a
            href="{{ url('/elearning/kelas') }}"
            class="inline-block px-8 py-3 bg-orange-500 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition duration-300"
          >
            Lihat Program
          </a>
        </div>
      </div>
      </div>    
    </div>
  </section>


  <!-- EVENT SECTION -->
<section
id="event"
class="py-20 bg-gradient-to-r from-orange-50 via-white to-blue-50 relative overflow-hidden"
>
<!-- Elemen dekoratif blur -->
<div
  class="absolute top-10 left-10 w-56 sm:w-80 h-56 sm:h-80 bg-blue-200/30 rounded-full blur-3xl animate-pulse pointer-events-none"
></div>
<div
  class="absolute bottom-0 right-8 w-72 sm:w-96 h-72 sm:h-96 bg-orange-200/30 rounded-full blur-3xl animate-pulse pointer-events-none"
></div>

<div
  class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10"
>
  <!-- Kiri: Gambar -->
  <div
    data-aos="zoom-in-up"
    data-aos-duration="900"
    class="flex justify-center md:justify-start"
  >
    <img
      src="{{ asset('assets/elearning/client/img/posterevent.jpeg') }}"
      alt="Poster Event Robotik"
      class="w-full max-w-xs sm:max-w-md md:max-w-full h-auto rounded-2xl shadow-xl object-cover hover:scale-105 transition-transform duration-500"
    />
  </div>

  <!-- Kanan: Teks -->
  <div
    data-aos="fade-left"
    data-aos-duration="1000"
    class="text-center md:text-left"
  >
    <h2
      class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight text-blue-700"
    >
      <span class="block text-orange-500">Kompetisi Robotik</span>
      <span class="block">Terdekat & Terpopuler</span>
    </h2>

    <p
      class="mt-4 text-base sm:text-lg text-gray-600 max-w-xl mx-auto md:mx-0 leading-relaxed"
    >
      Bergabunglah dengan kompetisi robotik terkini!
      Temukan inovasi, kolaborasi, dan inspirasi dari para ahli teknologi di seluruh Indonesia.
    </p>

    <div
      class="mt-6 flex flex-wrap justify-center md:justify-start gap-3"
      data-aos="zoom-in-up"
      data-aos-delay="150"
    >
      <a
        href="{{ url('https://brc.sukarobot.com/') }}"
        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition"
        >BRC</a
      >
      <a
        href="{{ url('https://src.sukarobot.com/') }}"
        class="px-6 py-3 bg-orange-500 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition"
        >SRC</a
      >
    </div>

    <!-- Statistik mini -->
    <div
      class="mt-10 flex flex-col sm:flex-row items-center gap-6 justify-center md:justify-start"
      data-aos="fade-up"
      data-aos-delay="200"
    >
      <div class="text-center">
        <h3 class="text-4xl font-bold text-orange-500">250+</h3>
        <p class="text-gray-600 text-sm">Event diselenggarakan</p>
      </div>
      <div class="text-center">
        <h3 class="text-4xl font-bold text-blue-600">30K+</h3>
        <p class="text-gray-600 text-sm">Peserta bergabung</p>
      </div>
      <div class="text-center">
        <h3 class="text-4xl font-bold text-gray-800">100+</h3>
        <p class="text-gray-600 text-sm">Universitas berpartisipasi</p>
      </div>
    </div>
  </div>
</div>
</section>



<!-- INSTRUKTUR SECTION -->
<!-- INSTRUKTUR SECTION -->
<section
  id="instructor"
  class="py-20 bg-gradient-to-r from-blue-50 via-white to-orange-50 relative overflow-hidden"
>
  <!-- Dekoratif blur -->
  <div
    class="absolute top-0 right-10 w-56 sm:w-80 h-56 sm:h-80 bg-orange-200/30 rounded-full blur-3xl animate-pulse pointer-events-none"
  ></div>
  <div
    class="absolute bottom-0 left-10 w-72 sm:w-96 h-72 sm:h-96 bg-blue-200/30 rounded-full blur-3xl animate-pulse pointer-events-none"
  ></div>

  <div class="max-w-7xl mx-auto px-6 relative z-10">
    <!-- Judul -->
    <div class="text-center mb-14" data-aos="fade-up" data-aos-duration="800">
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-blue-700">
        <span class="text-orange-500">Instruktur & Pengajar</span> Terbaik Kami
      </h2>
      <p class="mt-4 text-gray-600 max-w-2xl mx-auto text-base sm:text-lg leading-relaxed">
        Belajar langsung dari para ahli robotika, programmer, dan pendidik profesional yang berpengalaman
        di berbagai kompetisi dan proyek teknologi nasional.
      </p>
    </div>

    <!-- Grid Instruktur -->
    <div
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-10"
      data-aos="fade-up"
      data-aos-delay="150"
    >
      <!-- Card 1 -->
      <div
        class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition p-6 text-center hover:scale-105 duration-500"
      >
        <img
          src="https://randomuser.me/api/portraits/men/32.jpg"
          alt="Instruktur"
          class="w-24 h-24 mx-auto rounded-full object-cover mb-4 border-4 border-blue-500 shadow-md"
        />
        <h3 class="text-xl font-bold text-gray-800">Budi Santoso</h3>
        <p class="text-orange-500 font-semibold text-sm mt-1">Ahli Robotika</p>
        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
          Mentor nasional dengan pengalaman 8+ tahun dalam pengembangan sistem robot pintar dan otomasi.
        </p>
      </div>

      <!-- Card 2 -->
      <div
        class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition p-6 text-center hover:scale-105 duration-500"
      >
        <img
          src="https://randomuser.me/api/portraits/women/45.jpg"
          alt="Instruktur"
          class="w-24 h-24 mx-auto rounded-full object-cover mb-4 border-4 border-blue-500 shadow-md"
        />
        <h3 class="text-xl font-bold text-gray-800">Siti Ramadhani</h3>
        <p class="text-orange-500 font-semibold text-sm mt-1">Programmer AI</p>
        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
          Spesialis kecerdasan buatan dan pembelajaran mesin yang aktif dalam penelitian AI di bidang robotik.
        </p>
      </div>

      <!-- Card 3 -->
      <div
        class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition p-6 text-center hover:scale-105 duration-500"
      >
        <img
          src="https://randomuser.me/api/portraits/men/15.jpg"
          alt="Instruktur"
          class="w-24 h-24 mx-auto rounded-full object-cover mb-4 border-4 border-blue-500 shadow-md"
        />
        <h3 class="text-xl font-bold text-gray-800">Rizal Akbar</h3>
        <p class="text-orange-500 font-semibold text-sm mt-1">Software Engineer</p>
        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
          Berpengalaman dalam pembuatan sistem otomasi berbasis IoT dan kontrol mikroprosesor.
        </p>
      </div>

      <!-- Card 4 -->
      <div
        class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition p-6 text-center hover:scale-105 duration-500"
      >
        <img
          src="https://randomuser.me/api/portraits/women/25.jpg"
          alt="Instruktur"
          class="w-24 h-24 mx-auto rounded-full object-cover mb-4 border-4 border-blue-600 shadow-md"
        />
        <h3 class="text-xl font-bold text-gray-800">Dewi Anggraini</h3>
        <p class="text-orange-500 font-semibold text-sm mt-1">Desainer Robot</p>
        <p class="mt-3 text-gray-600 text-sm leading-relaxed">
          Desainer sistem mekanik dan pengembang robot humanoid yang kreatif dan inovatif.
        </p>
      </div>
    </div>

    <!-- Tombol View More -->
    <div class="mt-14 text-center" data-aos="zoom-in" data-aos-delay="200">
      <a
        href="{{ url('/instructor') }}"
        class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-orange-500 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition duration-300"
      >
        View More →
      </a>
    </div>
  </div>
</section>








<!-- Cerita Alumni -->
<section id="alumni" class="relative py-20 bg-white overflow-visible">
 <div
   class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-6 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14 items-center"
 >
   <!-- Teks -->
   <div data-aos="fade-up" class="text-center md:text-left">
     <h2 class="text-3xl md:text-4xl font-bold text-blue-700 mb-3">
       Cerita Alumni
     </h2>
     <p
       class="text-gray-600 text-base md:text-lg leading-relaxed max-w-lg mx-auto md:mx-0"
     >
       Dengarkan pengalaman nyata mereka yang sudah belajar bersama
       Sukarobot.
     </p>
   </div>

   <!-- Slider -->
   <div data-aos="zoom-in-up" class="flex justify-center md:justify-end">
     <div
       class="swiper alumniSwiper w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl"
     >
       <div class="swiper-wrapper">
         <div
           class="swiper-slide w-full p-6 bg-gray-50 rounded-2xl shadow-lg hover:shadow-xl transition"
         >
           <p class="text-gray-700 text-sm md:text-base leading-relaxed">
             "Belajar di Sukarobot benar-benar membuka peluang karir saya
             di bidang AI dan Robotics."
           </p>
           <div class="flex items-center gap-4 mt-4">
             <img
               src="https://randomuser.me/api/portraits/women/65.jpg"
               class="w-12 h-12 rounded-full ring-2 ring-orange-400"
             />
             <span class="font-semibold text-gray-800"
               >Dewi – Engineer</span
             >
           </div>
         </div>

         <div
           class="swiper-slide w-full p-6 bg-gray-50 rounded-2xl shadow-lg hover:shadow-xl transition"
         >
           <p class="text-gray-700 text-sm md:text-base leading-relaxed">
             "Mentor sangat profesional dan materinya up-to-date. Sangat
             direkomendasikan!"
           </p>
           <div class="flex items-center gap-4 mt-4">
             <img
               src="https://randomuser.me/api/portraits/men/41.jpg"
               class="w-12 h-12 rounded-full ring-2 ring-blue-400"
             />
             <span class="font-semibold text-gray-800"
               >Rizky – Developer</span
             >
           </div>
         </div>

         <div
           class="swiper-slide w-full p-6 bg-gray-50 rounded-2xl shadow-lg hover:shadow-xl transition"
         >
           <p class="text-gray-700 text-sm md:text-base leading-relaxed">
             "Komunitasnya keren banget, banyak relasi yang saya dapatkan."
           </p>
           <div class="flex items-center gap-4 mt-4">
             <img
               src="https://randomuser.me/api/portraits/women/33.jpg"
               class="w-12 h-12 rounded-full ring-2 ring-orange-400"
             />
             <span class="font-semibold text-gray-800"
               >Sinta – Researcher</span
             >
           </div>
         </div>
       </div>
       <div class="swiper-pagination mt-4"></div>
     </div>
   </div>
 </div>
</section>

<section id="partner">
 <div class="text-center mb-8">
   <h2 class="text-3xl font-bold text-gray-800">Partner Kami</h2>
 </div>

 <div class="partner-wrapper overflow-hidden relative space-y-8">
   <!-- Baris 1 (ke kiri) -->
   <div class="partner-track flex items-center track-left">
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/6/62/Tesla_Motors_Logo.svg"
       class="partner-logo"
       alt="Tesla"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg"
       class="partner-logo"
       alt="Google"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/4/48/Microsoft_logo.svg"
       class="partner-logo"
       alt="Microsoft"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/a/ab/Apple-logo.png"
       class="partner-logo"
       alt="Apple"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/9/96/OpenAI_Logo.svg"
       class="partner-logo"
       alt="OpenAI"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/f/fa/IBM_logo.svg"
       class="partner-logo"
       alt="IBM"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/2/20/Nvidia_logo.svg"
       class="partner-logo"
       alt="NVIDIA"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/0/05/Intel_logo_%282020%2B%29.svg"
       class="partner-logo"
       alt="Intel"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/c/c9/Meta_Platforms_Inc._logo.svg"
       class="partner-logo"
       alt="Meta"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/0/08/Samsung_logo.svg"
       class="partner-logo"
       alt="Samsung"
     />
   </div>

   <!-- Baris 2 (ke kanan) -->
   <div class="partner-track flex items-center track-right">
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/4/44/Tesla_logo.png"
       class="partner-logo"
       alt="Tesla"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg"
       class="partner-logo"
       alt="Google"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/4/48/Microsoft_logo.svg"
       class="partner-logo"
       alt="Microsoft"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/a/ab/Apple-logo.png"
       class="partner-logo"
       alt="Apple"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/9/96/OpenAI_Logo.svg"
       class="partner-logo"
       alt="OpenAI"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/f/fa/IBM_logo.svg"
       class="partner-logo"
       alt="IBM"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/2/20/Nvidia_logo.svg"
       class="partner-logo"
       alt="NVIDIA"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/0/05/Intel_logo_%282020%2B%29.svg"
       class="partner-logo"
       alt="Intel"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/c/c9/Meta_Platforms_Inc._logo.svg"
       class="partner-logo"
       alt="Meta"
     />
     <img
       src="https://upload.wikimedia.org/wikipedia/commons/0/08/Samsung_logo.svg"
       class="partner-logo"
       alt="Samsung"
     />
   </div>
 </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-white overflow-hidden">
 <div
   class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-[1fr_0.9fr] gap-3 md:gap-4 items-center"
 >
   <!-- Kolom Kiri: Teks & Foto -->
   <div data-aos="fade-up" class="text-center md:text-left">
     <h2 class="text-3xl font-bold text-gray-800">
       Mau Tanya Langsung Ke Kami?
     </h2>
     <p class="mt-3 text-gray-600">Yuk isi data di bawah ini!</p>

     <!-- Foto -->
     <div class="mt-5 flex justify-center md:justify-start">
       <img
         src="{{ asset('assets/elearning/client/img/eventilustrator.jpeg') }}"
         alt="Ilustrasi Tanya"
         class="w-56 sm:w-64 md:w-72 rounded-xl select-none"
       />
     </div>
   </div>

   <!-- Kolom Kanan: Formulir -->
   <div data-aos="zoom-in-up" class="flex justify-center md:justify-end">
     <form
       class="bg-white border border-gray-200 p-6 sm:p-7 rounded-xl space-y-4 w-full max-w-sm md:max-w-md shadow-sm"
     >
       <input
         type="text"
         placeholder="Nama"
         class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
       />
       <input
         type="email"
         placeholder="Email"
         class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
       />
       <input
         type="text"
         placeholder="Nomor HP"
         class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
       />
       <textarea
         rows="4"
         placeholder="Pertanyaan"
         class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
       ></textarea>
       <button
         class="w-full py-3 bg-gradient-to-r from-orange-500 to-blue-600 text-white font-semibold rounded-lg hover:opacity-90 transition"
       >
         Send
       </button>
     </form>
   </div>
 </div>
</section>

<script src="{{ asset('assets/elearning/client/js/home.js') }}"></script>
@endsection



