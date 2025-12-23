@extends('instructor.layouts.app')

@section('title', 'Pengajuan Program')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Pengajuan Program</h2>
                <a href="{{ route('instructor.programs.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Program Baru
                </a>
            </div>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Kelola pengajuan program Anda. Program baru memerlukan persetujuan admin sebelum dipublikasikan.
            </p>
        </div>

        <!-- Status Legend -->
        <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan Status:</p>
            <div class="flex flex-wrap gap-4">
                <span class="inline-flex items-center gap-1 text-xs">
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">Menunggu</span>
                    <span class="text-gray-500">Bisa diedit</span>
                </span>
                <span class="inline-flex items-center gap-1 text-xs">
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full">Disetujui</span>
                    <span class="text-gray-500">Hanya bisa dilihat</span>
                </span>
                <span class="inline-flex items-center gap-1 text-xs">
                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full">Ditolak</span>
                    <span class="text-gray-500">Bisa diedit & ajukan ulang</span>
                </span>
            </div>
        </div>

        <!-- Programs Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Judul Program</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Tanggal Pengajuan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($submissions ?? [] as $submission)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $submission->title }}</p>
                                        @if($submission->rejection_reason)
                                        <p class="text-xs text-red-500 mt-1">Alasan: {{ $submission->rejection_reason }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $submission->category ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ ucfirst($submission->type ?? '-') }}</td>
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-xs">
                                @if($submission->status === 'pending')
                                <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                    Menunggu
                                </span>
                                @elseif($submission->status === 'approved')
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                    Disetujui
                                </span>
                                @elseif($submission->status === 'rejected')
                                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                    Ditolak
                                </span>
                                @else
                                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">
                                    {{ $submission->status }}
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3 text-sm">
                                    @if($submission->status === 'approved')
                                        {{-- Approved: Only View --}}
                                        <a href="{{ route('instructor.programs.show', $submission->id) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        {{-- Pending/Rejected: Can Edit --}}
                                        <a href="{{ route('instructor.programs.edit', $submission->id) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 dark:bg-green-900 dark:text-green-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        {{-- Delete for pending/rejected --}}
                                        <form action="{{ route('instructor.programs.destroy', $submission->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900 dark:text-red-300">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mb-2">Belum ada pengajuan program.</p>
                                    <a href="{{ route('instructor.programs.create') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 font-medium">
                                        Ajukan program pertama Anda
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($submissions) && method_exists($submissions, 'links'))
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-t dark:border-gray-700">
                {{ $submissions->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
