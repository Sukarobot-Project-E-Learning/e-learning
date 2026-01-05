@extends('admin.layouts.app')

@section('title', 'Konfirmasi Akun Instruktur')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <!-- Page Header -->
        <!-- Page Header -->
        <div class="my-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Instruktur</h2>
            <a href="{{ route('admin.instructors.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Instruktur
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.instructors.index') }}" class="flex flex-col md:flex-row gap-6">
                <!-- Search -->
                <div class="relative w-full md:flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center ml-3">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama instruktur..." class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40">
                </div>

                <!-- Filter Status -->
                <select name="status" onchange="this.form.submit()" class="w-full md:w-auto py-2 pl-3 pr-8 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </form>
        </div>

        <!-- Pending Applications Table (Only if exists) -->
        @if(isset($pendingApplications) && count($pendingApplications) > 0)
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Pengajuan Menjadi Instruktur</h3>
            <div class="w-full overflow-hidden rounded-lg shadow-md dark:bg-gray-800 border-l-4 border-yellow-500">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Nama User</th>
                                <th class="px-4 py-3">Keahlian</th>
                                <th class="px-4 py-3">Dokumen</th>
                                <th class="px-4 py-3">Tanggal Pengajuan</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($pendingApplications as $application)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        @if($application->avatar)
                                            <img class="w-8 h-8 rounded-full mr-3 object-cover" src="{{ asset($application->avatar) }}" alt="Avatar">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gray-200 mr-3"></div>
                                        @endif
                                        <div>
                                            <p class="font-semibold">{{ $application->name }}</p>
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
                                <td class="px-4 py-3 text-xs">
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('admin.instructors.approve-application', $application->id) }}" method="POST" class="inline-block m-0" id="approveAppForm{{ $application->id }}">
                                            @csrf
                                            <button type="button" onclick="approveApplication({{ $application->id }})" class="px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.instructors.reject-application', $application->id) }}" method="POST" class="inline-block m-0" id="rejectAppForm{{ $application->id }}">
                                            @csrf
                                            <button type="button" onclick="rejectApplication({{ $application->id }})" class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Instructors Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Telepon</th>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Keahlian</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($instructors as $instructor)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $instructor['name'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $instructor['email'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $instructor['phone'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if(isset($instructor['avatar']) && $instructor['avatar'])
                                    <img src="{{ asset($instructor['avatar']) }}" 
                                         alt="Avatar" 
                                         class="w-16 h-12 rounded object-cover border border-gray-300 dark:border-gray-600 cursor-pointer"
                                         onclick="window.open('{{ asset($instructor['avatar']) }}', '_blank')">
                                @else
                                    <div class="w-16 h-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-300 dark:border-gray-500">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $instructor['expertise'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs">
                                @if($instructor['status'] == 'Aktif')
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100 rounded-full">
                                        Aktif
                                    </span>
                                @elseif($instructor['status'] == 'Tidak Aktif')
                                    <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100 rounded-full">
                                        Tidak Aktif
                                    </span>
                                @elseif($instructor['status'] == 'Pending')
                                    <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 dark:bg-yellow-700 dark:text-yellow-100 rounded-full">
                                        Pending
                                    </span>
                                @elseif($instructor['status'] == 'Approved')
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100 rounded-full">
                                        Approved
                                    </span>
                                @elseif($instructor['status'] == 'Rejected')
                                    <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100 rounded-full">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.instructors.edit', $instructor['id']) }}" class="text-green-600 hover:text-green-800 dark:text-green-400">Edit</a>
                                    @if($instructor['status'] == 'Pending')
                                        <form action="{{ route('admin.instructors.approve', $instructor['id']) }}" method="POST" class="inline-block m-0" id="approveForm{{ $instructor['id'] }}">
                                            @csrf
                                            <button type="button" onclick="approveInstructor({{ $instructor['id'] }})" class="text-green-600 hover:text-green-800 dark:text-green-400">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.instructors.reject', $instructor['id']) }}" method="POST" class="inline-block m-0" id="rejectForm{{ $instructor['id'] }}">
                                            @csrf
                                            <button type="button" onclick="rejectInstructor({{ $instructor['id'] }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Reject</button>
                                        </form>
                                    @endif
                                    <button @click="deleteInstructor({{ $instructor['id'] }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada instruktur. <a href="{{ route('admin.instructors.create') }}" class="text-orange-600 hover:text-orange-800 dark:text-orange-400">Tambah instruktur pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $instructors ?? null])
        </div>
    </div>

    <script>
        function deleteInstructor(id) {
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
                    fetch(`{{ url('admin/instructors') }}/${id}`, {
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
                                text: result.message || "Instruktur berhasil dihapus.",
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

        function approveInstructor(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda akan menyetujui akun instruktur ini!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, setujui!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveForm' + id).submit();
                }
            });
        }

        function rejectInstructor(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda akan menolak akun instruktur ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, tolak!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectForm' + id).submit();
                }
            });
        }

        function approveApplication(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda akan menyetujui pengajuan instruktur ini!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, setujui!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveAppForm' + id).submit();
                }
            });
        }

        function rejectApplication(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Anda akan menolak pengajuan instruktur ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, tolak!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectAppForm' + id).submit();
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
