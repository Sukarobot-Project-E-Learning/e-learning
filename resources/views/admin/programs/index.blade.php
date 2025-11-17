@extends('admin.layouts.app')

@section('title', 'Program Management')

@section('content')
    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Program</h2>
                <a href="{{ route('admin.programs.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Program
                </a>
            </div>
        </div>

        <!-- Programs Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Tanggal Mulai</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($programs ?? [] as $program)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $program['title'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $program['category'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $program['start_date'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $program['type'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $program['price'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.programs.edit', $program['id']) }}" class="text-green-600 hover:text-green-800 dark:text-green-400">Edit</a>
                                    <button @click="deleteProgram({{ $program['id'] }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data program. <a href="{{ route('admin.programs.create') }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400">Tambah program pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $programs ?? null])
        </div>
    </div>

    <script>
        function deleteProgram(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus program ini?')) return;

            fetch(`{{ url('admin/programs') }}/${id}`, {
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

