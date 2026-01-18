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

      <div class="space-y-6">
        @if($transactions->isEmpty())
          <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada transaksi</h3>
            <p class="text-gray-500 mb-8 max-w-sm mx-auto">Anda belum melakukan transaksi apapun. Mulai belajar dengan membeli program kelas.</p>
            <a href="{{ route('client.program.semua-kelas') }}" class="inline-flex items-center justify-center bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
              Jelajahi Program
            </a>
          </div>
        @else
          <div class="grid gap-4">
            @foreach($transactions as $transaction)
              <div class="bg-white rounded-2xl p-5 border border-gray-200 hover:border-blue-500/30 transition-all duration-300 shadow-sm hover:shadow-md group">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">
                  <!-- Left: Program Info -->
                  <div class="flex-1">
                    <div class="flex items-center gap-5">
                      @if($transaction->program && $transaction->program->image)
                        @php
                            $transactionImageUrl = str_starts_with($transaction->program->image, 'images/') 
                                ? asset($transaction->program->image) 
                                : asset('storage/' . $transaction->program->image);
                        @endphp
                        <img src="{{ $transactionImageUrl }}" alt="{{ $transaction->program->program ?? 'Program' }}" class="w-20 h-20 rounded-xl object-cover shadow-sm">
                      @else
                        <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center">
                          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                        </div>
                      @endif
                      <div>
                        <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">{{ $transaction->program->program ?? 'Program Dihapus' }}</h3>
                        <div class="flex items-center gap-3 text-sm text-gray-500 mb-1">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                {{ $transaction->transaction_code }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Right: Price and Status -->
                  <div class="flex flex-col md:items-end gap-3 min-w-[200px]">
                    <div class="text-right">
                        <span class="text-2xl font-bold text-gray-900">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex items-center gap-3 justify-end w-full">
                        <!-- Status Badge -->
                        @php
                          $statusStyles = [
                            'pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                            'paid' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                            'failed' => 'bg-red-50 text-red-600 border border-red-200',
                            'cancelled' => 'bg-gray-50 text-gray-600 border border-gray-200',
                            'expired' => 'bg-red-50 text-red-600 border border-red-200',
                            'refunded' => 'bg-blue-50 text-blue-600 border border-blue-200',
                          ];
                          $statusStyle = $statusStyles[$transaction->status] ?? 'bg-gray-50 text-gray-600 border border-gray-200';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyle }}">
                          {{ $transaction->getStatusLabel() }}
                        </span>
                    </div>

                    <!-- Countdown for pending transactions -->
                    @if($transaction->status === 'pending' && !$transaction->isExpired())
                      <div class="flex flex-col items-end gap-2 w-full mt-2">
                        <div class="flex items-center gap-2 text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-100">
                            <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-medium">Batas Waktu:</span>
                            <span class="text-xs font-bold countdown-timer" data-expires="{{ $transaction->expires_at->timestamp }}">
                            {{ $transaction->getRemainingTimeFormatted() }}
                            </span>
                        </div>
                        
                        <button 
                            type="button"
                            class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-6 py-2.5 rounded-xl transition-all shadow-lg shadow-orange-500/30 text-sm font-semibold resume-payment-btn flex items-center justify-center gap-2"
                            data-transaction-id="{{ $transaction->id }}"
                        >
                            <span>Bayar Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>

                        <!-- Tombol Batalkan Transaksi -->
                        <button 
                            type="button"
                            class="w-full bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-700 px-4 py-2 rounded-lg transition-all text-xs font-semibold cancel-payment-btn flex items-center justify-center gap-1 border border-gray-200 hover:border-red-200"
                            data-transaction-id="{{ $transaction->id }}"
                            data-program-name="{{ $transaction->program->program ?? 'Program' }}"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>Batalkan Transaksi</span>
                        </button>
                      </div>
                    @endif

                    <!-- Success info -->
                    @if($transaction->status === 'paid')
                      <div class="flex items-center gap-1.5 text-xs text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Dibayar pada {{ $transaction->payment_date ? $transaction->payment_date->format('d M Y, H:i') : '-' }}
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
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

        // Cancel payment buttons
        document.querySelectorAll('.cancel-payment-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            const transactionId = this.getAttribute('data-transaction-id');
            const programName = this.getAttribute('data-program-name');
            const button = this;
            
            Swal.fire({
              title: 'Batalkan Transaksi?',
              html: `Apakah Anda yakin ingin membatalkan transaksi untuk <strong>"${programName}"</strong>?<br><br><span class="text-red-500 text-sm">Transaksi yang dibatalkan tidak dapat dikembalikan.</span>`,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#ef4444',
              cancelButtonColor: '#6b7280',
              confirmButtonText: 'Ya, Batalkan',
              cancelButtonText: 'Kembali',
              reverseButtons: true
            }).then((result) => {
              if (result.isConfirmed) {
                button.disabled = true;
                button.innerHTML = '<span class="animate-spin inline-block">⏳</span>';

                fetch('/api/payment/cancel/' + transactionId, {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  }
                })
                .then(response => response.json())
                .then(data => {
                  if (data.error) {
                    Swal.fire({
                      title: 'Gagal!',
                      text: data.error,
                      icon: 'error',
                      confirmButtonColor: '#3b82f6'
                    });
                    button.disabled = false;
                    button.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg><span>Batalkan Transaksi</span>';
                    return;
                  }

                  Swal.fire({
                    title: 'Berhasil!',
                    text: 'Transaksi berhasil dibatalkan.',
                    icon: 'success',
                    confirmButtonColor: '#3b82f6'
                  }).then(() => {
                    window.location.reload();
                  });
                })
                .catch(error => {
                  console.error('Error:', error);
                  Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#3b82f6'
                  });
                  button.disabled = false;
                  button.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg><span>Batalkan Transaksi</span>';
                });
              }
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
