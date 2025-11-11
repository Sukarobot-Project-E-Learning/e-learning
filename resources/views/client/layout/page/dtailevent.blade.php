@extends('client.layout.main')
@section('css')
<style>
  /* Animasi Fade Up */
  .fade-up {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s ease-out;
    }
    .fade-up.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* Poster Hover */
    .poster-hover:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease;
    }

    /* Tombol Hover */
    .btn-hover:hover {
      transform: scale(1.05);
    }
</style>
@endsection

@section('body')

<!-- Hero Poster -->
<section class="relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
    <div class="max-w-7xl mx-auto px-6 py-16 lg:py-24 grid lg:grid-cols-2 gap-10 items-center">
      <div class="space-y-6">
        <h2 class="text-4xl lg:text-5xl font-bold leading-tight">SKILL BOOSTER: Strategi Iklan Digital</h2>
        <p class="text-lg text-blue-100">Kuasi strategi kampanye iklan digital yang kreatif, efektif, dan terukur. Belajar langsung bersama expert, dapatkan sertifikat resmi.</p>
        <div class="flex space-x-4">
          <button class="border border-white text-white px-6 py-3 rounded-xl font-medium hover:text-blue-600 hover:bg-white  transition">Ikuti Event</button>
        </div>
      </div>
      <div class="relative">
        <img src="{{ asset('assets/elearning/client/img/Kita ke Sana - Hindia.jpeg') }}" alt="Poster Event" class="rounded-2xl shadow-xl">
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-3 gap-12">

    <!-- Event Description -->
    <div class="lg:col-span-2 space-y-8 fade-up">
      <div class="space-y-4">
        <h3 class="text-2xl font-bold text-gray-900">Tentang Event</h3>
        <div class="flex flex-wrap gap-2 mb-2">
          <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm">Coding</span>
          <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-semibold text-sm">Online</span>
          <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold text-sm">Beginner</span>
        </div>
        <p class="text-gray-600 leading-relaxed">
          Event ini membahas dasar-dasar pemrograman Python untuk membangun proyek mini interaktif.
          Peserta akan belajar langsung membuat aplikasi sederhana dan robotik berbasis coding.
        </p>
        <p class="text-gray-600 leading-relaxed">
          Setiap peserta akan dibimbing mentor berpengalaman, mengikuti sesi praktikum, dan mendapatkan sertifikat penyelesaian.
        </p>
      </div>

      <!-- Jadwal + Kuota + Level -->
      <div class="rounded-3xl p-6 bg-white shadow-lg border border-gray-100 space-y-4 fade-up">
        <h4 class="font-semibold text-xl text-gray-800 flex items-center"><i class="ph-calendar-blank-bold mr-2 text-blue-600"></i> Jadwal & Durasi</h4>
        <div class="flex justify-between text-gray-700 text-lg">
          <span>📅 08 - 10 Oktober 2025</span>
          <span>⏰ 09:00 - 16:00 WIB</span>
        </div>

        <!-- Sisa Slot -->
        <div class="pt-4 border-t space-y-2">
          <div class="flex justify-between mb-2 text-sm font-medium text-gray-600">
            <span>Sisa Slot</span>
            <span id="quota-text">25/100</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-3">
            <div id="quota-bar" class="bg-blue-600 h-3 rounded-full transition-all" style="width:25%"></div>
          </div>
        </div>

        <!-- Level Kelas -->
        <div class="pt-4 border-t flex items-center gap-2 text-sm text-gray-600">
          <i class="ph-lightning-bold text-yellow-500"></i> Level: Beginner
        </div>
      </div>
    </div>

    <!-- Sidebar: Harga & Benefit -->
    <div class="rounded-3xl shadow-lg bg-white p-6 flex flex-col items-center text-center hover:shadow-2xl transition fade-up">
      <img src="{{ asset('assets/elearning/client/img/Kita ke Sana - Hindia.jpeg') }}" alt="Event" class="rounded-xl mb-6 shadow-lg poster-hover">
      <h4 class="text-3xl font-semibold text-gray-800 mb-2">Rp.390.000</h4>

      <button onclick="window.location.href='{{ url('/pembayaran') }}'"
      class="btn-hover bg-gradient-to-r from-blue-500 to-blue-600 w-full text-white py-3 rounded-xl font-medium mb-3 shadow hover:scale-105 transition">
 Ikuti Event
</button>

      <button class="border border-blue-600 w-full text-blue-600 py-3 rounded-xl font-medium hover:bg-blue-50 transition">Tukar Voucher</button>

      <!-- Benefit List -->
      <div class="mt-8 w-full text-left space-y-2">
        <h5 class="font-semibold text-gray-800 mb-3">Yang Kamu Dapatkan</h5>
        <ul class="space-y-3 text-sm text-gray-700">
          <li class="flex items-center fade-up"><i class="ph-check-circle text-green-500 mr-2 text-lg"></i> Sertifikat Penyelesaian</li>
          <li class="flex items-center fade-up" style="transition-delay: 100ms"><i class="ph-check-circle text-green-500 mr-2 text-lg"></i> Materi Praktik</li>
          <li class="flex items-center fade-up" style="transition-delay: 200ms"><i class="ph-check-circle text-green-500 mr-2 text-lg"></i> Panduan Tugas & Proyek</li>
        </ul>
      </div>
    </div>
  </div>

<script>

let total = 100, sisa = 25;
    const quotaText = document.getElementById("quota-text");
    const quotaBar = document.getElementById("quota-bar");
    function updateQuota(newSisa){
      sisa = newSisa;
      const persen = (sisa/total)*100;
      quotaText.textContent = `${sisa}/${total}`;
      quotaBar.style.width = persen+"%";
    }
    setInterval(()=>{ if(sisa>0) updateQuota(sisa-1); },3000);

    // Animasi scroll fade-up
    const faders = document.querySelectorAll('.fade-up');
    const appearOptions = { threshold: 0.2 };
    const appearOnScroll = new IntersectionObserver((entries, observer)=>{
      entries.forEach(entry=>{
        if(entry.isIntersecting){
          entry.target.classList.add('show');
          observer.unobserve(entry.target);
        }
      });
    }, appearOptions);
    faders.forEach(fader => appearOnScroll.observe(fader));


</script>
@endsection
