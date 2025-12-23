@extends('admin.layouts.app')

@section('title', 'Instructor Management')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Instructor</h2>
                <button type="button"
                        @click="$dispatch('open-modal', { type: 'create' })"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Instructor
                </button>
            </div>
        </div>

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
                                    <img src="{{ asset($user->avatar) }}" 
                                         alt="Avatar" 
                                         class="w-16 h-12 rounded object-cover border border-gray-300 dark:border-gray-600 cursor-pointer"
                                         onclick="window.open('{{ asset($user->avatar) }}', '_blank')">
                                @else
                                    <div class="w-16 h-12 rounded bg-gray-200 dark:bg-gray-600 flex items-center justify-center border border-gray-300 dark:border-gray-500">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight {{ $user->is_active ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' }} rounded-full">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <button @click="$dispatch('open-modal', { type: 'edit', id: {{ $user->id }}, name: '{{ $user->name }}', email: '{{ $user->email }}', phone: '{{ $user->phone }}' })" class="text-green-600 hover:text-green-800 dark:text-green-400">Edit</button>
                                    <button @click="deleteInstructor({{ $user->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400">Hapus</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada instructor. <button @click="$dispatch('open-modal', { type: 'create' })" class="text-orange-600 hover:text-orange-800 dark:text-orange-400">Tambah instructor pertama</button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @include('components.pagination', ['items' => $users ?? null])
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-data="{
        open: false,
        type: 'create',
        userId: null,
        formData: {
            name: '',
            email: '',
            phone: '',
            password: '',
            photo: null
        },
        init() {
            this.$watch('open', value => {
                if (!value) {
                    this.resetForm();
                }
            });
        },
        resetForm() {
            this.formData = { name: '', email: '', phone: '', password: '', photo: null };
            this.userId = null;
        }
    }"
    @open-modal.window="
        open = true;
        type = $event.detail.type;
        if (type === 'edit') {
            userId = $event.detail.id;
            formData.name = $event.detail.name;
            formData.email = $event.detail.email;
            formData.phone = $event.detail.phone || '';
            formData.password = '';
        }
    "
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-50"
    @click.self="open = false">
        <div class="relative w-full max-w-md p-6 mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-800" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" x-text="type === 'create' ? 'Tambah Instructor' : 'Edit Instructor'"></h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                        <input type="text" x-model="formData.name" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" x-model="formData.email" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telepon</label>
                        <input type="text" x-model="formData.phone"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password <span x-show="type === 'edit'" class="text-xs text-gray-500">(Kosongkan jika tidak ingin mengubah)</span></label>
                        <input type="password" x-model="formData.password" :required="type === 'create'"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto <span class="text-xs text-gray-500">(Opsional)</span></label>
                        <input type="file" @change="formData.photo = $event.target.files[0]" accept="image/jpeg,image/jpg,image/png"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Max 2MB</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" @click="open = false"
                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">
                        <span x-text="type === 'create' ? 'Tambah' : 'Update'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function submitForm() {
            const formData = new FormData();
            formData.append('name', this.formData.name);
            formData.append('email', this.formData.email);
            formData.append('phone', this.formData.phone || '');
            if (this.formData.password) {
                formData.append('password', this.formData.password);
            }
           if (this.formData.photo) {
                formData.append('photo', this.formData.photo);
            }
            
            const url = this.type === 'create' ? '{{ route('admin.instructors-list.store') }}' : `{{ url('admin/instructors-list') }}/${this.userId}`;
            const method = this.type === 'create' ? 'POST' : 'POST';
            
            if (this.type === 'edit') {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
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
                alert('Terjadi kesalahan saat menyimpan data');
            });
        }

        function deleteInstructor(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus instructor ini?')) return;

            fetch(`{{ url('admin/instructors-list') }}/${id}`, {
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
