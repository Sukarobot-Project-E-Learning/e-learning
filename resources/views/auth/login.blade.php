<!DOCTYPE html>
<html :class="{ 'dark': dark }" x-data="data()" lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="turbo-cache-control" content="no-cache" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Login - E-Learning Sukarobot</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />
    
    <!-- TailwindCSS CDN Fallback (in case Vite assets fail to load) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        orange: {
                            50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa', 300: '#fdba74',
                            400: '#fb923c', 500: '#f97316', 600: '#ea580c', 700: '#c2410c',
                            800: '#9a3412', 900: '#7c2d12', 950: '#431407'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Vite CSS & JS (will override CDN if loaded successfully) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js x-cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    
    <script
        src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
        defer
    ></script>
    <script>
        function data() {
            return {
                dark: false,
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-orange-50 text-gray-900 dark:from-gray-900 dark:via-gray-900 dark:to-slate-950 dark:text-gray-100">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 opacity-60 dark:opacity-40" aria-hidden="true"
             style="background: radial-gradient(circle at 20% 20%, rgba(249,115,22,0.18), transparent 30%),
                    radial-gradient(circle at 80% 0%, rgba(251,146,60,0.2), transparent 28%),
                    radial-gradient(circle at 60% 80%, rgba(248,180,0,0.14), transparent 35%);"></div>

        <div class="relative z-10 flex items-center justify-center min-h-screen px-6 py-10">
            <div class="w-full max-w-5xl">
                <div class="grid overflow-hidden rounded-3xl border border-white/60 bg-white/80 shadow-2xl shadow-indigo-100/70 backdrop-blur-xl dark:border-gray-800/80 dark:bg-gray-900/80 dark:shadow-black/40 md:grid-cols-2">
                    <!-- Brand / Highlight Side -->
                    <div class="relative hidden h-full md:flex md:flex-col md:justify-between bg-gradient-to-br from-orange-600 via-orange-500 to-amber-400 px-10 py-12 text-white">
                        <div class="absolute inset-0 opacity-30" aria-hidden="true"
                             style="background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.32), transparent 35%),
                                    radial-gradient(circle at 70% 20%, rgba(255,255,255,0.25), transparent 28%),
                                    radial-gradient(circle at 50% 80%, rgba(255,255,255,0.2), transparent 30%);"></div>
                        <div class="relative z-10 space-y-6">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                                {{ isset($type) && $type === 'admin' ? 'Admin Access' : 'Instruktur Access' }}
                            </span>
                            <h2 class="text-3xl font-bold leading-tight">Selamat datang di E-Learning Sukarobot</h2>
                            <p class="text-sm text-white/80 max-w-md">Kelola kelas, pantau progres peserta, dan akses materi dengan pengalaman yang ringkas dan aman.</p>
                            <div class="grid gap-3 text-sm">
                                <div class="flex items-center gap-3 rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-lg font-semibold">✓</span>
                                    <div>
                                        <p class="font-semibold">Masuk aman & terpisah</p>
                                        <p class="text-white/70">Sesi admin dan instruktur dipisahkan untuk keamanan.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-lg font-semibold">⚡</span>
                                    <div>
                                        <p class="font-semibold">Akses cepat</p>
                                        <p class="text-white/70">Langsung menuju dashboard dan materi terbaru.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 backdrop-blur">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-lg font-semibold">📈</span>
                                    <div>
                                        <p class="font-semibold">Pantau performa</p>
                                        <p class="text-white/70">Statistik pembelajaran dan pembayaran terintegrasi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center justify-between pt-6 text-xs text-white/70">
                            <span>E-Learning Platform</span>
                        </div>
                    </div>

                    <!-- Form Side -->
                    <div class="relative w-full p-8 sm:p-12">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/60 via-white/40 to-white/10 dark:from-gray-900/70 dark:via-gray-900/60 dark:to-gray-900/40" aria-hidden="true"></div>
                        <div class="relative space-y-8">
                            <div class="space-y-3">
                                <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700 dark:bg-orange-900/50 dark:text-orange-200">
                                    {{ isset($type) && $type === 'admin' ? 'Admin' : 'Instruktur' }} Login
                                </span>
                                <h1 class="text-2xl font-bold leading-7 text-gray-900 dark:text-gray-50">Masuk ke dashboard Anda</h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Gunakan email dan kata sandi yang terdaftar. Pastikan akun Anda aktif sebelum melanjutkan.</p>
                            </div>

                            @if ($errors->any())
                                <div class="rounded-2xl border border-red-200 bg-red-50/70 px-4 py-3 text-sm text-red-800 dark:border-red-700 dark:bg-red-900/40 dark:text-red-100">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ isset($type) && $type === 'admin' ? route('admin.login') : route('instructor.login') }}" class="space-y-6">
                                @csrf
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span>Email</span>
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        class="mt-2 block w-full rounded-2xl border border-gray-200 bg-white/80 px-4 py-3 text-sm text-gray-900 shadow-sm ring-1 ring-transparent transition focus:border-orange-400 focus:outline-none focus:ring-orange-200 dark:border-gray-700 dark:bg-gray-800/80 dark:text-gray-100 dark:focus:border-orange-400"
                                        placeholder="admin@sukarobot.com"
                                        required
                                        autofocus
                                    />
                                </label>

                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span>Password</span>
                                    <div class="relative mt-2" x-data="{ showPassword: false }">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            name="password"
                                            class="block w-full rounded-2xl border border-gray-200 bg-white/80 px-4 py-3 pr-12 text-sm text-gray-900 shadow-sm ring-1 ring-transparent transition focus:border-orange-400 focus:outline-none focus:ring-orange-200 dark:border-gray-700 dark:bg-gray-800/80 dark:text-gray-100 dark:focus:border-orange-400"
                                            placeholder="••••••••"
                                            required
                                        />
                                        <button
                                            type="button"
                                            class="absolute inset-y-0 right-3 inline-flex items-center text-gray-400 hover:text-orange-600 focus:text-orange-600 dark:text-gray-500 dark:hover:text-orange-300"
                                            @click="showPassword = !showPassword"
                                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                        >
                                            <svg x-show="!showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                                                <circle cx="12" cy="12" r="3" fill="currentColor" />
                                            </svg>
                                            <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18m-3.35-3.35C15.69 18.49 13.9 19 12 19c-4.477 0-8.268-2.943-9.542-7a13.227 13.227 0 0 1 4.258-6.036m4.147-1.753a9.74 9.74 0 0 1 1.137-.211C13.322 4.005 13.66 4 14 4c4.477 0 8.268 2.943 9.542 7-.355 1.132-.9 2.182-1.606 3.113M9.88 9.88A3 3 0 0 0 12 15a2.98 2.98 0 0 0 1.697-.526" />
                                            </svg>
                                        </button>
                                    </div>
                                </label>

                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between text-sm">
                                    <label class="inline-flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <input
                                            type="checkbox"
                                            name="remember"
                                            class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500 dark:border-gray-600"
                                        />
                                        <span>Ingat saya</span>
                                    </label>
                                    @if(isset($type) && $type === 'instructor')
                                        <a
                                            class="font-semibold text-orange-600 hover:text-orange-700 dark:text-orange-300 dark:hover:text-orange-200"
                                            href="{{ route('client.reset-password') }}"
                                        >
                                            Lupa password?
                                        </a>
                                    @endif
                                </div>

                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-orange-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-200 transition hover:-translate-y-0.5 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300 active:translate-y-0 dark:shadow-none"
                                >
                                    Masuk
                                </button>
                            </form>

                            <div class="flex items-center gap-3 text-xs text-gray-400 dark:text-gray-500">
                                <span class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent dark:via-gray-700"></span>
                                <span>Pastikan Anda berada di halaman login yang benar.</span>
                                <span class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent dark:via-gray-700"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

