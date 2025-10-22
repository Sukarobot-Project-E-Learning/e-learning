<header class="sticky-nav h-nav">
    <div class="nav-container">
      <!-- Logo -->
      <a href="{{ url('client/img/Sukarobot-logo.png') }}" class="nav-logo" aria-label="Sukarobot Home">
        <img src="{{ url('client/img/Sukarobot-logo.png') }}" alt="Sukarobot" class="logo-img" />
      </a>

      <!-- Burger -->
      <button class="custom-burger" aria-label="Toggle navigation" aria-expanded="false" aria-controls="nav-menu">
        ☰
      </button>

      <!-- Nav Links -->
      <ul class="custom-nav" id="nav-menu">
        <li><a href="{{ url('/') }}">Home</a></li>

        <li class="custom-dropdown">
          <a href="{{ url('/kelas') }}">Program ▾</a>
          <ul class="custom-dropdown-menu">
            <li><a href="{{ url('/kelas#filter=seminar') }}">Pelatihan</a></li>
            <li><a href="{{ url('/kelas#filter=online') }}">Sertifikasi</a></li>
          </ul>
        </li>

     <!-- NAV ITEM: EVENT DROPDOWN DENGAN SUBMENU -->
<!-- NAV ITEM: EVENT DROPDOWN DENGAN SUBMENU -->
<!-- NAV ITEM: EVENT DROPDOWN DENGAN SUBMENU -->
<li class="custom-dropdown relative group">
    <button
      class="dropdown-toggle flex items-center justify-between w-full md:w-auto text-gray-700 hover:text-orange-500 transition font-medium">
      Event ▾
    </button>

    <!-- MENU UTAMA -->
    <ul
      class="dropdown-menu absolute hidden md:group-hover:block bg-white border border-gray-200 rounded-lg shadow-lg mt-2 min-w-[190px] z-50">
      <li>
        <a href="{{ url('/event') }}"
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-500 transition">
          Outing Class
        </a>
      </li>
      <li>
        <a href="{{ url('/event') }}"
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-500 transition">
          Outboard
        </a>
      </li>

      <!-- DROPDOWN DALAM DROPDOWN -->
      <li class="relative group/sub">
        <button
          class="submenu-toggle flex justify-between items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-500 transition md:cursor-default">
          Kompetisi ▾
        </button>

        <ul
          class="submenu hidden md:absolute md:left-full md:top-0 bg-white border border-gray-200 rounded-lg shadow-lg min-w-[160px] z-50 md:group-hover/sub:block">
          <li>
            <a href="{{ url('/event') }}"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-500 transition">
              SRC
            </a>
          </li>
          <li>
            <a href="{{ url('/event') }}"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-500 transition">
              BRC
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </li>









        <li class="custom-dropdown">
          <a href="{{ url('/about#tentang-kami') }}">Tentang Sukarobot ▾</a>
          <ul class="custom-dropdown-menu">
            <li><a href="{{ url('/instruktur') }}">Instruktur</a></li>
            <li><a href="{{ url('/about#tentang-kami') }}">Tentang Kami</a></li>
          </ul>
        </li>

        <li><a href="{{ url('/blog') }}">Blog</a></li>
        <li><a href="{{ url('/login') }}" class="btn-login">Masuk</a></li>
      </ul>
    </div>
  </header>
