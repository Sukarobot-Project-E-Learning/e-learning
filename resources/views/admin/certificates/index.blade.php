@extends('admin.layouts.app')

@section('title', 'Template Sertifikat')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Template Sertifikat</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola template sertifikat per program. Sertifikat akan otomatis di-generate saat bukti program disetujui.</p>
                </div>
                <a href="{{ route('admin.certificates.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Template
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <!-- Templates Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Program</th>
                            <th class="px-4 py-3">Prefix Nomor</th>
                            <th class="px-4 py-3">Blanko</th>
                            <th class="px-4 py-3">Sertifikat Dibuat</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal Dibuat</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($templates as $template)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $template['program_name'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <code class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded">{{ $template['number_prefix'] }}</code>
                            </td>
                            <td class="px-4 py-3">
                                @if($template['template_path'])
                                    <img src="{{ $template['template_path'] }}" 
                                         alt="Blanko" 
                                         class="w-20 h-14 rounded object-cover border border-gray-300 dark:border-gray-600 cursor-pointer hover:opacity-80 transition"
                                         onclick="window.open('{{ $template['template_path'] }}', '_blank')">
                                @else
                                    <div class="w-20 h-14 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-300 dark:border-gray-500">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold leading-tight {{ $template['certificates_count'] > 0 ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300' }} rounded-full">
                                    {{ $template['certificates_count'] }} sertifikat
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($template['is_active'])
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $template['created_at'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.certificates.edit', $template['id']) }}" 
                                       class="text-green-600 hover:text-green-800 dark:text-green-400"
                                       title="Edit Template">
                                        Edit
                                    </a>
                                    <button onclick="deleteTemplate({{ $template['id'] }})" 
                                            class="text-red-600 hover:text-red-800 dark:text-red-400"
                                            title="Hapus Template">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mb-2">Belum ada template sertifikat.</p>
                                    <a href="{{ route('admin.certificates.create') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400">
                                        Tambah template pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $templates ?? null])
        </div>

        <!-- Info Box -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-blue-800 dark:text-blue-300">Cara Kerja</h4>
                    <ol class="mt-2 text-sm text-blue-700 dark:text-blue-400 list-decimal list-inside space-y-1">
                        <li>Buat template sertifikat untuk setiap program</li>
                        <li>Upload blanko (gambar kosong sertifikat) dan isi prefix nomor</li>
                        <li>Ketika user mengirim bukti program dan Anda setujui, sertifikat akan otomatis di-generate</li>
                        <li>Nama penerima diambil otomatis dari nama user yang mengirim bukti</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteTemplate(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus template ini?')) return;

            fetch(`{{ url('admin/certificates') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            });
        }
    </script>
@endsection
