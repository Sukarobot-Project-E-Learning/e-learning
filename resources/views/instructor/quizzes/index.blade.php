@extends('panel.layouts.app')

@section('title', 'Tugas Akhir')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100">
        <div class="container px-6 py-6 mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Tugas Akhir</h1>
                    <p class="mt-1 text-sm text-slate-500">Pilih program untuk menilai tugas akhir siswa.</p>
                </div>
                <div class="rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-slate-200">
                    Total Program: {{ $programs->count() }}
                </div>
            </div>

            @if($programs->isEmpty())
                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-50">
                        <svg class="h-7 w-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada program dengan tugas akhir.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($programs as $program)
                        @php
                            $programImage = $program->image
                                ? (str_starts_with($program->image, 'images/')
                                    ? asset($program->image)
                                    : asset('storage/' . $program->image))
                                : asset('assets/elearning/client/img/home1.jpeg');
                            $detailUrl = route('instructor.programs.lms.submissions.index', ['program' => $program->id]);
                        @endphp
                        <a href="{{ $detailUrl }}" class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="h-36 w-full bg-cover bg-center" style="background-image: url('{{ $programImage }}');"></div>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h2 class="text-base font-bold text-slate-900 group-hover:text-blue-600">{{ $program->program }}</h2>
                                        <p class="mt-1 text-xs font-semibold text-slate-500">Tugas Akhir: {{ $program->final_assignment_count ? 'Tersedia' : 'Belum ada' }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3 text-xs font-semibold text-slate-600">
                                    <div class="rounded-xl bg-slate-50 px-3 py-2">
                                        <p class="text-[11px] uppercase text-slate-400">Dikumpulkan</p>
                                        <p class="mt-1 text-sm font-bold text-slate-800">{{ $program->submission_count }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-blue-600">
                                    Lihat Submisi
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
