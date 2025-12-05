@extends('client.main')
@section('body')
<main class="pt-24 pb-24 min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    
    @if($type === 'success')
    <!-- Success -->
    <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
      <div class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
      <p class="text-gray-600 mb-6">{{ $message }}</p>
      
      <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Kode Transaksi</span>
          <span class="font-semibold">{{ $transaction->transaction_code }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Total Bayar</span>
          <span class="font-semibold">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Metode</span>
          <span class="font-semibold capitalize">{{ $transaction->payment_method }}</span>
        </div>
      </div>

      <div class="flex gap-3">
        <a href="{{ route('client.dashboard') }}" 
          class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
          Lihat Dashboard
        </a>
        <a href="{{ route('client.home') }}" 
          class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
          Kembali ke Home
        </a>
      </div>
    </div>

    @elseif($type === 'info')
    <!-- Pending -->
    <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
      <div class="w-20 h-20 mx-auto mb-4 bg-yellow-100 rounded-full flex items-center justify-center">
        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Diproses</h1>
      <p class="text-gray-600 mb-6">{{ $message }}</p>
      
      <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Kode Transaksi</span>
          <span class="font-semibold">{{ $transaction->transaction_code }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Total Bayar</span>
          <span class="font-semibold">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Status</span>
          <span class="font-semibold text-yellow-600">Menunggu Pembayaran</span>
        </div>
      </div>

      <a href="{{ route('client.home') }}" 
        class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
        Kembali ke Home
      </a>
    </div>

    @else
    <!-- Failed/Error -->
    <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
      <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Gagal</h1>
      <p class="text-gray-600 mb-6">{{ $message }}</p>
      
      <div class="flex gap-3">
        <a href="{{ route('client.pembayaran', $transaction->program->slug ?? '#') }}" 
          class="flex-1 bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 transition font-semibold">
          Coba Lagi
        </a>
        <a href="{{ route('client.home') }}" 
          class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
          Kembali ke Home
        </a>
      </div>
    </div>
    @endif

  </div>
</main>
@endsection
