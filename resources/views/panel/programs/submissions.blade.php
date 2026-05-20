@extends('panel.layouts.app')

@section('title', 'Nilai Tugas Akhir - ' . $program->program)

@section('content')
    <div class="min-h-screen bg-gradient-to-br
        from-slate-50 via-blue-50/30 to-slate-100
        dark:from-gray-900 dark:via-gray-900
        dark:to-gray-800">
        <div class="container px-4 py-6 mx-auto
            max-w-7xl sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route($prefix . '.quizzes.index') }}"
                    class="inline-flex items-center gap-2
                        text-sm font-medium text-gray-500
                        hover:text-blue-600 transition-colors
                        mb-4">
                    <svg class="w-4 h-4" fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold
                    text-gray-900 dark:text-white">
                    Nilai Tugas Akhir
                </h1>
                <p class="mt-1 text-sm text-gray-500
                    dark:text-gray-400">
                    Kursus: {{ $program->program }}
                </p>
            </div>

            @if($assignments->isEmpty())
                <div class="bg-white dark:bg-gray-800
                    rounded-2xl shadow-sm border
                    border-gray-100 dark:border-gray-700
                    p-12 text-center">
                    <div class="w-16 h-16 bg-purple-50
                        dark:bg-purple-900/30 rounded-full
                        flex items-center justify-center
                        mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-400"
                            fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0
                                    01-2-2V5a2 2 0 012-2h5.586a1
                                    1 0 01.707.293l5.414 5.414a1 1
                                    0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold
                        text-gray-900 dark:text-white mb-1">
                        Belum Ada Tugas Akhir
                    </h3>
                    <p class="text-sm text-gray-500
                        dark:text-gray-400">
                        Program ini belum memiliki tugas akhir.
                    </p>
                </div>
            @else
                @foreach($assignments as $assignment)
                    <div class="mb-8 bg-white dark:bg-gray-800
                        rounded-2xl shadow-sm border
                        border-gray-100 dark:border-gray-700
                        overflow-hidden">
                        {{-- Assignment Header --}}
                        <div class="px-6 py-5 border-b
                            border-gray-100
                            dark:border-gray-700
                            bg-gradient-to-r from-purple-50
                            to-blue-50 dark:from-gray-800
                            dark:to-gray-800">
                            <div class="flex flex-col sm:flex-row
                                sm:items-center
                                sm:justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-bold
                                        text-gray-900
                                        dark:text-white">
                                        Judul Tugas Akhir: {{ $assignment->title }}
                                    </h2>
                                    <div class="flex flex-wrap
                                        items-center gap-3 mt-2
                                        text-xs font-medium
                                        text-gray-500">
                                        <span class="inline-flex
                                            items-center gap-1
                                            bg-white
                                            dark:bg-gray-700
                                            px-2.5 py-1
                                            rounded-full">
                                            <i class="fas
                                                fa-trophy
                                                text-yellow-500">
                                            </i>
                                            KKM:
                                            {{ $assignment->passing_score }}
                                        </span>
                                        <span class="inline-flex
                                            items-center gap-1
                                            bg-white
                                            dark:bg-gray-700
                                            px-2.5 py-1
                                            rounded-full">
                                            <i class="fas
                                                fa-users
                                                text-blue-500">
                                            </i>
                                            {{ $assignment->submissions->count() }}
                                            Tugas Dikumpulkan
                                        </span>
                                        @if($assignment->due_date)
                                            <span class="inline-flex
                                                items-center gap-1
                                                bg-white
                                                dark:bg-gray-700
                                                px-2.5 py-1
                                                rounded-full">
                                                <i class="far
                                                    fa-calendar-alt
                                                    text-gray-400">
                                                </i>
                                                {{ $assignment->due_date->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submissions Table --}}
                        @if($assignment->submissions->isEmpty())
                            <div class="px-6 py-10 text-center">
                                <p class="text-sm text-gray-500
                                    dark:text-gray-400">
                                    Belum ada submisi.
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-50
                                            dark:bg-gray-800/50">
                                            <th class="px-6 py-3
                                                text-left text-xs
                                                font-semibold
                                                text-gray-600
                                                dark:text-gray-400
                                                uppercase
                                                tracking-wider">
                                                Siswa
                                            </th>
                                            <th class="px-6 py-3
                                                text-left text-xs
                                                font-semibold
                                                text-gray-600
                                                dark:text-gray-400
                                                uppercase
                                                tracking-wider">
                                                File
                                            </th>
                                            <th class="px-6 py-3
                                                text-center text-xs
                                                font-semibold
                                                text-gray-600
                                                dark:text-gray-400
                                                uppercase
                                                tracking-wider">
                                                Tanggal
                                            </th>
                                            <th class="px-6 py-3
                                                text-center text-xs
                                                font-semibold
                                                text-gray-600
                                                dark:text-gray-400
                                                uppercase
                                                tracking-wider">
                                                Nilai
                                            </th>
                                            <th class="px-6 py-3
                                                text-center text-xs
                                                font-semibold
                                                text-gray-600
                                                dark:text-gray-400
                                                uppercase
                                                tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3
                                                text-center text-xs
                                                font-semibold
                                                text-gray-600
                                                dark:text-gray-400
                                                uppercase
                                                tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y
                                        divide-gray-100
                                        dark:divide-gray-700">
                                        @foreach($assignment->submissions as $sub)
                                            @php
                                                $hasPassed = $sub->score !== null
                                                    && $sub->score >= $assignment->passing_score;
                                                $isGraded = $sub->score !== null;
                                            @endphp
                                            <tr class="hover:bg-gray-50
                                                dark:hover:bg-gray-800/50
                                                transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex
                                                        items-center
                                                        gap-3">
                                                        @if($sub->user)
                                                            <img
                                                                src="{{ $sub->user->avatar_url }}"
                                                                alt="{{ $sub->user->name ?? 'Siswa' }}"
                                                                class="w-8 h-8 rounded-full object-cover border border-blue-100 dark:border-blue-900/30"
                                                            >
                                                        @else
                                                            <div class="w-8
                                                                h-8
                                                                bg-blue-100
                                                                dark:bg-blue-900/30
                                                                rounded-full
                                                                flex
                                                                items-center
                                                                justify-center
                                                                text-xs
                                                                font-bold
                                                                text-blue-600">
                                                                ?
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-sm
                                                                font-medium
                                                                text-gray-900
                                                                dark:text-white">
                                                                {{ $sub->user->name ?? 'N/A' }}
                                                            </p>
                                                            <p class="text-xs
                                                                text-gray-500">
                                                                {{ $sub->user->email ?? '' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ asset('storage/' . $sub->file_path) }}"
                                                        target="_blank"
                                                        class="inline-flex
                                                            items-center
                                                            gap-1.5
                                                            text-sm
                                                            text-blue-600
                                                            hover:text-blue-700
                                                            font-medium">
                                                        <i class="fas
                                                            fa-download
                                                            text-xs">
                                                        </i>
                                                        {{ Str::limit($sub->file_name, 25) }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4
                                                    text-center text-sm
                                                    text-gray-500">
                                                    {{ optional($sub->submitted_at)->format('d M Y H:i') ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4
                                                    text-center">
                                                    @if($isGraded)
                                                        <span class="text-lg
                                                            font-bold
                                                            {{ $hasPassed ? 'text-green-600' : 'text-red-500' }}">
                                                            {{ $sub->score }}
                                                        </span>
                                                    @else
                                                        <span class="text-sm
                                                            text-gray-400
                                                            italic">
                                                            —
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4
                                                    text-center">
                                                    @if($isGraded)
                                                        @if($hasPassed)
                                                            <span class="inline-flex
                                                                items-center
                                                                px-2.5
                                                                py-0.5
                                                                rounded-full
                                                                text-xs
                                                                font-semibold
                                                                bg-green-100
                                                                text-green-800">
                                                                Lulus
                                                            </span>
                                                        @else
                                                            <span class="inline-flex
                                                                items-center
                                                                px-2.5
                                                                py-0.5
                                                                rounded-full
                                                                text-xs
                                                                font-semibold
                                                                bg-red-100
                                                                text-red-800">
                                                                Belum Lulus
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="inline-flex
                                                            items-center
                                                            px-2.5
                                                            py-0.5
                                                            rounded-full
                                                            text-xs
                                                            font-semibold
                                                            bg-yellow-100
                                                            text-yellow-800">
                                                            Belum Dinilai
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4
                                                    text-center">
                                                    <button type="button"
                                                        onclick="openGradeModal({{ $sub->id }}, {{ $sub->score ?? 'null' }}, '{{ addslashes($sub->feedback ?? '') }}')"
                                                        class="inline-flex
                                                            items-center
                                                            gap-1.5 px-3
                                                            py-1.5
                                                            text-xs
                                                            font-medium
                                                            text-white
                                                            bg-blue-600
                                                            hover:bg-blue-700
                                                            rounded-lg
                                                            transition-colors">
                                                        <i class="fas
                                                            fa-pen
                                                            text-[10px]">
                                                        </i>
                                                        Nilai
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Grade Modal --}}
    <div id="gradeModal"
        class="hidden fixed inset-0 z-50 flex
            items-center justify-center p-4
            bg-black/50 backdrop-blur-sm
            transition-opacity duration-300">
        <div class="w-full max-w-md bg-white
            dark:bg-gray-800 rounded-2xl shadow-2xl
            overflow-hidden transform transition-all
            duration-300">
            <div class="px-6 py-4 border-b
                border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold
                    text-gray-900 dark:text-white">
                    Beri Nilai
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="grade_submission_id">
                <div>
                    <label class="block text-sm font-medium
                        text-gray-700 dark:text-gray-300 mb-2">
                        Nilai (0 - 100)
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="grade_score"
                        min="0" max="100"
                        class="w-full px-4 py-2.5 rounded-xl
                            border border-gray-300
                            dark:border-gray-600
                            bg-white dark:bg-gray-700
                            text-gray-800 dark:text-white
                            focus:ring-2 focus:ring-blue-500
                            transition-colors text-lg
                            font-bold text-center"
                        placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium
                        text-gray-700 dark:text-gray-300 mb-2">
                        Feedback (Opsional)
                    </label>
                    <textarea id="grade_feedback" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl
                            border border-gray-300
                            dark:border-gray-600
                            bg-white dark:bg-gray-700
                            text-gray-800 dark:text-white
                            focus:ring-2 focus:ring-blue-500
                            transition-colors"
                        placeholder="Berikan catatan untuk siswa..."
                    ></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t
                border-gray-200 dark:border-gray-700
                bg-gray-50 dark:bg-gray-800/50
                flex justify-end gap-3">
                <button type="button"
                    onclick="closeGradeModal()"
                    class="px-4 py-2 text-sm font-medium
                        text-gray-700 bg-gray-100
                        rounded-xl hover:bg-gray-200
                        dark:bg-gray-700
                        dark:text-gray-300
                        dark:hover:bg-gray-600
                        transition-colors">
                    Batal
                </button>
                <button type="button"
                    onclick="submitGrade()"
                    id="gradeSubmitBtn"
                    class="px-5 py-2 text-sm font-medium
                        text-white bg-blue-600 rounded-xl
                        hover:bg-blue-700
                        transition-colors">
                    Simpan Nilai
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        var programId = {{ $program->id }};
        var gradePrefix = '{{ $prefix }}';

        function openGradeModal(subId, score, feedback) {
            document.getElementById('grade_submission_id')
                .value = subId;
            document.getElementById('grade_score')
                .value = score !== null ? score : '';
            document.getElementById('grade_feedback')
                .value = feedback || '';

            var modal = document.getElementById(
                'gradeModal'
            );
            modal.classList.remove('hidden');
        }

        function closeGradeModal() {
            var modal = document.getElementById(
                'gradeModal'
            );
            modal.classList.add('hidden');
        }

        function submitGrade() {
            var subId = document.getElementById(
                'grade_submission_id'
            ).value;
            var score = document.getElementById(
                'grade_score'
            ).value;
            var feedback = document.getElementById(
                'grade_feedback'
            ).value;
            var btn = document.getElementById(
                'gradeSubmitBtn'
            );

            if (!score || score < 0 || score > 100) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nilai Tidak Valid',
                    text: 'Nilai harus antara 0-100.',
                });
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            var url = '/' + gradePrefix
                + '/programs/' + programId
                + '/lms/submissions/' + subId
                + '/grade';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    score: parseInt(score, 10),
                    feedback: feedback,
                }),
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.textContent = 'Simpan Nilai';

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500,
                    }).then(function() {
                        window.location.reload();
                    });
                    closeGradeModal();
                } else {
                    var errMsg = 'Gagal menyimpan nilai.';
                    if (data.errors) {
                        var msgs = [];
                        for (var k in data.errors) {
                            msgs.push(
                                data.errors[k].join(', ')
                            );
                        }
                        errMsg = msgs.join('\n');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errMsg,
                    });
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.textContent = 'Simpan Nilai';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan.',
                });
            });
        }

        // Close modal on backdrop click
        document.getElementById('gradeModal')
            .addEventListener('click', function(e) {
                if (e.target === this) {
                    closeGradeModal();
                }
            });
    </script>
    @endpush
@endsection
