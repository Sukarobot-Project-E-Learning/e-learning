<!-- Footer Compact -->
<footer class="bg-gray-50 text-gray-600 py-12 border-t border-gray-200 font-sans">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Grid utama -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            <!-- Branding -->
            <div class="col-span-2 md:col-span-1 space-y-4 text-center md:text-left">
                <div class="flex flex-col items-center md:items-start">
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Sukarobot</h3>
                    <p class="text-blue-600 font-medium text-sm mt-1">Belajar - Berinovasi - Berprestasi</p>
                </div>
                <p class="text-sm leading-relaxed text-gray-500 max-w-md mx-auto md:mx-0">
                    Jl. A. Yani No.283, Kebonjati, Kec. Cikole, Kota Sukabumi, Jawa Barat 43111
                </p>
            </div>

            <!-- Navigasi -->
            <div class="space-y-4 text-center md:text-left">
                <h4 class="text-lg font-bold text-gray-900">Tentang</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/instruktur') }}" class="hover:text-blue-600 transition-colors duration-200">Instruktur</a></li>
                    <li><a href="{{ url('/tentang#tentang-kami') }}" 
                    class="hover:text-blue-600 transition-colors duration-200">Tentang Kami</a></li>
                    <li><a href="{{ url('/tentang#kebijakan') }}"class="hover:text-blue-600 transition-colors duration-200">Kebijakan & Privasi</a></li>
                    <li><a href="{{ url('/tentang#ketentuan') }}" class="hover:text-blue-600 transition-colors duration-200">Ketentuan Pengguna</a></li>
                    <li><a href="{{ url('/tentang#FAQ') }}" class="hover:text-blue-600 transition-colors duration-200">Bantuan (FAQ)</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="space-y-4 text-center md:text-left">
                <h4 class="text-lg font-bold text-gray-900">Kontak</h4>
                <div class="space-y-3 text-sm flex flex-col items-center md:items-start">
                    <a href="mailto:sukarobotacademy@gmail.com" class="flex items-center gap-3 hover:text-blue-600 transition-colors duration-200 group">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:shadow-md transition-all border border-gray-100 flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="break-all">sukarobotacademy@gmail.com</span>
                    </a>
                    <a href="tel:+6285795899901" class="flex items-center gap-3 hover:text-blue-600 transition-colors duration-200 group">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:shadow-md transition-all border border-gray-100 flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <span>+62 857-9589-9901</span>
                    </a>
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="col-span-2 md:col-span-1 space-y-4 text-center md:text-left">
                <h4 class="text-lg font-bold text-gray-900">Sosial Media</h4>
                <div class="flex flex-row md:flex-col justify-center md:justify-start gap-4 md:gap-3 flex-wrap text-sm">
                    <a href="https://www.instagram.com/sukarobot.academy" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 hover:text-blue-600 transition-colors duration-200 group">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:shadow-md transition-all border border-gray-100">
                            <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline">@sukarobot.academy</span>
                    </a>
                    <a href="https://www.facebook.com/sukarobotacademy" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 hover:text-blue-600 transition-colors duration-200 group">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:shadow-md transition-all border border-gray-100">
                            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline">@sukarobotacademy</span>
                    </a>
                    <a href="https://youtube.com/@sukarobot.academy" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 hover:text-blue-600 transition-colors duration-200 group">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:shadow-md transition-all border border-gray-100">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M22.54 6.42a2 2 0 00-1.41-1.42C19.5 4.5 12 4.5 12 4.5s-7.5 0-9.13.5a2 2 0 00-1.41 1.42C1 8.07 1 12 1 12s0 3.93.46 5.58a2 2 0 001.41 1.42C4.5 19.5 12 19.5 12 19.5s7.5 0 9.13-.5a2 2 0 001.41-1.42C23 15.93 23 12 23 12s0-3.93-.46-5.58z"/>
                                <polygon points="10 15 15 12 10 9 10 15"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline">Sukarobot</span>
                    </a>
                    <a href="https://www.linkedin.com/company/sukarobotacademy/" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 hover:text-blue-600 transition-colors duration-200 group">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:shadow-md transition-all border border-gray-100">
                            <svg class="w-4 h-4 text-blue-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-4 0v7h-4v-7a6 6 0 016-6z"/>
                                <rect x="2" y="9" width="4" height="12"/>
                                <circle cx="4" cy="4" r="2"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline">Sukarobot Academy</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-200 mt-12 pt-8 text-center">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} <span class="font-semibold text-gray-700">Sukarobot</span>. All rights reserved.
            </p>
        </div>
    </div>
</footer>

