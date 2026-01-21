<div class="w-full overflow-x-auto">
    <table class="w-full whitespace-nowrap">
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
                        @php
                            $avatarUrl = str_starts_with($instructor['avatar'], 'images/') 
                                ? asset($instructor['avatar']) 
                                : asset('storage/' . $instructor['avatar']);
                        @endphp
                        <img src="{{ $avatarUrl }}" 
                             alt="Avatar" 
                             class="w-12 h-12 rounded-full object-cover border border-gray-300 dark:border-gray-600 cursor-pointer"
                             onclick="window.open('{{ $avatarUrl }}', '_blank')">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-300 dark:border-gray-500">
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
                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100 rounded-full whitespace-nowrap">
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
                        <a href="{{ route('admin.instructors.show', $instructor['id']) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400" title="Detail">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.instructors.edit', $instructor['id']) }}" class="text-green-600 hover:text-green-800 dark:text-green-400" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
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
                        <button @click="deleteInstructor({{ $instructor['id'] }})" class="text-red-600 hover:text-red-800 dark:text-red-400" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    Belum ada instruktur. <a href="{{ route('admin.instructors.create') }}" class="text-orange-600 hover:text-orange-800 dark:text-orange-400">Tambah instruktur pertama</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('components.pagination', ['items' => $instructors ?? null])
