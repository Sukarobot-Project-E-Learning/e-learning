@extends('client.main')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/dashboard.css') }}">
@yield('dashboard-css')
@endsection

@section('body')
<script defer src="{{ asset('assets/elearning/client/js/dashboard.js') }}"></script>

<div class="flex-1 max-w-7xl mx-auto w-full px-6 py-12 flex flex-col lg:flex-row gap-10 pt-24">
  <!-- Sidebar (Desktop Only) -->
  <aside class="w-full lg:w-72 h-fit lg:sticky lg:top-24 bg-white border border-gray-100 shadow-lg rounded-2xl p-6 self-start hidden lg:block">
    <h3 class="text-lg font-bold text-gray-900 mb-4 px-3">Menu Dashboard</h3>
    <nav class="space-y-1">
        <a href="{{ route('client.dashboard') }}" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('client.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
            <i class="fa-regular fa-user mr-2 w-5 text-center"></i> Profil
        </a>
        <a href="{{ route('client.dashboard.program') }}" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('client.dashboard.program') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
            <i class="fa-solid fa-book mr-2 w-5 text-center"></i> Program
        </a>
        <a href="{{ route('client.dashboard.certificate') }}" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('client.dashboard.certificate') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
            <i class="fa-solid fa-award mr-2 w-5 text-center"></i> Sertifikat
        </a>
        <a href="{{ route('client.dashboard.transaction') }}" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('client.dashboard.transaction') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
            <i class="fa-solid fa-clock-rotate-left mr-2 w-5 text-center"></i> Riwayat Transaksi
        </a>
        <a href="{{ route('client.dashboard.voucher') }}" class="sidebar-link block px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('client.dashboard.voucher') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
            <i class="fa-solid fa-ticket mr-2 w-5 text-center"></i> Voucher
        </a>
    </nav>
  </aside>

  <!-- Content Card -->
  <section class="flex-1 bg-white shadow-sm border border-gray-100 rounded-2xl p-8 md:p-10 overflow-hidden min-h-[600px]">
    @yield('dashboard-content')
  </section>
</div>

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
@endsection

@section('js')
    @yield('dashboard-js')
@endsection
