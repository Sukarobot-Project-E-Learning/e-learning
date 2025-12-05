@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Sertifikat -->
    <section id="certificate" class="section">
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
@endsection
