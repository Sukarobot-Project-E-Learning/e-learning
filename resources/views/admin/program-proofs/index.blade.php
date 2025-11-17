@extends('admin.layouts.app')

@section('title', 'Bukti Program Management')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Bukti Program</h2>
                <div class="flex items-center space-x-4">
                    <button type="button"
                            onclick="acceptAll()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        Terima Semua
                    </button>
                    <button type="button"
                            onclick="rejectAll()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                        Tolak Semua
                    </button>
                </div>
            </div>
        </div>

        <!-- Program Proofs Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">
                                <input type="checkbox" 
                                       id="select-all"
                                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                       onchange="toggleAllCheckboxes(this)">
                            </th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Judul Program</th>
                            <th class="px-4 py-3">Jadwal</th>
                            <th class="px-4 py-3">Dokumentasi</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($proofs ?? [] as $proof)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <input type="checkbox" 
                                       name="selected_proofs[]" 
                                       value="{{ $proof['id'] }}"
                                       class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <a href="{{ route('admin.program-proofs.show', $proof['id']) }}" class="font-semibold text-purple-600 hover:text-purple-800 dark:text-purple-400">
                                            {{ $proof['name'] }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $proof['program_title'] }}</td>
                            <td class="px-4 py-3 text-sm">{{ $proof['schedule'] }}</td>
                            <td class="px-4 py-3">
                                @if(isset($proof['documentation']) && $proof['documentation'])
                                    <img src="{{ $proof['documentation'] }}" 
                                         alt="Documentation" 
                                         class="w-12 h-12 rounded object-cover border border-gray-300 dark:border-gray-600 cursor-pointer"
                                         onclick="window.open('{{ $proof['documentation'] }}', '_blank')">
                                @else
                                    <div class="w-12 h-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-300 dark:border-gray-500">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.program-proofs.show', $proof['id']) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Detail</a>
                                    <button @click="deleteProof({{ $proof['id'] }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada bukti program.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $proofs ?? null])
        </div>
    </div>

    <script>
        function toggleAllCheckboxes(checkbox) {
            const checkboxes = document.querySelectorAll('input[name="selected_proofs[]"]');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        function acceptAll() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_proofs[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Pilih minimal satu bukti program untuk diterima');
                return;
            }
            if (confirm(`Apakah Anda yakin ingin menerima ${selected.length} bukti program?`)) {
                // TODO: Implement accept all logic
                selected.forEach(id => {
                    fetch(`{{ url('admin/program-proofs') }}/${id}/accept`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                });
                setTimeout(() => window.location.reload(), 500);
            }
        }

        function rejectAll() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_proofs[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Pilih minimal satu bukti program untuk ditolak');
                return;
            }
            if (confirm(`Apakah Anda yakin ingin menolak ${selected.length} bukti program?`)) {
                // TODO: Implement reject all logic
                selected.forEach(id => {
                    fetch(`{{ url('admin/program-proofs') }}/${id}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                });
                setTimeout(() => window.location.reload(), 500);
            }
        }

        function deleteProof(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus bukti program ini?')) return;

            fetch(`{{ url('admin/program-proofs') }}/${id}`, {
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
