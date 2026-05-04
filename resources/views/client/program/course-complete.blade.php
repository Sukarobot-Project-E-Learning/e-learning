@extends('client.main')

@section('css')
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    @keyframes float-up {
      0% { transform: translateY(20px); opacity: 0; }
      100% { transform: translateY(0); opacity: 1; }
    }
    @keyframes pop-in {
      0% { transform: scale(0.5); opacity: 0; }
      70% { transform: scale(1.1); opacity: 1; }
      100% { transform: scale(1); opacity: 1; }
    }
    @keyframes shine {
      0% { background-position: -200% center; }
      100% { background-position: 200% center; }
    }
    @keyframes confetti-fall {
      0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
      100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }
    @keyframes pulse-ring {
      0% { transform: scale(0.8); opacity: 0.8; }
      100% { transform: scale(2); opacity: 0; }
    }
    .animate-float-up { animation: float-up 0.8s ease-out both; }
    .animate-pop-in { animation: pop-in 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) both; }
    .animate-shine {
      background: linear-gradient(90deg, #1e293b 0%, #3b82f6 25%, #8b5cf6 50%, #3b82f6 75%, #1e293b 100%);
      background-size: 200% auto;
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: shine 3s linear infinite;
    }
    .animate-pulse-ring {
      animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .confetti {
      position: absolute;
      width: 10px;
      height: 10px;
      animation: confetti-fall 4s linear infinite;
    }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
  </style>
@endsection

@section('body')
  <section class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-16 px-4" style="font-family: 'Inter', sans-serif;">

    {{-- Confetti decorations --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
      <div class="confetti bg-blue-500 rounded-sm" style="left: 10%; animation-delay: 0s;"></div>
      <div class="confetti bg-yellow-400 rounded-full" style="left: 20%; animation-delay: 0.5s;"></div>
      <div class="confetti bg-pink-500 rounded-sm" style="left: 30%; animation-delay: 1s;"></div>
      <div class="confetti bg-emerald-500 rounded-full" style="left: 45%; animation-delay: 1.5s;"></div>
      <div class="confetti bg-purple-500 rounded-sm" style="left: 60%; animation-delay: 0.3s;"></div>
      <div class="confetti bg-orange-400 rounded-full" style="left: 75%; animation-delay: 0.8s;"></div>
      <div class="confetti bg-blue-400 rounded-sm" style="left: 85%; animation-delay: 1.3s;"></div>
      <div class="confetti bg-yellow-500 rounded-full" style="left: 95%; animation-delay: 1.8s;"></div>
    </div>

    {{-- Decorative blurred background --}}
    <div class="pointer-events-none absolute -top-24 -left-24 h-96 w-96 rounded-full bg-blue-300/40 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-indigo-300/40 blur-3xl"></div>

    <div class="relative mx-auto max-w-3xl">

      @if(session('success'))
        <div class="animate-float-up mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-sm">
          ✓ {{ session('success') }}
        </div>
      @endif

      <div class="animate-float-up mt-8 rounded-3xl border border-white bg-white/80 p-8 shadow-2xl backdrop-blur-md sm:p-12">

        {{-- Trophy / Badge --}}
        <div class="relative mx-auto mb-8 flex h-28 w-28 items-center justify-center">
          <span class="absolute inset-0 rounded-full bg-blue-400 animate-pulse-ring"></span>
          <span class="absolute inset-0 rounded-full bg-blue-500/20 animate-pulse-ring" style="animation-delay: 0.5s;"></span>
          <div class="animate-pop-in relative flex h-28 w-28 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 5h14M7 5v6a5 5 0 0010 0V5M9 21h6M12 17v4M5 5a3 3 0 003 3M19 5a3 3 0 01-3 3"/>
            </svg>
          </div>
        </div>

        <div class="text-center">
          <p class="animate-float-up delay-300 mt-3 text-base text-slate-600 sm:text-lg">
            Selamat!!! anda telah menyelesaikan kursus ini
          </p>

          <div class="animate-float-up delay-400 mt-5 inline-block rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-3 ring-1 ring-blue-100">
            <p class="text-lg font-bold text-slate-900 sm:text-xl">
              {{ $program->program }}
            </p>
          </div>
        </div>

        {{-- Info card --}}
        <div class="animate-float-up delay-500 mt-10 rounded-2xl border border-slate-200 bg-slate-50/70 p-6">
          <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="text-sm leading-relaxed text-slate-700">
              <p class="font-semibold text-slate-900">Kirim Bukti Program kamu untuk mendapatkan sertifikat.</p>
              <p class="mt-1 text-slate-600">Setelah bukti diverifikasi oleh admin, sertifikat dapat diunduh pada halaman dashboard sertifikat.</p>
            </div>
          </div>
        </div>

        {{-- Action buttons --}}
        <div class="animate-float-up delay-500 mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
          @if(!$proof)
            <a href="{{ route('client.program.proof', $program->slug) }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl hover:-translate-y-0.5 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
              Kirim Bukti Program
            </a>
          @else
            <span class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-100 px-6 py-3 text-sm font-bold text-emerald-700 ring-1 ring-emerald-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
              Bukti Program Sudah Dikirim ({{ ucfirst($proof->status) }})
            </span>
          @endif

          <a href="{{ route('client.dashboard.program') }}"
             class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-400 hover:-translate-y-0.5 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Program Saya
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection
