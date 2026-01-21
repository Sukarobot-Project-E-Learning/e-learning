@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Sertifikat -->
    <section id="certificate" class="section">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Sertifikat</h2>
      <!-- Desktop View (Table) -->
      <div class="bg-white p-6 rounded-xl shadow-md hidden md:block">
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

      <!-- Mobile View (Cards) -->
      <div class="md:hidden space-y-4">
        <!-- Card Item -->
        <div class="bg-white p-5 rounded-xl shadow-md border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Kursus Web Development</h3>
                    <p class="text-sm text-gray-500 mt-1">Diterima: 12 Mei 2025</p>
                </div>
                <div class="bg-blue-50 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <button class="btn-orange w-full justify-center flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Sertifikat
            </button>
        </div>
      </div>
    </section>
@endsection
