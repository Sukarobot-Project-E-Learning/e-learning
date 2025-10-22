@extends('client.layout.main')
@section('css')
<link rel="stylesheet" href="{{ asset('client/css/login.css') }}">
@endsection

@section('body')

<!-- Main Login Section -->
<main class="flex items-center justify-center min-h-screen bg-white">
    <div id="login-card"
         class="flex flex-col md:flex-row w-full max-w-5xl mx-4 bg-white rounded-3xl shadow-2xl overflow-hidden opacity-0 scale-90 transition-all duration-700">

      <!-- Left mural full -->
      <div class="hidden md:flex w-1/2 relative">
        <!-- Background mural gradient full -->
        <div class="absolute inset-0 bg-gradient-to-br from-orange-400/30 via-blue-400/20 to-pink-400/20"></div>

        <!-- Ilustrasi -->
        <div class="relative flex items-center justify-center w-full h-full p-8">
            <img src="{{ asset('client/img/ilustrator.jpeg') }}"
                 alt="Mural Illustration"
                 class="w-4/5 max-w-md transform transition-transform duration-700 ease-in-out hover:scale-105">
          </div>

      </div>

      <!-- Right Login Form -->
      <div class="w-full md:w-1/2 p-8 flex flex-col justify-center relative">
        <h2 class="text-3xl font-bold text-gray-800 mb-2 animate-fade">Selamat Datang 👋</h2>
        <p class="text-gray-600 mb-6 animate-fade">Masuk untuk melanjutkan ke dashboard Anda.</p>

        <form action="#" class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <button type="submit"
                  class="w-full py-2 bg-gradient-to-r from-orange-500 to-blue-600 text-white rounded-xl font-semibold shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg active:scale-95">
            Login
          </button>
        </form>

        <div class="mt-6">
          <button id="googleLoginBtn"
            class="w-full flex items-center justify-center gap-2 border border-gray-300 py-2 rounded-xl hover:bg-gray-50 transition hover:shadow-md active:scale-95">
            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5" alt="Google">
            <span>Login dengan Google</span>
          </button>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-600">
          <a href="{{ url('/reset') }}" class="hover:text-blue-600 transition">Lupa Password?</a>
          <a href="{{ url('/create') }}" class="hover:text-orange-500 transition">Buat Akun Baru</a>
        </div>
      </div>
    </div>
  </main>

<script src="{{ asset('client/js/login.js') }}"></script>
@endsection
