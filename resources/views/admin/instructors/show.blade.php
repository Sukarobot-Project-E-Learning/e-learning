@extends('panel.layouts.app')

@section('title', 'Detail Pengajuan Instruktur')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Detail Pengajuan Instruktur</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.instructor-applications.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    
                    @if($application->status === 'pending')
                    <button type="button" id="approveBtn"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        Terima
                    </button>
                    <form id="approveForm" action="{{ route('admin.instructor-applications.approve', $application->id) }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    
                    <button type="button" id="rejectBtn"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                        Tolak
                    </button>
                    <form id="rejectForm" action="{{ route('admin.instructor-applications.reject', $application->id) }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    
                    <script>
                        document.getElementById('approveBtn').addEventListener('click', function() {
                            Swal.fire({
                                title: 'Terima Pengajuan?',
                                text: 'Anda akan menyetujui pengajuan instruktur ini',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#22c55e',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Ya, Terima!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('approveForm').submit();
                                }
                            });
                        });
                        
                        document.getElementById('rejectBtn').addEventListener('click', function() {
                            Swal.fire({
                                title: 'Tolak Pengajuan?',
                                text: 'Anda akan menolak pengajuan instruktur ini',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Ya, Tolak!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('rejectForm').submit();
                                }
                            });
                        });
                    </script>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <div class="px-6 py-6 space-y-6">

                <!-- Status Badge -->
                <div class="flex justify-end">
                    @if($application->status === 'approved')
                        <span class="px-3 py-1 font-semibold leading-tight rounded-full" style="background-color: #dcfce7; color: #15803d;">
                            Disetujui
                        </span>
                    @elseif($application->status === 'rejected')
                        <span class="px-3 py-1 font-semibold leading-tight rounded-full" style="background-color: #fee2e2; color: #b91c1c;">
                            Ditolak
                        </span>
                    @else
                        <span class="px-3 py-1 font-semibold leading-tight rounded-full" style="background-color: #fed7aa; color: #c2410c;">
                            Menunggu Persetujuan
                        </span>
                    @endif
                </div>

                <!-- User Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                         <!-- Nama dengan Foto Profil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Lengkap
                            </label>
                            <div class="flex items-center px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                @if($application->avatar)
                                    @php
                                        $avatarUrl = '';
                                        if (str_starts_with($application->avatar, 'http')) {
                                            $avatarUrl = $application->avatar;
                                        } elseif (str_starts_with($application->avatar, 'images/')) {
                                            $avatarUrl = asset($application->avatar);
                                        } else {
                                            $avatarUrl = asset('storage/' . $application->avatar);
                                        }
                                    @endphp
                                    <img class="w-10 h-10 rounded-full mr-3 object-cover border-2 border-orange-200 cursor-pointer" 
                                         src="{{ $avatarUrl }}" 
                                         alt="Avatar"
                                         onclick="window.open('{{ $avatarUrl }}', '_blank')"
                                         title="Klik untuk melihat foto"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 mr-3 items-center justify-center hidden">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 mr-3 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                                <span class="font-semibold">{{ $application->name }}</span>
                                @if($application->avatar)
                                    <a href="{{ route('admin.instructor-applications.download', ['id' => $application->id, 'type' => 'avatar']) }}" 
                                      
                                       class="ml-auto px-2 py-1 text-xs text-blue-600 bg-blue-100 rounded hover:bg-blue-200 inline-flex items-center gap-1"
                                       title="Unduh foto profil">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Foto
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                {{ $application->email }}
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                No. Telepon
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                {{ $application->phone ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-4">
                        <!-- Keahlian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bidang Keahlian
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 h-32 overflow-y-auto">
                                {{ $application->skills }}
                            </div>
                        </div>

                        <!-- Bio -->
                         <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Biografi Singkat
                            </label>
                            <div class="px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 h-32 overflow-y-auto">
                                {{ $application->bio ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Dokumen Pendukung</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- CV (Private - tersimpan di folder private) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Curriculum Vitae (CV) <span class="text-xs text-orange-600">(Privat)</span>
                            </label>
                            <div class="w-full">
                                @if(isset($application->cv_path) && $application->cv_path)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="text-sm">
                                                <p class="font-semibold text-gray-700 dark:text-gray-200">File CV</p>
                                                <p class="text-xs text-gray-500">Dokumen Privat</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.instructor-applications.download', ['id' => $application->id, 'type' => 'cv']) }}" 
                                          
                                           class="px-3 py-1 text-sm text-blue-600 bg-blue-100 rounded hover:bg-blue-200 focus:outline-none inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Unduh
                                        </a>
                                    </div>
                                @else
                                    <div class="w-full p-4 text-center rounded-lg bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada file CV</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- KTP (Private - download only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kartu Tanda Penduduk (KTP) <span class="text-xs text-orange-600">(Privat)</span>
                            </label>
                            <div class="w-full">
                                @if(isset($application->ktp_path) && $application->ktp_path)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="text-sm">
                                                <p class="font-semibold text-gray-700 dark:text-gray-200">File KTP</p>
                                                <p class="text-xs text-gray-500">Dokumen Privat</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.instructor-applications.download', ['id' => $application->id, 'type' => 'ktp']) }}" 
                                          
                                           class="px-3 py-1 text-sm text-orange-600 bg-orange-100 rounded hover:bg-orange-200 focus:outline-none inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Unduh
                                        </a>
                                    </div>
                                @else
                                    <div class="w-full p-4 text-center rounded-lg bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada file KTP</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- NPWP (Private - download only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                NPWP <span class="text-xs text-orange-600">(Privat)</span>
                            </label>
                            <div class="w-full">
                                @if(isset($application->npwp_path) && $application->npwp_path)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="text-sm">
                                                <p class="font-semibold text-gray-700 dark:text-gray-200">File NPWP</p>
                                                <p class="text-xs text-gray-500">Dokumen Privat</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.instructor-applications.download', ['id' => $application->id, 'type' => 'npwp']) }}" 
                                          
                                           class="px-3 py-1 text-sm text-green-600 bg-green-100 rounded hover:bg-green-200 focus:outline-none inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Unduh
                                        </a>
                                    </div>
                                @else
                                    <div class="w-full p-4 text-center rounded-lg bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada file NPWP</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

