@extends('client.layout.main')
@section('css')
<link rel="stylesheet" href="{{ asset('client/css/reset.css') }}">
@endsection

@section('body')

<!-- Reset Flow -->
<main class="flex items-center justify-center min-h-screen bg-white">
    <div id="auth-card"
         class="flex flex-col md:flex-row w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden animate-fade mx-4">

      <!-- Ilustrasi: MUNCUL di mobile & di desktop -->
      <div class="flex w-full md:w-1/2 bg-gradient-to-br from-orange-400 to-blue-600 items-center justify-center p-8 md:p-10">
        <img src="../source/img/download__1_-removebg-preview.png"
             alt="Illustration"
             class="w-48 sm:w-64 md:w-80 drop-shadow-xl rounded-xl">
      </div>

      <!-- Form Section -->
      <div class="w-full md:w-1/2 p-8 space-y-6 flex flex-col justify-center">
        <!-- Step 1: Cari akun -->
        <div id="step-1">
          <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center md:text-left">Lupa Password?</h2>
          <p class="text-gray-600 mb-6 text-sm text-center md:text-left">Masukkan email atau nomor HP yang terdaftar.</p>

          <form id="form-step-1" class="space-y-4">
            <input type="text" id="identity" name="identity" required
                   placeholder="Email atau Nomor HP"
                   class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
            <button type="submit"
                    class="w-full py-2 bg-gradient-to-r from-orange-500 to-blue-600 text-white rounded-xl font-semibold shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg active:scale-95">
              Cari
            </button>
          </form>
        </div>

  <!-- Step 2: Masukkan OTP -->
  <div id="step-2">
    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-lg max-w-md mx-auto">
      <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center md:text-left">
        Verifikasi OTP
      </h2>
      <p class="text-gray-600 mb-6 text-sm text-center md:text-left">
        Kami telah mengirimkan kode OTP ke email atau nomor Anda.
      </p>

      <form id="form-step-2" class="space-y-6">
        <!-- OTP Input -->
        <div class="flex justify-center gap-3 sm:gap-4">
          <input type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="#"
            class="otp-input w-12 h-12 sm:w-14 sm:h-14 text-xl sm:text-2xl text-center font-semibold
                   border border-gray-300 rounded-xl shadow-sm appearance-none
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-400 focus:outline-none
                   transition-all duration-200 placeholder:text-gray-400" />
          <input type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="#"
            class="otp-input w-12 h-12 sm:w-14 sm:h-14 text-xl sm:text-2xl text-center font-semibold
                   border border-gray-300 rounded-xl shadow-sm appearance-none
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-400 focus:outline-none
                   transition-all duration-200 placeholder:text-gray-400" />
          <input type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="#"
            class="otp-input w-12 h-12 sm:w-14 sm:h-14 text-xl sm:text-2xl text-center font-semibold
                   border border-gray-300 rounded-xl shadow-sm appearance-none
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-400 focus:outline-none
                   transition-all duration-200 placeholder:text-gray-400" />
          <input type="tel" maxlength="1" inputmode="numeric" pattern="[0-9]*" placeholder="#"
            class="otp-input w-12 h-12 sm:w-14 sm:h-14 text-xl sm:text-2xl text-center font-semibold
                   border border-gray-300 rounded-xl shadow-sm appearance-none
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-400 focus:outline-none
                   transition-all duration-200 placeholder:text-gray-400" />
        </div>

        <!-- Tombol Konfirmasi -->
        <button id="confirmBtn" type="submit"
          class="w-full py-3 bg-gradient-to-r from-orange-500 to-blue-600 text-white
                 rounded-xl font-semibold shadow-md transition-transform duration-300
                 hover:scale-[1.02] hover:shadow-lg active:scale-95">
          Konfirmasi
        </button>
      </form>
    </div>
  </div>




     <!-- Step 3: Reset Password -->
  <div id="step-3" class="hidden">
    <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center md:text-left">Reset Password 🔒</h2>
    <p class="text-gray-600 mb-6 text-sm text-center md:text-left">Buat password baru untuk akun Anda.</p>

    <form id="form-step-3" class="space-y-4 relative">
      <!-- Password Baru -->
      <div class="relative">
        <input type="password" id="new-password" required
               placeholder="Password Baru"
               class="w-full px-4 py-2 pr-10 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
        <button type="button" id="toggle-new" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
          <!-- ikon mata -->
          <svg xmlns="http://www.w3.org/2000/svg" id="eye-new" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
        </button>
      </div>

      <!-- Konfirmasi Password -->
      <div class="relative">
        <input type="password" id="confirm-password" required
               placeholder="Konfirmasi Password"
               class="w-full px-4 py-2 pr-10 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
        <button type="button" id="toggle-confirm" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
          <!-- ikon mata -->
          <svg xmlns="http://www.w3.org/2000/svg" id="eye-confirm" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
        </button>
      </div>

      <p id="password-error" class="hidden text-red-500 text-sm">⚠️ Password harus sama di kedua kolom.</p>

      <button type="submit"
              class="w-full py-2 bg-gradient-to-r from-orange-500 to-blue-600 text-white rounded-xl font-semibold shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg active:scale-95">
        Simpan Password
      </button>
    </form>
  </div>

  <!-- Popup Success -->
  <div id="popup-success" class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col items-center text-center w-72">
      <!-- Loading Spinner -->
      <div id="popup-loading" class="w-12 h-12 border-4 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
      <!-- Icon Centang -->
      <svg id="popup-check" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500 hidden mt-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
      </svg>
      <!-- Pesan -->
      <p id="popup-text" class="mt-4 text-gray-700 font-medium text-base">Memproses...</p>
    </div>
  </div>
</main>

<script src="{{ asset('client/js/reset.js') }}"></script>
@endsection
