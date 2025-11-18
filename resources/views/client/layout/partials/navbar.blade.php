<header class="sticky-nav h-16 flex-items-center">
    <div class="nav-container">
      <!-- Logo -->
      <a href="{{ url('/') }}" class="nav-logo" aria-label="Sukarobot Home">
        <img src="{{ asset('assets/elearning/client/img/Sukarobot-logo.png') }}" alt="Sukarobot" class="logo-img" />
      </a>

      <!-- Burger -->
      <button class="custom-burger" aria-label="Toggle navigation" aria-expanded="false" aria-controls="nav-menu">
        ☰
      </button>

      <!-- Nav Links -->
      <ul class="custom-nav" id="nav-menu">
        <!-- Home -->
        <li><a href="{{ url('/') }}">Home</a></li>

        <!-- Program Dropdown -->
        <li class="custom-dropdown">
          <a href="{{ url('/program') }}">Program ▾</a>
          <ul class="custom-dropdown-menu">
            <li><a href="{{ url('/program#filter=kursus') }}">Kursus</a></li>
            <li><a href="{{ url('/program#filter=pelatihan') }}">Pelatihan</a></li>
            <li><a href="{{ url('/program#filter=sertifikasi') }}">Sertifikasi</a></li>
            <li><a href="{{ url('/program#filter=outingclass') }}">Outing Class</a></li>
            <li><a href="{{ url('/program#filter=outboard') }}">Outboard</a></li>
          </ul>
        </li>

        <!-- Kompetisi -->
        <li class="custom-dropdown">
          <a href="{{ url('#') }}">Kompetisi ▾</a>
          <ul class="custom-dropdown-menu">
            <li><a href="{{ url('https://brc.sukarobot.com/') }}">BRC</a></li>
            <li><a href="{{ url('https://src.sukarobot.com/') }}">SRC</a></li>
          </ul>
        </li>

        <!-- Tentang Sukarobot -->
        <li class="custom-dropdown">
          <a href="{{ url('#') }}">Tentang Sukarobot ▾</a>
          <ul class="custom-dropdown-menu">
            <li><a href="{{ url('/instruktur') }}">Instruktur</a></li>
            <li><a href="{{ url('/tentang') }}">Tentang Kami</a></li>
          </ul>
        </li>

        <!-- Artikel -->
        <li><a href="{{ url('/artikel') }}">Artikel</a></li>

        <!-- Jika user belum login -->
        @guest
        <!-- Button masuk -->
          <li><a href="{{ url('/login') }}" class="btn-login">Masuk</a></li>          
        @endguest
        
        <!-- Jika user sudah login -->
        @auth
          <!-- Dropdown User -->
          <li class="custom-dropdown">
            <button id="userMenuBtn" class="flex items-center gap-2 cursor-pointer">
                <img src="{{ auth()->user()->avatar }}" 
                    class="w-8 h-8 rounded-full">
            </button> 
            <ul class="custom-dropdown-menu user-menu">
              <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="logout-button cursor-pointer">Logout</button>
                </form>
              </li>
            </ul>
          </li>
        @endauth
      </ul>
    </div>
  </header>

<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/navbar.css') }}">
<script src="{{ asset('assets/elearning/client/js/navbar.js') }}"></script>
