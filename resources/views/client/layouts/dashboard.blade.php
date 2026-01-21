@extends('client.main')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/layouts/dashboard.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@yield('dashboard-css')
@endsection

@section('body')
<script defer src="{{ asset('assets/elearning/client/js/layouts/dashboard.js') }}"></script>

<div class="flex-1 max-w-7xl mx-auto w-full px-6 py-12 flex flex-col lg:flex-row gap-10 pt-24">
    <!-- Sidebar (Desktop Only) -->
    <aside class="w-full lg:w-72 h-fit lg:sticky lg:top-24 bg-white border border-gray-100 shadow-lg rounded-2xl p-6 self-start hidden lg:block">
        @if(Auth::user()->role === 'instructor')
        <a href="{{ route('instructor.dashboard') }}?welcome=1" class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white px-4 py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
            </svg>
            Masuk ke Dashboard Instruktur
        </a>
        @endif
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
        <i class="fa-solid fa-book text-xl mb-0.5"></i>
        <span>Program</span>
    </a>
    <a href="{{ route('client.dashboard.certificate') }}" class="flex flex-col items-center gap-1 text-xs font-medium transition-all duration-200 px-3 py-2 rounded-xl {{ request()->routeIs('client.dashboard.certificate') ? 'bg-blue-600 text-white shadow-md transform -translate-y-1' : 'text-gray-500 hover:bg-gray-50' }}">
        <i class="fa-solid fa-award text-xl mb-0.5"></i>
        <span>Sertifikat</span>
    </a>

    <!-- Center Floating Item -->
    <div class="relative -top-8">
        <a href="{{ route('client.dashboard') }}" class="flex items-center justify-center w-16 h-16 rounded-full shadow-lg border-4 border-white transition-transform duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-blue-600 text-white scale-110' : 'bg-gray-100 text-gray-500' }}">
            <i class="fa-regular fa-user text-2xl"></i>
        </a>
        <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs font-medium {{ request()->routeIs('client.dashboard') ? 'text-blue-600' : 'text-gray-500' }}">Profil</span>
    </div>

    <!-- Right Group -->
    <a href="{{ route('client.dashboard.transaction') }}" class="flex flex-col items-center gap-1 text-xs font-medium transition-all duration-200 px-3 py-2 rounded-xl {{ request()->routeIs('client.dashboard.transaction') ? 'bg-blue-600 text-white shadow-md transform -translate-y-1' : 'text-gray-500 hover:bg-gray-50' }}">
        <i class="fa-solid fa-clock-rotate-left text-xl mb-0.5"></i>
        <span>Riwayat</span>
    </a>
    <a href="{{ route('client.dashboard.voucher') }}" class="flex flex-col items-center gap-1 text-xs font-medium transition-all duration-200 px-3 py-2 rounded-xl {{ request()->routeIs('client.dashboard.voucher') ? 'bg-blue-600 text-white shadow-md transform -translate-y-1' : 'text-gray-500 hover:bg-gray-50' }}">
        <i class="fa-solid fa-ticket text-xl mb-0.5"></i>
        <span>Voucher</span>
    </a>
</nav>
@endsection

@section('js')
@yield('dashboard-js')
@endsection