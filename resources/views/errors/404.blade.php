@extends('client.main')

@section('body')
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-hidden pt-20 pb-20">
        <!-- Background Elements -->
        <div class="absolute top-20 right-0 w-[500px] h-[500px] bg-orange-200/20 rounded-full blur-[100px] animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-200/20 rounded-full blur-[100px] animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <div class="flex flex-col items-center">
                <!-- Animated 404 Illustration -->
                <div class="relative w-64 h-64 mb-8">
                     <svg class="w-full h-full drop-shadow-xl" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Floating Elements -->
                        <circle cx="100" cy="100" r="80" class="fill-blue-50 animate-pulse" style="animation-duration: 4s;"/>
                        
                        <!-- Robot Head -->
                        <path d="M60 90C60 67.9086 77.9086 50 100 50C122.091 50 140 67.9086 140 90V130C140 141.046 131.046 150 120 150H80C68.9543 150 60 141.046 60 130V90Z" class="fill-white"/>
                        
                        <!-- Eyes (Confused/Sad) -->
                        <rect x="75" y="80" width="15" height="15" rx="7.5" class="fill-blue-200" style="transform: rotate(-10deg); transform-origin: center;"/>
                        <rect x="110" y="80" width="15" height="15" rx="7.5" class="fill-blue-200" style="transform: rotate(10deg); transform-origin: center;"/>
                        
                        <!-- Mouth (Sad) -->
                         <path d="M85 125 C85 125 90 118 100 118 C110 118 115 125 115 125" stroke="#93C5FD" stroke-width="4" stroke-linecap="round"/>

                        <!-- Question Marks (Floating) -->
                        <text x="40" y="60" class="text-4xl font-bold fill-orange-400 animate-bounce" style="animation-duration: 2s; font-family: sans-serif;">?</text>
                        <text x="150" y="60" class="text-4xl font-bold fill-blue-400 animate-bounce" style="animation-duration: 2.5s; font-family: sans-serif;">?</text>
                        
                        <!-- 404 Text -->
                        <text x="100" y="180" text-anchor="middle" class="text-3xl font-bold fill-gray-400" style="font-family: sans-serif; letter-spacing: 0.1em;">404</text>
                    </svg>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4">
                        Halaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Tidak Ditemukan</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto leading-relaxed">
                        Maaf, halaman yang Anda cari mungkin telah dihapus, namanya diganti, atau untuk sementara tidak tersedia.
                    </p>
                    
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-blue-500/30 hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
