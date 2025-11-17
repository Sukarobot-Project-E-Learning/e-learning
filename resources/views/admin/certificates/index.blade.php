@extends('admin.layouts.app')

@section('title', 'Sertifikat Management')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Sertifikat</h2>
                <a href="{{ route('admin.certificates.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Sertifikat
                </a>
            </div>
        </div>

        <!-- Certificates Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Nomor Kredensial</th>
                            <th class="px-4 py-3">Blanko</th>
                            <th class="px-4 py-3">Tanggal Diterbitkan</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($certificates as $certificate)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $certificate['name'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $certificate['title'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $certificate['credential_number'] }}</td>
                            <td class="px-4 py-3">
                                @if(isset($certificate['blanko']) && $certificate['blanko'])
                                    <img src="{{ $certificate['blanko'] }}" 
                                         alt="Sertifikat" 
                                         class="w-16 h-12 rounded object-cover border border-gray-300 dark:border-gray-600 cursor-pointer"
                                         onclick="window.open('{{ $certificate['blanko'] }}', '_blank')">
                                @else
                                    <div class="w-16 h-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-300 dark:border-gray-500">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $certificate['issued_at'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.certificates.edit', $certificate['id']) }}" class="text-green-600 hover:text-green-800 dark:text-green-400">Edit</a>
                                    <button @click="deleteCertificate({{ $certificate['id'] }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada sertifikat. <a href="{{ route('admin.certificates.create') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400">Tambah sertifikat pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $certificates ?? null])
        </div>
    </div>

    <script>
        function deleteCertificate(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus sertifikat ini?')) return;

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
