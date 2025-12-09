@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Riwayat Transaksi -->
    <section id="transactions" class="section">
      <h2 class="text-2xl font-bold text-blue-700 mb-4">Riwayat Transaksi</h2>
      
      @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
          {{ session('warning') }}
        </div>
      @endif

      @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
          {{ session('info') }}
        </div>
      @endif

      <div class="bg-white p-6 rounded-xl shadow-md">
        @if($transactions->isEmpty())
          <div class="text-center py-8">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-gray-500">Belum ada transaksi</p>
            <a href="{{ route('client.program.semua-kelas') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
              Lihat Program
            </a>
          </div>
        @else
          <ul class="space-y-4">
            @foreach($transactions as $transaction)
              <li class="border rounded-lg p-4 {{ $transaction->status === 'pending' && !$transaction->isExpired() ? 'border-yellow-300 bg-yellow-50' : '' }}">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                  <!-- Left: Program Info -->
                  <div class="flex-1">
                    <div class="flex items-start gap-3">
                      @if($transaction->program && $transaction->program->image)
                        <img src="{{ asset($transaction->program->image) }}" alt="{{ $transaction->program->program ?? 'Program' }}" class="w-16 h-16 rounded-lg object-cover">
                      @else
                        <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                        </div>
                      @endif
                      <div>
                        <p class="font-semibold text-gray-800">{{ $transaction->program->program ?? 'Program Dihapus' }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->transaction_code }}</p>
                        <p class="text-xs text-gray-400">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Right: Price and Status -->
                  <div class="text-right flex flex-col items-end gap-2">
                    <span class="text-orange-600 font-bold text-lg">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                    
                    <!-- Status Badge -->
                    @php
                      $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'cancelled' => 'bg-gray-100 text-gray-800',
                        'expired' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-blue-100 text-blue-800',
                      ];
                      $statusColor = $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                      {{ $transaction->getStatusLabel() }}
                    </span>

                    <!-- Countdown for pending transactions -->
                    @if($transaction->status === 'pending' && !$transaction->isExpired())
                      <div class="text-xs text-yellow-600">
                        <span>Sisa waktu: </span>
                        <span class="font-semibold countdown-timer" data-expires="{{ $transaction->expires_at->timestamp }}">
                          {{ $transaction->getRemainingTimeFormatted() }}
                        </span>
                      </div>
                      
                      <!-- Resume Payment Button -->
                      <button 
                        type="button"
                        class="mt-2 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition text-sm font-semibold resume-payment-btn"
                        data-transaction-id="{{ $transaction->id }}"
                      >
                        Bayar Sekarang
                      </button>
                    @endif

                    <!-- Success info -->
                    @if($transaction->status === 'paid')
                      <p class="text-xs text-green-600">
                        Dibayar: {{ $transaction->payment_date ? $transaction->payment_date->format('d M Y, H:i') : '-' }}
                      </p>
                    @endif
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </section>

    <!-- Midtrans Snap.js for resume payment -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Resume payment buttons
        document.querySelectorAll('.resume-payment-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            const button = this;
            
            button.disabled = true;
            button.innerHTML = '<span class="animate-spin inline-block mr-1">⏳</span> Memproses...';

            fetch('/api/payment/resume/' + transactionId, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.error) {
                alert('Error: ' + data.error);
                button.disabled = false;
                button.innerHTML = 'Bayar Sekarang';
                return;
              }

              snap.pay(data.snap_token, {
                onSuccess: function(result) {
                  window.location.href = '{{ route("client.payment.finish") }}?order_id=' + result.order_id + '&status=success';
                },
                onPending: function(result) {
                  window.location.reload();
                },
                onError: function(result) {
                  window.location.reload();
                },
                onClose: function() {
                  button.disabled = false;
                  button.innerHTML = 'Bayar Sekarang';
                }
              });
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Terjadi kesalahan. Silakan coba lagi.');
              button.disabled = false;
              button.innerHTML = 'Bayar Sekarang';
            });
          });
        });

        // Countdown timers
        function updateCountdowns() {
          document.querySelectorAll('.countdown-timer').forEach(function(timer) {
            const expiresAt = parseInt(timer.getAttribute('data-expires')) * 1000;
            const now = Date.now();
            const remaining = Math.max(0, expiresAt - now);

            if (remaining <= 0) {
              timer.textContent = 'Kadaluarsa';
              timer.closest('li').classList.remove('border-yellow-300', 'bg-yellow-50');
              // Reload page after a short delay
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
          });
        }

        // Update countdown every second
        setInterval(updateCountdowns, 1000);
      });
    </script>

    <style>
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
      .animate-spin {
        animation: spin 1s linear infinite;
      }
    </style>
@endsection
