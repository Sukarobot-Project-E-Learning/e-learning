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
          <span class="text-gray-600">Program</span>
          <span class="font-semibold">{{ $transaction->program->program ?? 'Program' }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Total Bayar</span>
          <span class="font-semibold text-green-600">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Metode</span>
          <span class="font-semibold capitalize">{{ $transaction->payment_method }}</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Status</span>
          <span class="font-semibold text-green-600">Berhasil</span>
        </div>
      </div>

      <div class="flex gap-3">
        <a href="{{ route('client.dashboard.program') }}" 
          class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold text-center">
          Lihat Program Saya
        </a>
        <a href="{{ route('client.home') }}" 
          class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 transition font-semibold text-center">
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
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h1>
      <p class="text-gray-600 mb-4">{{ $message }}</p>

      <!-- Countdown Timer -->
      @if($transaction->expires_at && !$transaction->isExpired())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
          <p class="text-sm text-yellow-700 mb-2">Selesaikan pembayaran dalam:</p>
          <p class="text-2xl font-bold text-yellow-600 countdown-timer" data-expires="{{ $transaction->expires_at->timestamp }}">
            {{ $transaction->getRemainingTimeFormatted() }}
          </p>
          <p class="text-xs text-yellow-600 mt-2">Setelah waktu habis, transaksi akan otomatis dibatalkan</p>
        </div>
      @endif
      
      <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Kode Transaksi</span>
          <span class="font-semibold">{{ $transaction->transaction_code }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Program</span>
          <span class="font-semibold">{{ $transaction->program->program ?? 'Program' }}</span>
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

      <div class="flex flex-col gap-3">
        <a href="{{ route('client.dashboard.transaction') }}" 
          class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition font-semibold text-center">
          Lihat Riwayat Transaksi
        </a>
        <a href="{{ route('client.home') }}" 
          class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 transition font-semibold text-center">
          Kembali ke Home
        </a>
      </div>

      <p class="text-xs text-gray-500 mt-4">
        Anda dapat melanjutkan pembayaran melalui halaman Riwayat Transaksi
      </p>
    </div>

    @else
    <!-- Failed/Error/Expired -->
    <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
      <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mb-2">
        {{ $transaction->status === 'expired' ? 'Transaksi Kadaluarsa' : 'Pembayaran Gagal' }}
      </h1>
      <p class="text-gray-600 mb-6">{{ $message }}</p>
      
      <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Kode Transaksi</span>
          <span class="font-semibold">{{ $transaction->transaction_code }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
          <span class="text-gray-600">Program</span>
          <span class="font-semibold">{{ $transaction->program->program ?? 'Program' }}</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-600">Status</span>
          <span class="font-semibold text-red-600">{{ $transaction->getStatusLabel() }}</span>
        </div>
      </div>

      <div class="flex gap-3">
        @if($transaction->program && $transaction->program->slug)
        <a href="{{ route('client.pembayaran', $transaction->program->slug) }}" 
          class="flex-1 bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 transition font-semibold text-center">
          Beli Lagi
        </a>
        @endif
        <a href="{{ route('client.home') }}" 
          class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300 transition font-semibold text-center">
          Kembali ke Home
        </a>
      </div>
    </div>
    @endif

  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const timer = document.querySelector('.countdown-timer');
  if (!timer) return;

  function updateCountdown() {
    const expiresAt = parseInt(timer.getAttribute('data-expires')) * 1000;
    const now = Date.now();
    const remaining = Math.max(0, expiresAt - now);

    if (remaining <= 0) {
      timer.textContent = 'Kadaluarsa';
      setTimeout(() => window.location.reload(), 1000);
      return;
    }

    const hours = Math.floor(remaining / (1000 * 60 * 60));
    const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

    if (hours > 0) {
      timer.textContent = hours + ' jam ' + minutes + ' menit ' + seconds + ' detik';
    } else if (minutes > 0) {
      timer.textContent = minutes + ' menit ' + seconds + ' detik';
    } else {
      timer.textContent = seconds + ' detik';
    }
  }

  setInterval(updateCountdown, 1000);
});
</script>
@endsection
