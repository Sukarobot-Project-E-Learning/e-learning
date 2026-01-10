@extends('admin.layouts.app')

@section('title', 'Admin Management')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <!-- Page Header -->
        <!-- Page Header -->
        <div class="my-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Admin</h2>
            <a href="{{ route('admin.admins.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Admin
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.admins.index') }}" class="flex flex-col md:flex-row gap-6">
                <!-- Search -->
                <div class="relative w-full md:flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center ml-3">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama admin..." class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40">
                </div>

                <!-- Filter Status -->
                <select name="status" onchange="this.form.submit()" class="w-full md:w-auto py-2 pl-3 pr-8 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </form>
        </div>

                <!-- Admins Table Container -->
                <div id="admins-table" class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
                    @include('admin.admins.partials.table', ['users' => $users])
                </div>
            </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.querySelector('input[name="search"]');
                const statusSelect = document.querySelector('select[name="status"]');
                const tableContainer = document.getElementById('admins-table');
    
                let timeoutId;
    
                function fetchAdmins() {
                    const search = searchInput.value;
                    const status = statusSelect.value;
                    
                    // Show loading state (optional)
                    tableContainer.style.opacity = '0.5';
    
                    fetch(`{{ route('admin.admins.index') }}?search=${search}&status=${status}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        tableContainer.style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableContainer.style.opacity = '1';
                    });
                }
    
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(fetchAdmins, 300); // Debounce 300ms
                });
    
                statusSelect.addEventListener('change', function() {
                    fetchAdmins();
                });
            });
        </script>

    <script>
        function deleteAdmin(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('admin/admins') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            Swal.fire({
                                title: "Dihapus!",
                                text: result.message || "Admin berhasil dihapus.",
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: result.message || "Terjadi kesalahan",
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Error!",
                            text: "Terjadi kesalahan saat menghapus data",
                            icon: "error"
                        });
                    });
                }
            });
        }

        // Handle success/error messages from session
        @if(session('success'))
            Swal.fire({
                title: "{{ session('success') }}",
                icon: "success",
                draggable: true
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
