@extends('admin.layouts.app')

@section('title', 'Detail Bukti Program')

@section('content')

    <div class="container px-6 mx-auto">

        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Bukti Program</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.program-proofs.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    @if(isset($proof->status) && $proof->status == 'pending')
                    <button type="button" id="btn-accept"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none cursor-pointer">
                        Terima
                    </button>
                    @endif
                    @if(isset($proof->status) && $proof->status != 'accepted')
                    <button type="button" id="btn-reject"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none cursor-pointer">
                        Tolak
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const acceptBtn = document.getElementById('btn-accept');
            const rejectBtn = document.getElementById('btn-reject');
            const proofId = {{ $proof->id ?? 0 }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Terima Bukti Program?',
                        text: 'Bukti program akan diterima dan sertifikat akan di-generate jika template tersedia.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#16a34a',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Terima!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ url('admin/program-proofs') }}/${proofId}/accept`, {
                                method: 'POST',
                                headers: { 
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message || 'Bukti program berhasil diterima.', timer: 2000, showConfirmButton: false })
                                    .then(() => window.location.href = '{{ route("admin.program-proofs.index") }}');
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Terjadi kesalahan' });
                                }
                            })
                            .catch(() => Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan' }));
                        }
                    });
                });
            }

            if (rejectBtn) {
                rejectBtn.addEventListener('click', function() {
                    Swal.fire({
                        title: 'Tolak Bukti Program?',
                        text: 'Data akan ditolak dan dihapus permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Tolak!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ url('admin/program-proofs') }}/${proofId}/reject`, {
                                method: 'POST',
                                headers: { 
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({ icon: 'success', title: 'Ditolak!', text: data.message || 'Bukti program berhasil ditolak.', timer: 2000, showConfirmButton: false })
                                    .then(() => window.location.href = '{{ route("admin.program-proofs.index") }}');
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Terjadi kesalahan' });
                                }
                            })
                            .catch(() => Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan' }));
                        }
                    });
                });
            }
        });
        </script>

        <!-- Detail Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <div class="px-6 py-6 space-y-6">

                <!-- Jenis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jenis
                    </label>
                    <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded dark:bg-green-700 dark:text-green-100">
                            Online
                        </span>
                    </div>
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama
                    </label>
                    <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                        {{ $proof->user_name ?? 'Nama User' }}
                    </div>
                </div>

                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Judul
                    </label>
                    <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                        {{ $proof->program_title ?? 'Judul Program' }}
                    </div>
                </div>

                <!-- Tanggal & Waktu Section -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Tanggal & Waktu</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Mulai
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                {{ $proof->start_date ?? '20/09/2025' }}
                            </div>
                        </div>

                        <!-- Waktu Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Waktu Mulai
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                {{ $proof->start_time ?? '09:00 AM' }}
                            </div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Berakhir
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                {{ $proof->end_date ?? '20/09/2025' }}
                            </div>
                        </div>

                        <!-- Waktu Berakhir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Waktu Berakhir
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                {{ $proof->end_time ?? '11:00 AM' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumentasi Section -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Dokumentasi / Bukti Program
                    </label>
                    <div class="w-full">
                        @if(isset($proof->documentation) && $proof->documentation)
                            @php
                                // Check if file is in storage or old public path
                                $docUrl = '';
                                if (str_starts_with($proof->documentation, 'bukti-program/')) {
                                    $docUrl = asset('storage/' . $proof->documentation);
                                } elseif (str_starts_with($proof->documentation, 'images/')) {
                                    $docUrl = asset($proof->documentation);
                                } else {
                                    $docUrl = asset('storage/' . $proof->documentation);
                                }
                                
                                $extension = strtolower(pathinfo($proof->documentation, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            
                            @if($isImage)
                                <img src="{{ $docUrl }}" 
                                     alt="Documentation" 
                                     class="w-full max-w-lg h-auto rounded-lg border border-gray-300 dark:border-gray-600 object-cover cursor-pointer mb-4"
                                     onclick="window.open('{{ $docUrl }}', '_blank')">
                            @endif
                            
                            <a href="{{ $docUrl }}" 
                               download 
                               data-turbo="false"
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Bukti Program
                            </a>
                        @else
                            <div class="w-full h-32 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center border border-gray-300 dark:border-gray-600">
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada dokumentasi</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

