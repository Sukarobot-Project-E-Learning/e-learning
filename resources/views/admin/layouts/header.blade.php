<header class="z-40 py-4 bg-white/80 backdrop-blur-md shadow-lg dark:bg-gray-800/90 border-b border-orange-100 dark:border-orange-900/30">
    <div class="container flex items-center justify-between h-full px-6 mx-auto text-orange-600 dark:text-orange-300">

        <!-- Mobile hamburger -->
        <button class="p-1 mr-5 -ml-1 rounded-lg md:hidden focus:outline-none focus:ring-2 focus:ring-orange-500 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-all duration-300"
            @click="toggleSideMenu"
            aria-label="Menu">
            <svg class="w-6 h-6 transition-transform duration-300 hover:scale-110" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>

        <!-- Search input -->
        <div class="flex justify-center flex-1 lg:mr-32">
            <div class="relative w-full max-w-xl mr-6 focus-within:text-orange-500">
                <div class="absolute inset-y-0 flex items-center pl-4">
                </div>
            </div>
        </div>

        <ul class="flex items-center flex-shrink-0 space-x-6">
            <!-- Theme toggler - unified with global `dark` state -->
            <li class="flex">
                <button class="relative p-2 rounded-xl text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 focus:outline-none focus:ring-2 focus:ring-orange-500 hover:bg-gradient-to-br hover:from-orange-100 hover:to-amber-100 dark:hover:from-orange-900/30 dark:hover:to-amber-900/30 transition-all duration-300 group"
                    @click="toggleTheme"
                    aria-label="Toggle color mode">
                    <!-- Sun icon (visible when dark mode is active) -->
                    <svg x-cloak x-show="dark" class="w-5 h-5 transform transition-transform duration-500 group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                    </svg>
                    <!-- Moon icon (visible when light mode is active) -->
                    <svg x-cloak x-show="!dark" class="w-5 h-5 transform transition-transform duration-500 group-hover:-rotate-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <!-- Glow effect -->
                    <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-orange-400/0 via-amber-400/0 to-yellow-400/0 group-hover:from-orange-400/20 group-hover:via-amber-400/20 group-hover:to-yellow-400/20 transition-all duration-300"></span>
                </button>
            </li>

            <!-- Notifications menu -->
            <li class="relative" x-data="{ isNotificationsMenuOpen: false }">
                <button class="relative p-2 rounded-xl text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300 align-middle focus:outline-none focus:ring-2 focus:ring-orange-500 hover:bg-gradient-to-br hover:from-orange-100 hover:to-amber-100 dark:hover:from-orange-900/30 dark:hover:to-amber-900/30 transition-all duration-300 group"
                    @click="isNotificationsMenuOpen = !isNotificationsMenuOpen"
                    @keydown.escape="isNotificationsMenuOpen = false"
                    aria-label="Notifications"
                    aria-haspopup="true">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    <!-- Notification badge with pulse animation -->
                    <span aria-hidden="true" class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white transform translate-x-1 -translate-y-1 bg-gradient-to-r from-red-500 to-orange-500 rounded-full shadow-lg animate-pulse">
                        3
                    </span>
                    <!-- Glow effect -->
                    <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-orange-400/0 via-amber-400/0 to-yellow-400/0 group-hover:from-orange-400/20 group-hover:via-amber-400/20 group-hover:to-yellow-400/20 transition-all duration-300"></span>
                </button>

                <template x-if="isNotificationsMenuOpen">
                    <ul x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        @click.away="isNotificationsMenuOpen = false"
                        @keydown.escape="isNotificationsMenuOpen = false"
                        class="absolute right-0 w-72 p-3 mt-2 space-y-2 text-gray-600 bg-white/95 backdrop-blur-md border border-orange-200 rounded-2xl shadow-xl shadow-orange-200/50 dark:text-gray-300 dark:border-orange-800/50 dark:bg-gray-800/95 dark:shadow-orange-900/30 overflow-hidden">

                        <!-- Header -->
                        <li class="flex items-center justify-between px-3 py-2 bg-gradient-to-r from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 rounded-xl">
                            <span class="font-bold text-orange-700 dark:text-orange-300">Notifikasi</span>
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs font-bold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-full shadow-md">
                                3 Baru
                            </span>
                        </li>

                        <!-- Divider -->
                        <li class="border-t border-orange-200 dark:border-orange-800/50"></li>

                        <!-- Notification items -->
                        <li>
                            <a class="flex items-start p-3 rounded-xl hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 dark:hover:from-orange-900/20 dark:hover:to-amber-900/20 transition-all duration-300 group" href="#">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Program Baru Ditambahkan</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">5 menit yang lalu</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a class="flex items-start p-3 rounded-xl hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 dark:hover:from-orange-900/20 dark:hover:to-amber-900/20 transition-all duration-300 group" href="#">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Transaksi Berhasil</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">1 jam yang lalu</p>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a class="flex items-start p-3 rounded-xl hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 dark:hover:from-orange-900/20 dark:hover:to-amber-900/20 transition-all duration-300 group" href="#">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">User Baru Terdaftar</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">2 jam yang lalu</p>
                                </div>
                            </a>
                        </li>

                        <!-- Divider -->
                        <li class="border-t border-orange-200 dark:border-orange-800/50"></li>

                        <!-- View all link -->
                        <li>
                            <a href="#" class="block text-center py-2 text-sm font-semibold text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 transition-colors">
                                Lihat Semua Notifikasi
                            </a>
                        </li>
                    </ul>
                </template>
            </li>

            <!-- Profile menu -->
            <li class="relative" x-data="{ isProfileMenuOpen: false }">
                <button class="relative flex items-center p-1 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 hover:bg-gradient-to-br hover:from-orange-100 hover:to-amber-100 dark:hover:from-orange-900/30 dark:hover:to-amber-900/30 transition-all duration-300 group"
                    @click="isProfileMenuOpen = !isProfileMenuOpen"
                    @keydown.escape="isProfileMenuOpen = false"
                    aria-label="Account"
                    aria-haspopup="true">
                    <div class="relative">
                        <img class="object-cover w-9 h-9 rounded-xl ring-2 ring-orange-300 dark:ring-orange-600 transition-all duration-300 group-hover:ring-4 group-hover:ring-orange-400 dark:group-hover:ring-orange-500 shadow-md"
                            src="https://ui-avatars.com/api/?name=Admin&background=f97316&color=fff"
                            alt="Admin"
                            aria-hidden="true" />
                        <!-- Online indicator -->
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                    </div>
                    <div class="hidden md:block ml-3 text-left">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Admin</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
                    </div>
                    <svg class="hidden md:block w-4 h-4 ml-2 text-gray-500 dark:text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': isProfileMenuOpen }" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <template x-if="isProfileMenuOpen">
                    <ul x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        @click.away="isProfileMenuOpen = false"
                        @keydown.escape="isProfileMenuOpen = false"
                        class="absolute right-0 w-64 p-3 mt-2 space-y-1 text-gray-600 bg-white/95 backdrop-blur-md border border-orange-200 rounded-2xl shadow-xl shadow-orange-200/50 dark:border-orange-800/50 dark:text-gray-300 dark:bg-gray-800/95 dark:shadow-orange-900/30 overflow-hidden"
                        aria-label="submenu">

                        <!-- User info header -->
                        <li class="px-3 py-3 mb-2 bg-gradient-to-r from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 rounded-xl">
                            <div class="flex items-center">
                                <img class="w-12 h-12 rounded-xl ring-2 ring-orange-300 dark:ring-orange-600 shadow-md"
                                    src="https://ui-avatars.com/api/?name=Admin&background=f97316&color=fff"
                                    alt="Admin" />
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100">Admin User</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">admin@example.com</p>
                                </div>
                            </div>
                        </li>

                        <!-- Menu items -->
                        <li>
                            <a class="flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 dark:hover:from-orange-900/20 dark:hover:to-amber-900/20 transition-all duration-300 group" href="#">
                                <div class="p-2 mr-3 rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/40 dark:to-amber-900/40 group-hover:from-orange-200 group-hover:to-amber-200 dark:group-hover:from-orange-800/40 dark:group-hover:to-amber-800/40 transition-all">
                                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Profile</span>
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 dark:hover:from-orange-900/20 dark:hover:to-amber-900/20 transition-all duration-300 group" href="#">
                                <div class="p-2 mr-3 rounded-lg bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/40 dark:to-amber-900/40 group-hover:from-orange-200 group-hover:to-amber-200 dark:group-hover:from-orange-800/40 dark:group-hover:to-amber-800/40 transition-all">
                                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Settings</span>
                            </a>
                        </li>

                        <!-- Divider -->
                        <li class="my-2 border-t border-orange-200 dark:border-orange-800/50"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-medium rounded-xl hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 dark:hover:from-red-900/20 dark:hover:to-orange-900/20 transition-all duration-300 group">
                                    <div class="p-2 mr-3 rounded-lg bg-gradient-to-br from-red-100 to-orange-100 dark:from-red-900/40 dark:to-orange-900/40 group-hover:from-red-200 group-hover:to-orange-200 dark:group-hover:from-red-800/40 dark:group-hover:to-orange-800/40 transition-all">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span class="text-red-600 dark:text-red-400 group-hover:text-red-700 dark:group-hover:text-red-300 transition-colors">Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </template>
            </li>
        </ul>
    </div>
</header>