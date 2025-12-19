@extends('admin.layouts.app')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Buat Template Sertifikat
    </h2>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('admin.certificates.templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Program</span>
                <select name="program_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value="">Pilih Program</option>
                    @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->program }}</option>
                    @endforeach
                </select>
            </label>

            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Upload Blanko (Gambar Kosong)</span>
                <input type="file" name="background_image" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
            </label>



            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Deskripsi Program (Teks Statis)</span>
                <textarea name="description" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Contoh: Pelatihan Junior Web Developer diselenggarakan oleh..."></textarea>
            </label>

            <h4 class="mb-4 mt-6 text-lg font-semibold text-gray-600 dark:text-gray-300">
                Konfigurasi Posisi & Font
            </h4>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nama Peserta -->
                <div class="p-4 border rounded-lg dark:border-gray-700">
                    <h5 class="font-bold mb-2 dark:text-gray-300">Nama Peserta</h5>
                    <p class="text-xs text-gray-500 mb-2">Rekomendasi: Lobster 1.4 Regular (Size 38)</p>
                    
                    <label class="block text-sm mb-2">
                        <span class="text-gray-700 dark:text-gray-400">Font</span>
                        <select name="name_font" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select">
                            @foreach($fonts as $font)
                            <option value="{{ $font }}" {{ str_contains(strtolower($font), 'lobster') ? 'selected' : '' }}>{{ $font }}</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex space-x-4">
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">X</span>
                            <input type="number" name="name_x" value="1000" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">Y</span>
                            <input type="number" name="name_y" value="700" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                    </div>
                </div>

                <!-- Nomor Sertifikat -->
                <div class="p-4 border rounded-lg dark:border-gray-700">
                    <h5 class="font-bold mb-2 dark:text-gray-300">Nomor Sertifikat</h5>
                    <p class="text-xs text-gray-500 mb-2">Rekomendasi: Lato Regular (Size 12)</p>

                    <label class="block text-sm mb-2">
                        <span class="text-gray-700 dark:text-gray-400">Font</span>
                        <select name="number_font" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select">
                            @foreach($fonts as $font)
                            <option value="{{ $font }}" {{ str_contains(strtolower($font), 'lato') ? 'selected' : '' }}>{{ $font }}</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex space-x-4">
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">X</span>
                            <input type="number" name="number_x" value="1000" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">Y</span>
                            <input type="number" name="number_y" value="500" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="p-4 border rounded-lg dark:border-gray-700">
                    <h5 class="font-bold mb-2 dark:text-gray-300">Deskripsi Program</h5>
                    <p class="text-xs text-gray-500 mb-2">Rekomendasi: Lato Regular (Size 16)</p>

                    <label class="block text-sm mb-2">
                        <span class="text-gray-700 dark:text-gray-400">Font</span>
                        <select name="desc_font" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select">
                            @foreach($fonts as $font)
                            <option value="{{ $font }}" {{ str_contains(strtolower($font), 'lato') ? 'selected' : '' }}>{{ $font }}</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex space-x-4">
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">X</span>
                            <input type="number" name="desc_x" value="1000" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">Y</span>
                            <input type="number" name="desc_y" value="900" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="p-4 border rounded-lg dark:border-gray-700">
                    <h5 class="font-bold mb-2 dark:text-gray-300">Tanggal</h5>
                    <p class="text-xs text-gray-500 mb-2">Mengikuti font Deskripsi</p>
                    <div class="flex space-x-4">
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">X</span>
                            <input type="number" name="date_x" value="1000" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                        <label class="block text-sm w-1/2">
                            <span class="text-gray-700 dark:text-gray-400">Y</span>
                            <input type="number" name="date_y" value="1100" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input">
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="button" id="btn-preview" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-teal-500 border border-transparent rounded-lg active:bg-teal-600 hover:bg-teal-600 focus:outline-none focus:shadow-outline-teal">
                    Preview Template
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Simpan Template
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-preview').addEventListener('click', async function() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        
        // Show loading
        Swal.fire({
            title: 'Generating Preview...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch("{{ route('admin.certificates.templates.preview') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    title: 'Preview Sertifikat',
                    imageUrl: data.image,
                    imageAlt: 'Certificate Preview',
                    width: 800,
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', data.error || 'Gagal membuat preview', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }
    });
</script>
@endsection
