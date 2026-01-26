@extends('panel.layouts.app')

@section('title', 'Detail Pengajuan Program')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Detail Program</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $approval->title ?? 'N/A' }}</p>
                </div>
                <a href="{{ route('admin.program-approvals.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @if($approval->status == 'pending')
            <span class="px-4 py-2 text-sm font-semibold text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-700 dark:text-yellow-100">
                Status: Menunggu Persetujuan
            </span>
            @elseif($approval->status == 'approved')
            <span class="px-4 py-2 text-sm font-semibold text-green-700 bg-green-100 rounded-lg dark:bg-green-700 dark:text-green-100">
                Status: Disetujui
            </span>
            @else
            <span class="px-4 py-2 text-sm font-semibold text-red-700 bg-red-100 rounded-lg dark:bg-red-700 dark:text-red-100">
                Status: Ditolak
            </span>
            @endif
        </div>

        <!-- Program Details Card -->
        <div class="grid gap-6 mb-8 md:grid-cols-2">
            <!-- Left Column -->
            <div class="w-full overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Informasi Program</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Program</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->title ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->category ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Program</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($approval->type ?? '-') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->price_note ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $approval->description ?? '-' }}</p>
                        </div>

                        @if($approval->image)
                        @php
                            $imageUrl = str_starts_with($approval->image, 'images/') 
                                ? asset($approval->image) 
                                : asset('storage/' . $approval->image);
                        @endphp
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar Program</label>
                            <img src="{{ $imageUrl }}" alt="{{ $approval->title }}" class="mt-2 rounded-lg max-w-full h-auto max-h-64">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="w-full overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Informasi Instruktur & Jadwal</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Instruktur</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->instructor_name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->instructor_email ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telepon</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->instructor_phone ?? '-' }}</p>
                        </div>

                        @if($approval->type == 'offline')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $approval->full_address ?? '' }}
                                @if($approval->village), {{ $approval->village }}@endif
                                @if($approval->district), {{ $approval->district }}@endif
                                @if($approval->city), {{ $approval->city }}@endif
                                @if($approval->province), {{ $approval->province }}@endif
                            </p>
                        </div>
                        @endif

                        @if($approval->start_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ date('d F Y', strtotime($approval->start_date)) }}
                                @if($approval->start_time) - {{ date('H:i', strtotime($approval->start_time)) }}@endif
                            </p>
                        </div>
                        @endif

                        @if($approval->end_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Berakhir</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ date('d F Y', strtotime($approval->end_date)) }}
                                @if($approval->end_time) - {{ date('H:i', strtotime($approval->end_time)) }}@endif
                            </p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Diajukan</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->created_at ? date('d F Y H:i', strtotime($approval->created_at)) : '-' }}</p>
                        </div>

                        @if($approval->status == 'approved' && $approval->approved_by_name)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Disetujui Oleh</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $approval->approved_by_name }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $approval->approved_at ? date('d F Y H:i', strtotime($approval->approved_at)) : '' }}</p>
                        </div>
                        @endif

                        @if($approval->status == 'rejected' && $approval->rejection_reason)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penolakan</label>
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $approval->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($approval->status == 'pending')
        <div class="flex gap-4 mb-8">
            <form action="{{ route('admin.program-approvals.approve', $approval->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin menyetujui program ini? Program akan langsung dipublikasikan.')"
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Setujui Program
                </button>
            </form>

            <button type="button" 
                    onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Tolak Program
            </button>
        </div>

        <!-- Reject Modal -->
        <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
                <div class="relative bg-white rounded-lg shadow-xl dark:bg-gray-800 max-w-md w-full p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Tolak Program</h3>
                    <form action="{{ route('admin.program-approvals.reject', $approval->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penolakan</label>
                            <textarea name="rejection_reason" 
                                      rows="4" 
                                      required
                                      class="w-full px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                                      placeholder="Masukkan alasan penolakan program..."></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" 
                                    onclick="document.getElementById('rejectModal').classList.add('hidden')"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

