@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Voucher -->
    <section id="voucher" class="section">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
          <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">
            Terbatas
          </div>
          <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-xl">
              %
            </div>
            <div>
              <h3 class="font-bold text-gray-900">Diskon 50%</h3>
              <p class="text-xs text-gray-500">Untuk pendaftar pertama</p>
            </div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3 flex justify-between items-center border border-dashed border-gray-300 mb-4">
            <span class="font-mono font-bold text-gray-700">ASLKDNNA123</span>
            <!-- <span class="text-xs text-gray-400 italic">Klaim untuk lihat</span> -->
          </div>
          <button class="w-full py-2 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors">
            Gunakan Sekarang
          </button>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
          <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">
            Terbatas
          </div>
          <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-xl">
              %
            </div>
            <div>
              <h3 class="font-bold text-gray-900">Diskon 50%</h3>
              <p class="text-xs text-gray-500">Untuk pendaftar pertama</p>
            </div>
          </div>
          <div class="bg-gray-50 rounded-xl p-3 flex justify-between items-center border border-dashed border-gray-300 mb-4">
            <span class="font-mono font-bold text-gray-700">QKDNALS123</span>
            <!-- <span class="text-xs text-gray-400 italic">Klaim untuk lihat</span> -->
          </div>
          <button class="w-full py-2 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors">
            Gunakan Sekarang
          </button>
        </div>
      </div>
    </section>

@endsection
