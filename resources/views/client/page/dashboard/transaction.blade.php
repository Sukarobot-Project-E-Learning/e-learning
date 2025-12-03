@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Riwayat Transaksi -->
    <section id="transactions" class="section">
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
@endsection
