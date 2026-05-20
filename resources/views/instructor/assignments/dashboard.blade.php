@extends('panel.layouts.app')

@section('title', 'Dashboard Tugas Akhir')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100">
        <div class="container px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Tugas Akhir</h2>
                    <p class="text-sm text-gray-500">Pantau performa tugas akhir untuk setiap program.</p>
                </div>
                <a href="{{ route('instructor.assignments.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-600 bg-white rounded-xl border border-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kelola Tugas Akhir
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
                <form method="GET" action="{{ route('instructor.assignments.dashboard') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-gray-500">Program</label>
                        <select name="program_id" class="mt-1 w-full py-2.5 px-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @forelse($programs as $program)
                                <option value="{{ $program->id }}" {{ $selectedProgramId == $program->id ? 'selected' : '' }}>
                                    {{ $program->program }}
                                </option>
                            @empty
                                <option value="">Tidak ada program</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="pt-5 sm:pt-6">
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            @if(!$selectedProgramId)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                    Pilih program untuk melihat dashboard tugas akhir.
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs font-semibold text-gray-500">Total Peserta</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_students']) }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs font-semibold text-gray-500">Sudah Submit</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['submitted']) }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs font-semibold text-gray-500">Completion Rate</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['completion_rate'] }}%</p>
                        <div class="mt-2 h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-blue-600" style="width: {{ $stats['completion_rate'] }}%"></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <p class="text-xs font-semibold text-gray-500">Rata-Rata Skor</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['avg_score'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">Sudah Submit</h3>
                            <p class="text-xs text-gray-500">{{ $submittedStudents->count() }} siswa</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($submittedStudents as $student)
                                <div class="px-6 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                    </div>
                                    <span class="text-xs text-green-600 font-semibold">Submitted</span>
                                </div>
                            @empty
                                <div class="px-6 py-6 text-sm text-gray-500">Belum ada siswa yang submit.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">Belum Submit</h3>
                            <p class="text-xs text-gray-500">{{ $pendingStudents->count() }} siswa</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($pendingStudents as $student)
                                <div class="px-6 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                    </div>
                                    <span class="text-xs text-amber-600 font-semibold">Pending</span>
                                </div>
                            @empty
                                <div class="px-6 py-6 text-sm text-gray-500">Semua siswa sudah submit.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Submission Terbaru</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Skor</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentSubmissions as $submission)
                                    <tr>
                                        <td class="px-6 py-3 text-sm text-gray-700">
                                            {{ $submission->user->name ?? 'Siswa' }}
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-700">
                                            {{ $submission->assignment->title ?? '-' }}
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-700">
                                            {{ $submission->score ?? '-' }}
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-700">
                                            {{ optional($submission->submitted_at)->format('d M Y H:i') ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-6 text-sm text-gray-500 text-center">Belum ada submission.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
