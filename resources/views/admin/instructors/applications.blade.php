@extends('admin.layouts.app')

@section('title', 'Pengajuan Menjadi Instruktur')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Pengajuan Menjadi Instruktur</h2>
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

        <!-- Applications Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">
                                <input type="checkbox" 
                                       id="select-all"
                                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                       onchange="toggleAllCheckboxes(this)">
                            </th>
                            <th class="px-4 py-3">Nama User</th>
                            <th class="px-4 py-3">Keahlian</th>
                            <th class="px-4 py-3">Dokumen</th>
                            <th class="px-4 py-3">Tanggal Pengajuan</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($applications as $application)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <input type="checkbox" 
                                       name="selected_applications[]" 
                                       value="{{ $application->id }}"
                                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    @if($application->avatar)
                                        <img class="w-8 h-8 rounded-full mr-3 object-cover" src="{{ asset($application->avatar) }}" alt="Avatar">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-200 mr-3"></div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.instructor-applications.show', $application->id) }}" class="font-semibold text-orange-600 hover:text-orange-800 dark:text-orange-400">
                                            {{ $application->name }}
                                        </a>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $application->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="truncate w-48" title="{{ $application->skills }}">
                                    {{ Str::limit($application->skills, 50) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex space-x-2">
                                    @if($application->cv_path)
                                        <a href="{{ asset($application->cv_path) }}" target="_blank" class="px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded hover:bg-blue-600">CV</a>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded">No CV</span>
                                    @endif

                                    @if(!empty($application->ktp_path))
                                        <a href="{{ asset($application->ktp_path) }}" target="_blank" class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded hover:bg-green-600">KTP</a>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded">No KTP</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ date('d M Y H:i', strtotime($application->created_at)) }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.instructor-applications.show', $application->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Detail</a>
                                    <button onclick="deleteApplication({{ $application->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada pengajuan instruktur.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $applications])
        </div>
    </div>

    <script>
        function toggleAllCheckboxes(checkbox) {
            const checkboxes = document.querySelectorAll('input[name="selected_applications[]"]');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        function acceptAll() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_applications[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                Swal.fire('Peringatan', 'Pilih minimal satu pengajuan untuk disetujui', 'warning');
                return;
            }
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menyetujui ${selected.length} pengajuan instruktur`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui semua!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let completed = 0;
                    selected.forEach(id => {
                        fetch(`{{ url('admin/instructor-applications') }}/${id}/approve`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).finally(() => {
                            completed++;
                            if (completed === selected.length) {
                                Swal.fire('Berhasil!', 'Semua pengajuan yang dipilih telah disetujui.', 'success')
                                    .then(() => window.location.reload());
                            }
                        });
                    });
                }
            });
        }

        function rejectAll() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_applications[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                Swal.fire('Peringatan', 'Pilih minimal satu pengajuan untuk ditolak', 'warning');
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menolak ${selected.length} pengajuan instruktur`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, tolak semua!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let completed = 0;
                    selected.forEach(id => {
                        fetch(`{{ url('admin/instructor-applications') }}/${id}/reject`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).finally(() => {
                            completed++;
                            if (completed === selected.length) {
                                Swal.fire('Berhasil!', 'Semua pengajuan yang dipilih telah ditolak.', 'success')
                                    .then(() => window.location.reload());
                            }
                        });
                    });
                }
            });
        }

        function deleteApplication(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('admin/instructor-applications') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            Swal.fire('Terhapus!', result.message, 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Gagal!', result.message || 'Terjadi kesalahan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data', 'error');
                    });
                }
            });
        }

        // Handle success/error messages from session
        @if(session('success'))
            Swal.fire({
                title: "{{ session('success') }}",
                icon: "success",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error"
            });
        @endif
    </script>
@endsection
