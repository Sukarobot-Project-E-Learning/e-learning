@extends('client.main')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/login/register.css') }}">
@endsection

@section('body')

<main class="flex items-center justify-center min-h-screen bg-white pt-24 pb-24">
    <div id="register-card"
         class="flex flex-col md:flex-row w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden animate-fade mx-4">

      <!-- Left mural -->
      <div class="hidden md:flex w-1/2 bg-gradient-to-br from-orange-400 to-blue-600 items-center justify-center p-10">
        <img src="{{ asset('assets/elearning/client/img/download__1_-removebg-preview.png') }}"
             alt="Register Illustration"
             class="w-80 drop-shadow-xl rounded-xl">
      </div>

      <!-- Right form -->
      <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
        <p class="text-gray-600 mb-6 text-sm">Isi data di bawah untuk membuat akun Anda.</p>

        <form id="register-form" class="space-y-4">
          <!-- Error Messages -->
          <div id="error-messages" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
            <ul id="error-list" class="list-disc list-inside text-sm"></ul>
          </div>

          <!-- Nama -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="name" id="name" required placeholder="Nama lengkap Anda"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Username -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" required placeholder="Username unik"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Nomor HP -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
            <input type="tel" name="phone" id="phone" required placeholder="08xxxxxxxxxx"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" required placeholder="contoh@email.com"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" required placeholder="Password rahasia (min. 8 karakter)"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Confirm Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Button daftar -->
          <button type="submit" id="submit-btn"
                  class="w-full py-2 bg-gradient-to-r from-orange-500 to-blue-600 text-white rounded-xl font-semibold shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg active:scale-95">
            Buat Akun
          </button>
        </form>

        <div class="mt-6">
          <a href="{{ route('google.login') }}"
            class="w-full flex items-center justify-center gap-2 border border-gray-300 py-2 rounded-xl hover:bg-gray-50 transition hover:shadow-md active:scale-95">
            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5" alt="Google">
            <span>Daftar dengan Google</span>
          </a>
        </div>

        <div class="mt-6 text-sm text-gray-600 text-center">
          Sudah punya akun?
          <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login</a>
        </div>
      </div>
    </div>
  </main>

<script src="{{ asset('assets/elearning/client/js/login/register.js') }}"></script>
@endsection
