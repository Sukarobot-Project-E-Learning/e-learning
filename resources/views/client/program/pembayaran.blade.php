@extends('client.main')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program/pembayaran.css') }}">
@endsection
@section('body')
<!-- Konten Utama -->
<main>
  <div class="pt-24 pb-24 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Kiri: Program Info -->
    <div>
      <img src="{{ asset($program->image ?? 'client/img/placeholder.jpg') }}" alt="{{ $program->program }}"
        class="rounded-xl shadow-md mb-4 w-full object-cover h-64">
      <h1 class="text-2xl sm:text-3xl font-bold mb-2 text-gray-900 leading-snug">
        {{ $program->program }}
      </h1>
      <p class="text-gray-600 mb-4 text-sm sm:text-base">
        {{ $program->description }}
      </p>

      <!-- Jadwal -->
      <div class="rounded-lg p-4 shadow-sm mb-4 bg-gray-50">
        <p class="text-sm font-semibold text-gray-600 mb-2">Jadwal</p>
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <span class="text-blue-600 text-lg">📅</span>
          <span>{{ date('d M Y', strtotime($program->start_date)) }} - {{ date('d M Y', strtotime($program->end_date)) }}</span>
        </div>
        <div class="flex items-center gap-2 text-sm sm:text-base mt-2">
          <span class="text-blue-600 text-lg">🕐</span>
          <span>{{ date('H:i', strtotime($program->start_time)) }} - {{ date('H:i', strtotime($program->end_time)) }} WIB</span>
        </div>
      </div>

      <!-- Instructor -->
      <div class="rounded-lg p-4 shadow-sm bg-gray-50">
        <p class="text-sm font-semibold text-gray-600 mb-2">Instruktur</p>
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
            {{ strtoupper(substr($program->instructor_name ?? 'I', 0, 1)) }}
          </div>
          <div>
            <p class="font-bold text-gray-900">{{ $program->instructor_name ?? 'Sukarobot Team' }}</p>
            <p class="text-sm text-gray-500">Instructor</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Kanan: Payment Details -->
    <div class="rounded-lg p-6 shadow-sm bg-white border border-gray-200">

      <h2 class="text-lg sm:text-xl font-semibold mb-4">Detail Pembayaran</h2>

      <!-- Metode Pembayaran -->
      <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex items-center gap-3">
          <img src="https://media.licdn.com/dms/image/C510BAQF0STaxnpVW6w/company-logo_200_200/0?e=2159024400&v=beta&t=yMtwE7LXLn6KWLtNlnARrjsT61JKKuEnFhWImxznRmk"
            alt="Midtrans" class="h-10 w-10 rounded-full object-cover">
          <div>
            <p class="font-semibold text-gray-900">Midtrans Payment Gateway</p>
            <p class="text-sm text-gray-600">Berbagai metode pembayaran tersedia</p>
          </div>
        </div>
      </div>

      <!-- Detail Harga -->
      <div class="rounded-lg p-4 bg-gray-50 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Rincian Harga</h3>
        <div class="flex justify-between text-sm mb-1">
          <span>Harga Kelas</span>
          <span>Rp{{ number_format($program->price, 0, ',', '.') }}</span>
        </div>
        @php
        $discount = 0; // You can add discount logic here
        $tax = 0;
        $total = $program->price - $discount + $tax;
        @endphp
        @if($discount > 0)
        <div class="flex justify-between text-sm mb-1">
          <span>Diskon</span>
          <span class="text-green-600">- Rp{{ number_format($discount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="flex justify-between text-sm mb-1">
          <span>Pajak</span>
          <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold text-gray-900 border-t pt-2 mt-2">
          <span>Total</span>
          <span class="text-orange-600 text-lg">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>
      </div>

      <button id="pay-button"
        class="w-full mt-6 bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition font-semibold shadow-md hover:shadow-lg">
        Bayar Sekarang
      </button>

      <p class="text-xs text-gray-500 text-center mt-3">
        Dengan melakukan pembayaran, Anda menyetujui <a href="#" class="text-blue-600 underline">syarat & ketentuan</a> kami
      </p>
    </div>
  </div>
</main>

<!-- Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function() {
    const button = this;
    button.disabled = true;
    button.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> Memproses...';

    fetch('{{ route("client.payment.create") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            program_id: {{ $program->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            button.disabled = false;
            button.innerHTML = 'Bayar Sekarang';
            return;
        }

        // Open Midtrans Snap popup
        snap.pay(data.snap_token, {
            onSuccess: function(result) {
                console.log('Payment success:', result);
                window.location.href = '{{ route("client.payment.finish") }}?order_id=' + result.order_id + '&status=success';
            },
            onPending: function(result) {
                console.log('Payment pending:', result);
                window.location.href = '{{ route("client.payment.finish") }}?order_id=' + result.order_id + '&status=pending';
            },
            onError: function(result) {
                console.log('Payment error:', result);
                window.location.href = '{{ route("client.payment.finish") }}?order_id=' + result.order_id + '&status=error';
            },
            onClose: function() {
                console.log('Payment popup closed');
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
