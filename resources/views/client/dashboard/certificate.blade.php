@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Sertifikat -->
    <section id="certificate" class="section">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Sertifikat</h2>
      
      @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
      @endif

      @if(count($certificates) > 0)
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
                @foreach($certificates as $certificate)
                <tr class="border-t">
                  <td class="p-3">{{ $certificate->program_name }}</td>
                  <td class="p-3">{{ \Carbon\Carbon::parse($certificate->issued_at)->locale('id')->isoFormat('D MMMM Y') }}</td>
                  <td class="p-3">
                      <a href="{{ route('client.dashboard.certificate.download', $certificate->id) }}" class="btn-orange inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white font-medium hover:bg-orange-600 transition-colors">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                          </svg>
                          Download
                      </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Mobile View (Cards) -->
          <div class="md:hidden space-y-4">
            @foreach($certificates as $certificate)
            <!-- Card Item -->
            <div class="bg-white p-5 rounded-xl shadow-md border border-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">{{ $certificate->program_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Diterima: {{ \Carbon\Carbon::parse($certificate->issued_at)->locale('id')->isoFormat('D MMMM Y') }}</p>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('client.dashboard.certificate.download', $certificate->id) }}" class="btn-orange w-full justify-center flex items-center gap-2 px-4 py-2 rounded-lg text-white font-medium hover:bg-orange-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Sertifikat
                </a>
            </div>
            @endforeach
          </div>
      @else
          <div class="bg-white p-8 rounded-xl shadow-md text-center">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900">Belum Ada Sertifikat</h3>
              <p class="text-gray-500 mt-2">Anda belum memiliki sertifikat. Selesaikan program dan tunggu persetujuan bukti program untuk mendapatkan sertifikat.</p>
          </div>
      @endif
    </section>
@endsection
