@extends('client.layouts.dashboard')

@section('dashboard-content')
    <!-- Voucher -->
    <section id="voucher" class="section">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($vouchers as $voucher)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 relative overflow-hidden group hover:shadow-md transition-all duration-300 flex flex-col justify-between">
          <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">
            Tersedia
          </div>
          <div>
            <div class="flex items-center gap-4 mb-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-xl">
                @if($voucher->discount_type == 'percentage')
                  %
                @else
                  Rp
                @endif
              </div>
              <div>
                <h3 class="font-bold text-gray-900">
                  @if($voucher->discount_type == 'percentage')
                    Diskon {{ $voucher->discount_value }}%
                  @else
                    Potongan Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                  @endif
                </h3>
                <p class="text-xs text-gray-500">{{ $voucher->name }}</p>
                @if($voucher->end_date)
                  <p class="text-xs text-red-500 font-semibold mt-1">Berakhir: {{ \Carbon\Carbon::parse($voucher->end_date)->format('d M Y') }}</p>
                @endif
              </div>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 flex justify-between items-center border border-dashed border-gray-300 mb-4 cursor-pointer hover:bg-gray-100 transition" onclick="copyVoucherCode('{{ $voucher->code }}', this)">
              <span class="font-mono font-bold text-gray-700 tracking-wider">{{ $voucher->code }}</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
          <a href="{{ route('client.program') }}" class="block text-center w-full py-2 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors mt-auto">
            Gunakan Sekarang
          </a>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
          </svg>
          <h3 class="text-lg font-bold text-gray-700">Belum Ada Voucher</h3>
          <p class="text-gray-500 mt-2">Nantikan promo menarik dari kami selanjutnya!</p>
        </div>
        @endforelse
      </div>
    </section>

    <!-- Script for copy clipboard -->
    <script>
      function copyVoucherCode(code, element) {
        navigator.clipboard.writeText(code).then(function() {
          const icon = element.querySelector('.copy-icon');
          const originalHTML = icon.innerHTML;
          // Change to checkmark
          icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
          icon.classList.remove('text-gray-400');
          icon.classList.add('text-green-500');
          
          // Toast notification or visual cue
          const tooltip = document.createElement('div');
          tooltip.className = 'absolute bg-gray-800 text-white text-xs px-2 py-1 rounded -top-8 left-1/2 transform -translate-x-1/2 transition-opacity duration-300';
          tooltip.innerText = 'Tersalin!';
          element.style.position = 'relative';
          element.appendChild(tooltip);

          setTimeout(() => {
            icon.innerHTML = originalHTML;
            icon.classList.add('text-gray-400');
            icon.classList.remove('text-green-500');
            tooltip.remove();
          }, 2000);
        });
      }
    </script>

@endsection
