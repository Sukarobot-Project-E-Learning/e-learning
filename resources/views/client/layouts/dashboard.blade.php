@extends('client.main')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/dashboard.css') }}">
<style>
    @media (min-width: 1024px) {
        footer {
            margin-left: 16rem; /* w-64 is 16rem */
            width: auto;
        }
    }
</style>
@yield('dashboard-css')
@endsection

@section('body')
<script defer src="{{ asset('assets/elearning/client/js/dashboard.js') }}"></script>

<div class="flex min-h-screen bg-gray-50 pt-24 bg-white pb-24 lg:pb-0">
  <!-- Sidebar (Desktop Only) -->
  <aside id="sidebar" class="hidden lg:flex w-64 pt-2 pr-1 bg-white border-r border-gray-200 fixed h-full flex-col justify-between shadow-md rounded-xl z-40">
    <div>
      <nav>
        <ul class="space-y-1 text-gray-700">
          <li>
            <a href="{{ route('client.dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <i class="fa-regular fa-user mr-2 w-5 text-center"></i>Profil
            </a>
          </li>
          <li>
            <a href="{{ route('client.dashboard.program') }}" class="nav-item flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('client.dashboard.program') ? 'active' : '' }}">
                <i class="fa-solid fa-book mr-2 w-5 text-center"></i>Program
            </a>
          </li>
          <li>
            <a href="{{ route('client.dashboard.certificate') }}" class="nav-item flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('client.dashboard.certificate') ? 'active' : '' }}">
                <i class="fa-solid fa-award mr-2 w-5 text-center"></i>Sertifikat
            </a>
          </li>
          <li>
            <a href="{{ route('client.dashboard.transaction') }}" class="nav-item flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('client.dashboard.transaction') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left mr-2 w-5 text-center"></i>Riwayat Transaksi
            </a>
          </li>
          <li>
            <a href="{{ route('client.dashboard.voucher') }}" class="nav-item flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('client.dashboard.voucher') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket mr-2 w-5 text-center"></i>Voucher
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Bottom Navigation (Mobile/Tablet Only) -->
  <nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex justify-between md:justify-center md:gap-24 items-end px-4 py-3 z-[999] lg:hidden shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
      <!-- Left Group -->
      <a href="{{ route('client.dashboard.program') }}" class="flex flex-col items-center gap-1 text-xs font-medium transition-all duration-200 px-3 py-2 rounded-xl {{ request()->routeIs('client.dashboard.program') ? 'bg-blue-600 text-white shadow-md transform -translate-y-1' : 'text-gray-500 hover:bg-gray-50' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
          <span>Program</span>
      </a>
      <a href="{{ route('client.dashboard.certificate') }}" class="flex flex-col items-center gap-1 text-xs font-medium transition-all duration-200 px-3 py-2 rounded-xl {{ request()->routeIs('client.dashboard.certificate') ? 'bg-blue-600 text-white shadow-md transform -translate-y-1' : 'text-gray-500 hover:bg-gray-50' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>Sertifikat</span>
      </a>

      <!-- Center Floating Item -->
      <div class="relative -top-8">
          <a href="{{ route('client.dashboard') }}" class="flex items-center justify-center w-16 h-16 rounded-full shadow-lg border-4 border-white transition-transform duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-blue-600 text-white scale-110' : 'bg-gray-100 text-gray-500' }}">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
          </a>
          <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs font-medium {{ request()->routeIs('client.dashboard') ? 'text-blue-600' : 'text-gray-500' }}">Profil</span>
      </div>

      <!-- Right Group -->
      <a href="{{ route('client.dashboard.transaction') }}" class="flex flex-col items-center gap-1 text-xs font-medium transition-all duration-200 px-3 py-2 rounded-xl {{ request()->routeIs('client.dashboard.transaction') ? 'bg-blue-600 text-white shadow-md transform -translate-y-1' : 'text-gray-500 hover:bg-gray-50' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>Riwayat</span>
      </a>
      <a href="{{ route('client.dashboard.voucher') }}" class="flex flex-col items-center gap-1 text-xs font-medium transition-all duration-200 px-3 py-2 rounded-xl {{ request()->routeIs('client.dashboard.voucher') ? 'bg-blue-600 text-white shadow-md transform -translate-y-1' : 'text-gray-500 hover:bg-gray-50' }}">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
          </svg>
          <span>Voucher</span>
      </a>
  </nav>

  <!-- Main Content -->
  <main class="lg:ml-64 flex-1 pt-2 pr-8 pb-8 pl-8 transition-all duration-300 w-full">
    <!-- No sidebar toggle needed for mobile as we use bottom nav -->
    
    @yield('dashboard-content')

  </main>
</div>
@endsection

@section('js')
    @yield('dashboard-js')
@endsection
