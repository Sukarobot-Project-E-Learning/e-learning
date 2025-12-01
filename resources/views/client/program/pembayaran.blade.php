@extends('client.main')
@section('css')
<link rel="stylesheet" href="{{ 'client/css/pembayaran.css' }}">
@endsection
@section('body')
<!-- Konten Utama -->
<main>
  <div class="pt-8 pb-24 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Kiri -->
    <div>
      <img src="{{ ('client/img/Kita ke Sana - Hindia.jpeg') }}" alt="Poster Kelas"
        class="rounded-xl shadow-md mb-4 w-full object-cover">
      <h1 class="text-2xl sm:text-3xl font-bold mb-2 text-gray-900 leading-snug">
        SKILL BOOSTER: Kuasai Strategi Iklan Digital
      </h1>
      <p class="text-gray-600 mb-4 text-sm sm:text-base">
        Webinar intensif untuk meningkatkan skill digital marketing dengan strategi iklan efektif.
      </p>

      <!-- Jadwal -->
      <div class="rounded-lg p-4 shadow-sm mb-4">
        <p class="text-sm font-semibold text-gray-600 mb-2">Jadwal</p>
        <div class="flex items-center gap-2 text-sm sm:text-base">
          <span class="text-blue-600 text-lg">📅</span>
          <span>08 - 10 Oktober 2025, 19:00 WIB</span>
        </div>
      </div>

      <!-- Status Pembayaran -->
      <div id="paymentStatus"></div>
    </div>

    <!-- Kanan -->
    <div class="rounded-lg p-6 shadow-sm">

      <h2 class="text-lg sm:text-xl font-semibold mb-4">Pilih Metode Pembayaran</h2>
      <div class="space-y-3">

        <!-- Midtrans -->
        <label
          class="flex items-center gap-3 p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition text-sm sm:text-base">
          <input type="radio" name="payment" value="Midtrans" class="payment-radio focus:outline-none">
          <img
            src="https://media.licdn.com/dms/image/C510BAQF0STaxnpVW6w/company-logo_200_200/0?e=2159024400&v=beta&t=yMtwE7LXLn6KWLtNlnARrjsT61JKKuEnFhWImxznRmk"
            alt="Midtrans" class="h-10 w-10 rounded-full object-cover">
          <span>Midtrans</span>
        </label>

        <!-- Bank -->
        <div class="p-3 rounded-lg">
          <label class="flex items-center gap-3 cursor-pointer text-sm sm:text-base">
            <input type="radio" name="payment" value="Bank" class="payment-radio focus:outline-none">
            <img src="{{ asset('assets/elearning/client/img/1.jpeg') }}" class="w-6">
            <span>BSI</span>
          </label>
          <div id="bankOptions" class="hidden mt-3 space-y-2">

            <label class="block p-2 rounded-md cursor-pointer hover:bg-blue-50 transition">
              <input type="radio" name="bank" value="BCA|1234567890|PT. Contoh Digital" class="hidden peer">
              <div
                class="peer-checked:bg-blue-50 p-2 rounded flex gap-2 items-center">
                <img src="{{ asset('assets/elearning/client/img/1.jpeg') }}" class="w-8">
                <div>
                  <p class="font-semibold">BSI</p>
                  <p class="text-xs text-gray-600">1234567890 a.n PT. Contoh Digital</p>
                </div>
              </div>
            </label>

            <label class="block p-2 rounded-md cursor-pointer hover:bg-blue-50 transition">
              <input type="radio" name="bank" value="Mandiri|9876543210|PT. Contoh Digital" class="hidden peer">
              <div
                class="peer-checked:bg-blue-50 p-2 rounded flex gap-2 items-center">
                <img src="{{ asset('assets/elearning/client/img/DANA-scaled.jpeg') }}" class="w-8">
                <div>
                  <p class="font-semibold">Dana</p>
                  <p class="text-xs text-gray-600">9876543210 a.n PT. Contoh Digital</p>
                </div>
              </div>
            </label>

          </div>
        </div>

        <!-- Cash -->
        <label
          class="flex items-center gap-3 p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition text-sm sm:text-base">
          <input type="radio" name="payment" value="Cash" class="payment-radio focus:outline-none">
          <img src="https://cdn-icons-png.flaticon.com/512/2331/2331941.png" class="w-6">
          <span>Cash</span>
        </label>

      </div>

      <!-- Detail -->
      <div class="mt-6 rounded-lg p-4 bg-gray-50 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Detail Pembayaran</h3>
        <div class="flex justify-between text-sm mb-1">
          <span>Harga Kelas</span>
          <span>Rp400.000</span>
        </div>
        <div class="flex justify-between text-sm mb-1">
          <span>Diskon</span>
          <span class="text-green-600">- Rp50.000</span>
        </div>
        <div class="flex justify-between text-sm mb-1">
          <span>Pajak</span>
          <span>Rp0</span>
        </div>
        <div class="flex justify-between font-bold text-gray-900 border-t pt-2 mt-2">
          <span>Total</span>
          <span class="text-orange-600 text-lg">Rp350.000</span>
        </div>
      </div>

      <button id="buyButton" onclick="openPaymentModal()"
        class="w-full mt-6 bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">
        Bayar Sekarang
      </button>

    </div>
  </div>

  <!-- Modal Konfirmasi -->
  <div id="paymentModal"
    class="hidden fixed inset-0 z-[9999] bg-black/30 backdrop-blur-md flex items-center justify-center px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl animate-popup">
      <div class="flex gap-3 items-center mb-4">
        <img src="https://via.placeholder.com/120x80" class="rounded-md">
        <div>
          <span
            class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-full inline-flex items-center gap-1">🎥 Webinar</span>
          <h3 class="text-sm font-bold mt-2 text-gray-800">SKILL BOOSTER: Kuasai Strategi Iklan Digital</h3>
        </div>
      </div>
      <div class="rounded-lg p-3 mb-3">
        <p class="text-sm font-semibold text-gray-600 mb-1">Jadwal</p>
        <div class="flex items-center gap-2 text-gray-700 text-sm sm:text-base">📅 08 - 10 Oktober 2025</div>
      </div>
      <div class="rounded-lg p-3 mb-3">
        <p class="text-sm font-semibold text-gray-600 mb-1">Metode Pembayaran</p>
        <div id="paymentInfo" class="text-sm"></div>
      </div>
      <div class="flex justify-between items-center border-t pt-3">
        <p class="text-sm font-semibold text-gray-600">Total</p>
        <p class="font-bold text-lg text-orange-600">Rp350.000</p>
      </div>
      <div class="mt-4 flex gap-2">
        <button onclick="closePaymentModal()" class="flex-1 rounded-lg py-2 border">Kembali</button>
        <button onclick="openUploadModal()" class="flex-1 bg-orange-500 text-white rounded-lg py-2">Konfirmasi</button>
      </div>
    </div>
  </div>

  <!-- Modal Upload -->
  <div id="uploadModal" class="hidden fixed inset-0 z-[9999] bg-black/30 backdrop-blur-md flex items-center justify-center px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-lg text-center relative">

      <h3 class="text-lg font-semibold mb-1">Upload Bukti Pembayaran</h3>
      <p class="text-sm text-gray-600 mb-5">
        Silakan upload bukti pembayaran Anda di sini untuk verifikasi.
      </p>

      <div class="w-full flex flex-col items-center space-y-4">

        <label class="w-full border-2 border-dashed rounded-xl p-5 text-center cursor-pointer hover:border-orange-400 transition bg-white shadow-sm">
          <input type="file" class="hidden" id="proofUpload" accept="image/*,.pdf">
          <div class="flex flex-col items-center justify-center space-y-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v9m0-9l-3 3m3-3l3 3M12 4v4m0 0L9 5m3 3l3-3" />
            </svg>
            <span id="uploadText" class="text-gray-600 text-sm sm:text-base">
              📂 <span class="font-medium text-orange-500">Klik</span> atau
              <span class="font-medium text-blue-500">seret file</span> ke sini
            </span>
            <p class="text-xs text-gray-400">(Maksimal 5MB, format gambar/pdf)</p>
          </div>
        </label>

        <div class="w-full flex justify-start">
          <span id="fileIndicator"
            class="hidden flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-green-700 bg-green-50 rounded-full shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Bukti sudah diunggah
          </span>
        </div>
      </div>

      <button id="submitBtn" onclick="submitProof()" disabled
        class="w-full bg-orange-300 text-white py-2.5 mt-6 rounded-lg cursor-not-allowed transition hover:bg-orange-400 disabled:opacity-60">
        Submit
      </button>

      <div id="uploadState" class="mt-4 hidden flex justify-center"></div>
    </div>
  </div>

  <!-- Popup Notifikasi -->
  <div id="alertPopup" class="hidden fixed inset-0 flex items-center justify-center z-[9999] bg-black/30 backdrop-blur-md">
      <div class="bg-white shadow-lg rounded-2xl p-6 text-center max-w-sm w-full animate-fadein">
        <div class="mx-auto mb-4 w-12 h-12 border-4 border-orange-400 border-t-transparent rounded-full animate-spin"></div>
        <h3 class="text-lg font-bold text-gray-800">Mengunggah Bukti...</h3>
        <p class="text-sm text-gray-600 mt-1">Harap tunggu sebentar</p>
      </div>
    </div>

    <!-- Popup Sukses -->
    <div id="successPopup" class="hidden fixed inset-0 flex items-center justify-center z-[9999] bg-black/30 backdrop-blur-md">
      <div class="bg-white shadow-xl rounded-2xl p-6 text-center max-w-sm w-full animate-fadein">
        <div class="relative w-16 h-16 mx-auto mb-3 flex items-center justify-center">
          <svg class="w-16 h-16" viewBox="0 0 50 50">
            <circle class="text-green-500 stroke-current" cx="25" cy="25" r="22" fill="none" stroke-width="4"
              stroke-linecap="round"
              style="stroke-dasharray:138;stroke-dashoffset:138;animation:drawCircle 1.5s ease-in-out forwards;"></circle>
            <path class="text-green-500 stroke-current opacity-0" d="M15 27l6 6 14-16" fill="none" stroke-width="4"
              stroke-linecap="round" stroke-linejoin="round"
              style="stroke-dasharray:30;stroke-dashoffset:30;animation:showCheck 0.5s ease-out 1.2s forwards;"></path>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Bukti Pembayaran Dikirim!</h3>
        <p class="text-sm text-gray-600 mt-1">Menunggu konfirmasi dari admin.</p>
        <button class="mt-4 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition">
          Tutup
        </button>
      </div>
    </div>
</main>

<script src="{{ 'client/js/pembayaran.js' }}"></script>

<style>
  @keyframes popup {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }

  .animate-popup { animation: popup 0.25s ease-out; }

  #paymentModal,
  #uploadModal { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
</style>
@endsection
