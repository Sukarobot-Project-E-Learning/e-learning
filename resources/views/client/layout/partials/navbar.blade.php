<header class="sticky-nav h-16">
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
          <a href="{{ url('/kelas') }}">Program ▾</a>
          <ul class="custom-dropdown-menu">
            <li><a href="{{ url('/kelas#filter=kursus') }}">Kursus</a></li>
            <li><a href="{{ url('/kelas#filter=pelatihan') }}">Pelatihan</a></li>
            <li><a href="{{ url('/kelas#filter=sertifikasi') }}">Sertifikasi</a></li>
            <li><a href="{{ url('/kelas#filter=outingclass') }}">Outing Class</a></li>
            <li><a href="{{ url('/kelas#filter=outboard') }}">Outboard</a></li>
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
            <li><a href="{{ url('/about') }}">Tentang Kami</a></li>
          </ul>
        </li>

        <!-- Blog -->
        <li><a href="{{ url('/blog') }}">Artikel</a></li>

        <!-- Button Masuk -->
        <li><a href="{{ url('/login') }}" class="btn-login">Masuk</a></li>
      </ul>
    </div>
  </header>

<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/nav.css') }}">
