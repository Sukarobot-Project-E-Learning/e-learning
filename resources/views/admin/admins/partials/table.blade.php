<table class="w-full whitespace-nowrap">
    <thead>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Telepon</th>
            <th class="px-4 py-3">Foto</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Action</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        @forelse($users as $user)
        <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">
                <div class="flex items-center text-sm">
                    <div>
                        <p class="font-semibold">{{ $user->name }}</p>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3 text-sm">{{ $user->email ?? '-' }}</td>
            <td class="px-4 py-3 text-sm">{{ $user->phone ?? '-' }}</td>
            <td class="px-4 py-3">
                @if($user->avatar)
                    @php
                        $avatarUrl = str_starts_with($user->avatar, 'images/') 
                            ? asset($user->avatar) 
                            : asset('storage/' . $user->avatar);
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
            <td class="px-4 py-3 text-xs">
                <span class="px-2 py-1 font-semibold leading-tight {{ $user->is_active ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' }} rounded-full whitespace-nowrap">
                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center space-x-4 text-sm">
                    <a href="{{ route('admin.admins.edit', $user->id) }}" class="text-green-600 hover:text-green-800 dark:text-green-400">Edit</a>
                    <button @click="deleteAdmin({{ $user->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                Belum ada admin. <a href="{{ route('admin.admins.create') }}" class="text-orange-600 hover:text-orange-800 dark:text-orange-400">Tambah admin pertama</a>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="px-4 py-3 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        {{ $users->links() }}
    @endif
</div>
