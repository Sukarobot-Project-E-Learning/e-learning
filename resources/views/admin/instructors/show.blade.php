@extends('admin.layouts.app')

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
                    <form action="{{ route('admin.instructor-applications.approve', $application->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                        @csrf
                        <button type="submit" 
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                            Terima
                        </button>
                    </form>
                    <form action="{{ route('admin.instructor-applications.reject', $application->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                        @csrf
                        <button type="submit" 
                                class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                            Tolak
                        </button>
                    </form>
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
                        <span class="px-3 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                            Disetujui
                        </span>
                    @elseif($application->status === 'rejected')
                        <span class="px-3 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                            Ditolak
                        </span>
                    @else
                        <span class="px-3 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:bg-orange-700 dark:text-orange-100">
                            Menunggu Persetujuan
                        </span>
                    @endif
                </div>

                <!-- User Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Column 1 -->
                    <div class="space-y-4">
                         <!-- Nama -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Lengkap
                            </label>
                            <div class="flex items-center px-4 py-3 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                @if($application->avatar)
                                    <img class="w-8 h-8 rounded-full mr-3 object-cover" src="{{ asset($application->avatar) }}" alt="Avatar">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 mr-3"></div>
                                @endif
                                {{ $application->name }}
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- CV -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Curriculum Vitae (CV)
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
                                                <p class="text-xs text-gray-500">PDF Document</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset($application->cv_path) }}" target="_blank" class="px-3 py-1 text-sm text-blue-600 bg-blue-100 rounded hover:bg-blue-200 focus:outline-none">
                                            Lihat File
                                        </a>
                                    </div>
                                @else
                                    <div class="w-full p-4 text-center rounded-lg bg-gray-200 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                                        <p class="text-gray-500 dark:text-gray-400">Tidak ada file CV</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- KTP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kartu Tanda Penduduk (KTP)
                            </label>
                            <div class="w-full">
                                @if(isset($application->ktp_path) && $application->ktp_path)
                                    <div class="relative group">
                                         <img src="{{ asset($application->ktp_path) }}" 
                                             alt="KTP" 
                                             class="w-full h-48 object-cover rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer hover:opacity-75 transition-opacity duration-200"
                                             onclick="window.open('{{ asset($application->ktp_path) }}', '_blank')">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 pointer-events-none">
                                            <span class="bg-black bg-opacity-50 text-white px-3 py-1 rounded-lg text-sm">Klik untuk memperbesar</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-48 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center border border-gray-300 dark:border-gray-600">
                                        <div class="text-center">
                                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884.342 1.638.905 2.193m3.09 0C13.658 7.638 14 6.884 14 6m0 0v5m0-5h5m-5 0v5"></path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400">Tidak ada file KTP</p>
                                        </div>
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
