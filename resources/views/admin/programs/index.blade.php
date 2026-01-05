@extends('admin.layouts.app')

@section('title', 'Program Management')

@section('content')
    <div class="container px-6 mx-auto">

        <!-- Page Header -->
        <!-- Page Header -->
        <div class="my-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Program</h2>
            <a href="{{ route('admin.programs.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Program
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="mb-6">
            <form action="{{ route('admin.programs.index') }}" method="GET" class="flex flex-col md:flex-row gap-6">
                <!-- Search -->
                <div class="relative w-full md:flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center ml-3">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40"
                           placeholder="Cari program...">
                </div>

                <!-- Sort -->
                <select name="sort" onchange="this.form.submit()"
                        class="w-full md:w-auto py-2 pl-3 pr-8 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40">
                    <option value="">Urutkan</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Tanggal Mulai - Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Tanggal Mulai - Terlama</option>
                </select>
            </form>
        </div>

        <!-- Programs Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Tanggal Mulai</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Gambar</th>
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
                                    <div class="flex items-center text-sm">
                                        @if(!empty($program['image']))
                                            <img src="{{ asset($program['image']) }}" alt="{{ $program['title'] }}"
                                                class="w-16 h-12 object-cover rounded">
                                        @else
                                            <div
                                                class="w-16 h-12 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-4 text-sm">
                                        <a href="{{ route('admin.programs.edit', $program['id']) }}"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400">Edit</a>
                                        <button @click="deleteProgram({{ $program['id'] }})"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data program. <a href="{{ route('admin.programs.create') }}"
                                        class="text-orange-600 hover:text-orange-800 dark:text-orange-400">Tambah program
                                        pertama</a>
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