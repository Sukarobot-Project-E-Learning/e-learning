@extends('client.layout.main')
@section('css')
<link rel="stylesheet" href="{{ asset('client/css/create.css') }}">
@endsection

@section('body')

<main class="flex items-center justify-center min-h-screen bg-white">
    <div id="register-card"
         class="flex flex-col md:flex-row w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden animate-fade mx-4">

      <!-- Left mural -->
      <div class="hidden md:flex w-1/2 bg-gradient-to-br from-orange-400 to-blue-600 items-center justify-center p-10">
        <img src="{{ asset('client/img/download__1_-removebg-preview.png') }}"
             alt="Register Illustration"
             class="w-80 drop-shadow-xl rounded-xl">
      </div>

      <!-- Right form -->
      <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
        <p class="text-gray-600 mb-6 text-sm">Isi data di bawah untuk membuat akun Anda.</p>

        <form id="register-form" class="space-y-4">
          <!-- Nama -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" required placeholder="Nama lengkap Anda"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Username -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" required placeholder="Username unik"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Nomor HP -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
            <input type="tel" required placeholder="08xxxxxxxxxx"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" required placeholder="contoh@email.com"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" required placeholder="Password rahasia"
                   class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-300 hover:shadow-md active:scale-95">
          </div>

          <!-- Button daftar -->
          <button type="submit"
                  class="w-full py-2 bg-gradient-to-r from-orange-500 to-blue-600 text-white rounded-xl font-semibold shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg active:scale-95">
            Buat Akun
          </button>
        </form>

        <div class="mt-6 text-sm text-gray-600 text-center">
          Sudah punya akun?
          <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login</a>
        </div>
      </div>
    </div>
  </main>

<script src="{{ asset('client/js/create.js') }}"></script>
@endsection
