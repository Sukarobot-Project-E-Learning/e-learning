@if(isset($recommendedVouchers) && $recommendedVouchers->count() > 0 && !$isPurchased)
  <div class="mb-8">
    <div class="flex items-center gap-2 mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z" clip-rule="evenodd" />
      </svg>
      <h3 class="font-bold text-gray-900">Promo Spesial Untukmu</h3>
    </div>
    <div class="space-y-3">
      @foreach($recommendedVouchers as $voucher)
        <div class="border border-orange-200 bg-orange-50/50 rounded-xl p-3 flex justify-between items-center group relative overflow-hidden transition hover:bg-orange-50">
          <div class="z-10 relative">
            <p class="font-bold text-orange-700 text-sm">
              @if($voucher->discount_type == 'percentage')
                Diskon {{ $voucher->discount_value }}%
              @else
                Potongan Rp{{ number_format($voucher->discount_value, 0, ',', '.') }}
              @endif
            </p>
            <p class="text-xs text-orange-600/80 font-mono font-bold mt-1 tracking-wider">{{ $voucher->code }}</p>
          </div>
          <button onclick="copyVoucherCode('{{ $voucher->code }}', this)" class="z-10 bg-white border border-orange-200 text-orange-600 hover:bg-orange-100 hover:text-orange-700 text-xs px-3 py-1.5 rounded-lg font-bold transition flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            Salin
          </button>
        </div>
      @endforeach
    </div>
    <script>
      if (typeof copyVoucherCode !== 'function') {
        function copyVoucherCode(code, element) {
          navigator.clipboard.writeText(code).then(function() {
            const icon = element.querySelector('.copy-icon');
            if (icon) {
              const originalHTML = icon.outerHTML;
              icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
              element.classList.remove('text-orange-600', 'border-orange-200');
              element.classList.add('text-green-600', 'border-green-300', 'bg-green-50');
              element.innerHTML = icon.outerHTML + 'Tersalin!';

              setTimeout(() => {
                element.classList.add('text-orange-600', 'border-orange-200');
                element.classList.remove('text-green-600', 'border-green-300', 'bg-green-50');
                element.innerHTML = originalHTML + 'Salin';
              }, 2000);
            }
          });
        }
      }
    </script>
  </div>
@endif
