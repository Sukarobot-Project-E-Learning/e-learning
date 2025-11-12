@extends('client.layout.main')

@section('body')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/dashboard.css') }}">
<script defer src="{{ asset('assets/elearning/client/js/dashboard.js') }}"></script>

<div class="flex min-h-screen bg-gray-50">
  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r border-gray-200 fixed h-screen flex flex-col justify-between shadow-md">
    <div>
      <nav class="mt-4">
        <ul class="space-y-1 text-gray-700">
          <li><button class="nav-item active rounded-lg" data-section="profile"><i class="fa-regular fa-user mr-2"></i>Profil</button></li>
          <li><button class="nav-item rounded-lg" data-section="program"><i class="fa-solid fa-book mr-2"></i>Program</button></li>
          <li><button class="nav-item rounded-lg" data-section="certificate"><i class="fa-solid fa-award mr-2"></i>Sertifikat</button></li>
          <li><button class="nav-item rounded-lg" data-section="transactions"><i class="fa-solid fa-clock-rotate-left mr-2"></i>Riwayat Transaksi</button></li>
          <li><button class="nav-item rounded-lg" data-section="voucher"><i class="fa-solid fa-ticket mr-2"></i>Voucher</button></li>
        </ul>
      </nav>
    </div>
    <div class="p-4">
      <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Keluar</button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="ml-64 flex-1 p-8 transition-all duration-300">
    <!-- Profil -->
    <section id="profile" class="section active">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Profil Pengguna</h2>
      <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex flex-col md:flex-row gap-6">
          <img src="https://via.placeholder.com/120" alt="User Avatar" class="rounded-full w-28 h-28 border-4 border-orange-400 object-cover">
          <div class="flex-1">
            <p><strong>Nama:</strong> John Doe</p>
            <p><strong>Email:</strong> john@example.com</p>
            <p><strong>Nomor HP:</strong> 08123456789</p>
            <div class="mt-4 flex gap-3">
              <button class="btn-orange">Edit Profil</button>
              <button class="btn-blue">Ubah Password</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Program -->
    <section id="program" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Program Saya</h2>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach(['Kursus','Pelatihan','Sertifikasi','Outing Class','Outboard'] as $program)
        <div class="bg-white p-5 rounded-xl shadow-md border-t-4 border-orange-500 hover:shadow-lg transition">
          <h3 class="font-semibold text-lg text-gray-800">{{ $program }}</h3>
          <p class="text-gray-500 mt-1 text-sm">Lihat detail dan progres pembelajaran Anda.</p>
          <button class="btn-blue mt-3">Lihat Program</button>
        </div>
        @endforeach
      </div>
    </section>

    <!-- Sertifikat -->
    <section id="certificate" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Sertifikat</h2>
      <div class="bg-white p-6 rounded-xl shadow-md">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-blue-50 text-blue-700">
              <th class="p-3 text-left">Nama Program</th>
              <th class="p-3 text-left">Tanggal Diterima</th>
              <th class="p-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="p-3">Kursus Web Development</td>
              <td class="p-3">12 Mei 2025</td>
              <td class="p-3"><button class="btn-orange">Download</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Riwayat Transaksi -->
    <section id="transactions" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Riwayat Transaksi</h2>
      <div class="bg-white p-6 rounded-xl shadow-md">
        <ul class="space-y-4">
          <li class="flex justify-between items-center border-b pb-3">
            <div>
              <p class="font-semibold text-gray-800">Kursus Frontend Development</p>
              <p class="text-sm text-gray-500">Transaksi ID: #123456</p>
            </div>
            <span class="text-orange-600 font-bold">Rp 250.000</span>
          </li>
        </ul>
      </div>
    </section>

    <!-- Voucher -->
    <section id="voucher" class="section hidden">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Voucher Saya</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-orange-500 to-blue-500 text-white p-6 rounded-xl shadow-lg">
          <h3 class="text-lg font-semibold">Voucher Diskon 20%</h3>
          <p class="text-sm mt-1">Berlaku hingga 31 Desember 2025</p>
          <button class="bg-white text-orange-600 mt-3 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">Gunakan</button>
        </div>
      </div>
    </section>
  </main>
</div>
@endsection