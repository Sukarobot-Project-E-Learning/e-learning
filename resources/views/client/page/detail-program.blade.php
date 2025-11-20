@extends('client.main')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/detail-program.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
@endsection

@section('body')

<div class="max-w-7xl mx-auto px-4 md:px-6 py-10 grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- KONTEN KIRI -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Lokasi -->
      <div class="bg-white p-5 rounded-xl shadow">
        <span class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-full font-medium">Kelas Webinar</span>
        <p class="mt-3 text-sm text-gray-600">📍 Pertemuan Virtual via Zoom</p>
        <p class="mt-4 text-gray-700 leading-relaxed">
          Kelas ini akan membantu kamu memahami cara membuat strategi kampanye iklan digital yang kreatif dan efektif dengan fokus pada hasil yang bisa diukur...
        </p>
      </div>

      <!-- Jadwal -->
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-5 rounded-xl shadow text-white">
        <h2 class="font-semibold text-lg mb-3">📅 Jadwal Pelatihan</h2>
        <p class="text-sm">08 - 10 Oktober 2025 | 09:00 - 11:00 WIB</p>
        <div class="mt-3">
          <span class="slot-badge">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM19 11c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM8 16c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM16 16c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2z" />
            </svg>
            Sisa Slot: 50
          </span>
        </div>
      </div>

      <!-- Instruktur -->
      <div class="bg-white p-5 rounded-xl shadow flex items-center gap-4">
        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Instruktur" class="w-16 h-16 rounded-full">
        <div>
          <h3 class="font-semibold text-lg text-blue-700">Aisya Savitri</h3>
          <p class="text-sm text-gray-600">Digital Media Manager di Innocean Worldwide (APAC) | >6 Tahun Pengalaman</p>
        </div>
      </div>

      <!-- Materi dengan Dropdown -->
      <div class="bg-white p-5 rounded-xl shadow">
        <h2 class="font-semibold text-lg mb-4 text-gray-800">Materi Pembelajaran</h2>
        <div class="space-y-4">

          <!-- Hari 1 -->
          <div class="accordion border border-blue-200 rounded-lg p-4 cursor-pointer hover:bg-blue-50 transition">
            <div class="flex justify-between items-center">
              <h4 class="font-medium text-blue-700">Hari 1: Analisis SWOT</h4>
              <span class="toggle text-xl text-orange-500">+</span>
            </div>
            <div class="accordion-content mt-3 text-sm text-gray-600 space-y-3">
              <p class="text-gray-500">Durasi: 3 Jam</p>
              <p>Belajar menganalisis kekuatan, kelemahan, peluang, dan ancaman bisnis.</p>
              <div class="flex flex-col gap-4 pt-3">
                <div class="flex items-center gap-3">
                  <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="w-8">
                  <p class="text-sm font-medium text-gray-700">Pembukaan</p>
                </div>
                <div class="flex items-center gap-3">
                  <img src="https://cdn-icons-png.flaticon.com/512/2965/2965567.png" class="w-8">
                  <p class="text-sm font-medium text-gray-700">Presentasi</p>
                </div>
                <div class="flex items-center gap-3">
                  <img src="https://cdn-icons-png.flaticon.com/512/3063/3063822.png" class="w-8">
                  <p class="text-sm font-medium text-gray-700">Praktik</p>
                </div>
                <div class="flex items-center gap-3">
                  <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" class="w-8">
                  <p class="text-sm font-medium text-gray-700">Evaluasi</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Hari 2 -->
          <div class="accordion border border-blue-200 rounded-lg p-4 cursor-pointer hover:bg-blue-50 transition">
            <div class="flex justify-between items-center">
              <h4 class="font-medium text-blue-700">Hari 2: Targeting & Segmentasi</h4>
              <span class="toggle text-xl text-orange-500">+</span>
            </div>
            <div class="accordion-content mt-3 text-sm text-gray-600 space-y-3">
              <p class="text-gray-500">Durasi: 2.5 Jam</p>
              <p>Membuat segmentasi pasar dan memilih target audiens yang tepat.</p>
            </div>
          </div>

          <!-- Hari 3 -->
          <div class="accordion border border-blue-200 rounded-lg p-4 cursor-pointer hover:bg-blue-50 transition">
            <div class="flex justify-between items-center">
              <h4 class="font-medium text-blue-700">Hari 3: Optimasi & Evaluasi</h4>
              <span class="toggle text-xl text-orange-500">+</span>
            </div>
            <div class="accordion-content mt-3 text-sm text-gray-600 space-y-3">
              <p class="text-gray-500">Durasi: 3 Jam</p>
              <p>Melakukan optimasi iklan digital dan evaluasi hasil kampanye.</p>
            </div>
          </div>

        </div>
      </div>

    </div>

    <!-- KONTEN KANAN -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white p-6 rounded-xl shadow space-y-5">
        <!-- Poster -->
        <img src="sukarobot.com/source/img/Sukarobot-logo.png" alt="Poster Kelas" class="rounded-lg w-full mb-3">

        <!-- Harga -->
        <p class="text-orange-600 font-semibold text-xl">Rp 39.000</p>

        <!-- Tombol -->
        <div class="flex flex-col gap-2">
          <a href="{{ url('pembayaran') }}"><button class="w-full bg-gradient-to-r from-orange-500 to-blue-600 text-white py-2 rounded-lg font-medium hover:opacity-90 transition">Beli Kelas</button></a>
          <button class="w-full border border-blue-400 text-blue-600 py-2 rounded-lg font-medium hover:bg-blue-50 transition">Tukar Voucher Kelas</button>
        </div>

        <!-- Benefit -->
        <div>
          <h3 class="font-semibold mb-2 text-blue-700">Kamu Akan Mendapatkan:</h3>
          <div class="flex flex-wrap gap-2">
            <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg text-xs">Sertifikat</span>
            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-lg text-xs">Panduan Tugas</span>
            <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg text-xs">Akses Rekaman</span>
          </div>
        </div>

        <!-- Tools -->
        <div>
          <h3 class="font-semibold mb-3 text-blue-700">Tools yang Dipelajari</h3>
          <div id="toolsList" class="flex flex-col gap-3"></div>
        </div>
      </div>
    </div>
  </div>

<script src="{{ asset('assets/elearning/client/js/detail-program.js') }}"></script>
@endsection
