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
      @php
          $pembayaranImageUrl = ($program->image && str_starts_with($program->image, 'images/'))
              ? asset($program->image) 
              : ($program->image ? asset('storage/' . $program->image) : asset('client/img/placeholder.jpg'));
      @endphp
      <img src="{{ $pembayaranImageUrl }}" alt="{{ $program->program }}"
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

      <!-- Rekomendasi Voucher -->
      @if(isset($recommendedVouchers) && $recommendedVouchers->count() > 0)
      <div class="mb-5 bg-orange-50/50 border border-orange-100 rounded-xl p-4">
        <label class="block text-sm font-semibold text-orange-900 mb-3 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd" />
          </svg>
          Promo Tersedia
        </label>
        <div class="space-y-2.5">
          @foreach($recommendedVouchers as $voucher)
          <div onclick="applyRecommendedVoucher('{{ $voucher->code }}')" class="cursor-pointer border border-orange-200 bg-white rounded-lg p-3 flex justify-between items-center hover:border-orange-400 hover:shadow-sm transition group">
             <div>
               <p class="font-bold text-orange-700 text-sm">
                 @if($voucher->discount_type == 'percentage')
                   Diskon {{ $voucher->discount_value }}%
                 @else
                   Potongan Rp{{ number_format($voucher->discount_value, 0, ',', '.') }}
                 @endif
               </p>
               <p class="text-xs text-gray-500 mt-0.5">{{ \Illuminate\Support\Str::limit($voucher->name, 40) }}</p>
             </div>
             <span class="text-xs font-bold text-orange-600 bg-orange-50 border border-orange-200 px-3 py-1.5 rounded-lg group-hover:bg-orange-500 group-hover:text-white transition-colors">Gunakan</span>
          </div>
          @endforeach
        </div>
      </div>
      <script>
        function applyRecommendedVoucher(code) {
           document.getElementById('voucher_code').value = code;
           
           // Highlight input shortly
           const input = document.getElementById('voucher_code');
           input.classList.add('ring-2', 'ring-orange-500', 'border-orange-500');
           setTimeout(() => {
              input.classList.remove('ring-2', 'ring-orange-500', 'border-orange-500');
           }, 800);
           
           document.getElementById('apply-voucher').click();
        }
      </script>
      @endif

      <!-- Voucher Input -->
      <div class="mb-4">
        <label for="voucher_code" class="block text-sm font-semibold text-gray-700 mb-2">Kode Voucher</label>
        <div class="flex gap-2">
          <input type="text" id="voucher_code" placeholder="Masukkan kode voucher" class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none uppercase text-sm">
          <button type="button" id="apply-voucher" class="px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition w-auto flex-shrink-0">Terapkan</button>
        </div>
        <p id="voucher-message" class="text-xs mt-2 hidden"></p>
      </div>

      <!-- Detail Harga -->
      <div class="rounded-lg p-4 bg-gray-50 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Rincian Harga</h3>
        <div class="flex justify-between text-sm mb-1">
          <span>Harga Kelas</span>
          <span>Rp{{ number_format($program->price, 0, ',', '.') }}</span>
        </div>
        @php
        $tax = 0;
        $total = $program->price + $tax;
        @endphp
        
        <div id="discount-row" class="flex justify-between text-sm mb-1 hidden">
          <span>Diskon <span id="voucher-label" class="font-semibold text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded ml-1"></span></span>
          <span class="text-green-600" id="discount-amount">- Rp0</span>
        </div>
        
        <div class="flex justify-between text-sm mb-1">
          <span>Pajak</span>
          <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold text-gray-900 border-t pt-2 mt-2">
          <span>Total</span>
          <span class="text-orange-600 text-lg" id="final-total" data-original="{{ $total }}">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>
      </div>

      <button id="pay-button" data-voucher=""
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
// Format Rupiah helper
function formatRupiah(number) {
    return 'Rp' + new Intl.NumberFormat('id-ID').format(number);
}

// Apply Voucher Logic
document.getElementById('apply-voucher').addEventListener('click', function() {
    const code = document.getElementById('voucher_code').value.trim();
    const messageEl = document.getElementById('voucher-message');
    const discountRow = document.getElementById('discount-row');
    const discountAmountEl = document.getElementById('discount-amount');
    const finalTotalEl = document.getElementById('final-total');
    const voucherLabelEl = document.getElementById('voucher-label');
    const payButton = document.getElementById('pay-button');
    const originalTotal = parseFloat(finalTotalEl.dataset.original);

    if (!code) {
        messageEl.textContent = 'Silakan masukkan kode voucher';
        messageEl.className = 'text-xs mt-2 text-red-500 block';
        return;
    }

    const button = this;
    button.disabled = true;
    button.innerHTML = '...';

    fetch('{{ route("client.payment.apply-voucher") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            voucher_code: code,
            program_id: {{ $program->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.innerHTML = 'Terapkan';

        if (data.error) {
            messageEl.textContent = data.error;
            messageEl.className = 'text-xs mt-2 text-red-500 block';
            
            // Reset discount
            discountRow.classList.add('hidden');
            finalTotalEl.textContent = formatRupiah(originalTotal);
            payButton.dataset.voucher = '';
            
            return;
        }

        // Apply Success
        messageEl.textContent = 'Voucher "' + data.voucher_name + '" berhasil diterapkan!';
        messageEl.className = 'text-xs mt-2 text-green-600 block';
        
        discountRow.classList.remove('hidden');
        voucherLabelEl.textContent = code;
        discountAmountEl.textContent = '- ' + formatRupiah(data.discount_amount);
        
        // Ensure tax logic remains if you change tax calculation, here max(0, price - discount + tax)
        const newTotal = Math.max(0, originalTotal - data.discount_amount);
        finalTotalEl.textContent = formatRupiah(newTotal);
        
        // Store applied voucher to pass to createTransaction API
        payButton.dataset.voucher = code;
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = 'Terapkan';
        messageEl.textContent = 'Terjadi kesalahan jaringan, silakan coba lagi';
        messageEl.className = 'text-xs mt-2 text-red-500 block';
    });
});

document.getElementById('pay-button').addEventListener('click', function() {
    const button = this;
    const appliedVoucher = button.dataset.voucher;

    button.disabled = true;
    button.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> Memproses...';

    fetch('{{ route("client.payment.create") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            program_id: {{ $program->id }},
            voucher_code: appliedVoucher
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
