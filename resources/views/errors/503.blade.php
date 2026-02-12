@extends('client.main')

@section('body')
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-hidden pt-20 pb-20">
        <!-- Background Elements -->
        <div class="absolute top-20 right-0 w-[500px] h-[500px] bg-orange-200/20 rounded-full blur-[100px] animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-200/20 rounded-full blur-[100px] animate-pulse pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <div class="flex flex-col items-center">
                <!-- Animated 503 Illustration -->
                <div class="relative w-64 h-64 mb-8">
                     <svg class="w-full h-full drop-shadow-xl" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Floating Elements -->
                        <circle cx="100" cy="100" r="80" class="fill-orange-50 animate-pulse" style="animation-duration: 4s;"/>
                        
                        <!-- Robot Head -->
                        <path d="M60 90C60 67.9086 77.9086 50 100 50C122.091 50 140 67.9086 140 90V130C140 141.046 131.046 150 120 150H80C68.9543 150 60 141.046 60 130V90Z" class="fill-white"/>
                        
                        <!-- Eyes (Sleeping/Closed) -->
                        <path d="M75 85 L90 85" stroke="#F97316" stroke-width="4" stroke-linecap="round"/>
                        <path d="M110 85 L125 85" stroke="#F97316" stroke-width="4" stroke-linecap="round"/>
                        
                        <!-- Mouth (Straight/Neutral) -->
                         <path d="M90 125 L110 125" stroke="#93C5FD" stroke-width="4" stroke-linecap="round"/>

                        <!-- Gears (Maintenance) -->
                        <g class="origin-center animate-[spin_8s_linear_infinite]" style="transform-box: fill-box; transform-origin: 160px 50px;">
                            <path d="M160 50L175 45L165 30L150 40L160 50Z" class="fill-orange-400"/> <!-- Simple representation -->
                             <circle cx="160" cy="45" r="10" class="fill-orange-400"/>
                            <rect x="145" y="40" width="30" height="10" class="fill-orange-400"/>
                             <rect x="155" y="30" width="10" height="30" class="fill-orange-400"/>
                            <circle cx="160" cy="45" r="5" class="fill-white"/>
                        </g>

                         <g class="origin-center animate-[spin_6s_linear_infinite_reverse]" style="transform-box: fill-box; transform-origin: 40px 140px;">
                            <circle cx="40" cy="140" r="15" class="fill-blue-400"/>
                            <rect x="20" y="135" width="40" height="10" class="fill-blue-400"/>
                            <rect x="35" y="120" width="10" height="40" class="fill-blue-400"/>
                            <circle cx="40" cy="140" r="5" class="fill-white"/>
                        </g>
                        
                        <!-- 503 Text -->
                        <text x="100" y="180" text-anchor="middle" class="text-3xl font-bold fill-gray-400" style="font-family: sans-serif; letter-spacing: 0.1em;">503</text>
                    </svg>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4">
                        Layanan Sedang <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Perbaikan</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto leading-relaxed">
                        Maaf, kami sedang melakukan pemeliharaan sistem untuk meningkatkan kualitas layanan. Silakan coba beberapa saat lagi.
                    </p>
                    
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-orange-500 text-white font-bold rounded-xl shadow-lg hover:shadow-orange-500/30 hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Coba Lagi
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
