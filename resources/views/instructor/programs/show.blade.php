@extends('panel.layouts.app')

@section('title', 'Detail Program')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Program ini sudah disetujui dan tidak dapat diedit.
                    </p>
                </div>
                <a href="{{ route('instructor.programs.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            <span class="px-3 py-1.5 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                ✓ Disetujui
            </span>
        </div>

        <!-- Content Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <div class="px-6 py-6 space-y-6">

                <!-- Informasi Dasar -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Judul Program</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ ucfirst($submission->category ?? '-') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Pelaksanaan</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ ucfirst($submission->type ?? '-') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Harga</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">Rp {{ number_format($submission->price ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                        <p class="mt-1 text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $submission->description ?? '-' }}</p>
                    </div>
                </div>

                <!-- Jadwal -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Jadwal Pelaksanaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->start_date ? \Carbon\Carbon::parse($submission->start_date)->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Mulai</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->start_time ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Berakhir</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->end_date ? \Carbon\Carbon::parse($submission->end_date)->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Waktu Berakhir</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->end_time ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lokasi (if offline) -->
                @if($submission->type === 'offline')
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Lokasi Pelaksanaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Provinsi</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->province ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kota/Kabupaten</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->city ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kecamatan</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->district ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kelurahan/Desa</label>
                            <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->village ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Lengkap</label>
                        <p class="mt-1 text-gray-800 dark:text-gray-200">{{ $submission->full_address ?? '-' }}</p>
                    </div>
                </div>
                @endif

                <!-- Tools -->
                @if(!empty($submission->tools))
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Tools yang Digunakan</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($submission->tools as $tool)
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm dark:bg-blue-900 dark:text-blue-300">{{ $tool }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Benefits -->
                @if(!empty($submission->benefits))
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Kamu Akan Mendapatkan</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($submission->benefits as $benefit)
                            <span class="px-3 py-1 bg-green-50 text-green-700 rounded-lg text-sm dark:bg-green-900 dark:text-green-300">{{ $benefit }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Materials -->
                @if(!empty($submission->materials))
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Materi Pembelajaran</h3>
                    <div class="space-y-4">
                        @foreach($submission->materials as $index => $material)
                            <div class="p-4 border border-gray-200 rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">Hari {{ $index + 1 }}</span>
                                    @if(!empty($material['duration']))
                                        @php
                                            $durationParts = explode(':', $material['duration']);
                                            $hours = intval($durationParts[0] ?? 0);
                                            $minutes = intval($durationParts[1] ?? 0);
                                            $durationText = '';
                                            if ($hours > 0) $durationText .= $hours . ' Jam';
                                            if ($minutes > 0) $durationText .= ($hours > 0 ? ' ' : '') . $minutes . ' Menit';
                                        @endphp
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded dark:bg-gray-800">{{ $durationText }}</span>
                                    @endif
                                </div>
                                <h4 class="font-medium text-gray-800 dark:text-gray-200">{{ $material['title'] ?? 'Tanpa Judul' }}</h4>
                                @if(!empty($material['description']))
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $material['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Image -->
                @if($submission->image)
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Gambar Program</h3>
                    @php
                        $submissionImageUrl = str_starts_with($submission->image, 'images/') 
                            ? asset($submission->image) 
                            : asset('storage/' . $submission->image);
                    @endphp
                    <img src="{{ $submissionImageUrl }}" alt="Program Image" class="mt-2 rounded-lg max-w-full h-auto max-h-64 object-cover">
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection
