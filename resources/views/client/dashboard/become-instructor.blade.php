@extends('client.layouts.dashboard')

@section('dashboard-content')
    <h2 class="text-2xl font-bold text-blue-700 mb-6">Menjadi Instruktur</h2>

    @if(isset($existingApplication) && $existingApplication->status == 'rejected')
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm leading-5 font-medium text-red-800">
                        Pengajuan Sebelumnya Ditolak
                    </h3>
                    <div class="mt-2 text-sm leading-5 text-red-700">
                        <p>Alasan: {{ $existingApplication->admin_notes }}</p>
                        <p class="mt-1">Silakan perbaiki data Anda dan ajukan kembali.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ route('client.dashboard.become-instructor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Skills -->
        <div>
            <label class="block text-gray-900 font-medium mb-2" for="skills">
                Keahlian (Skills)
            </label>
            <input type="text" name="skills" id="skills"
                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-gray-700 placeholder-gray-400"
                placeholder="Contoh: Web Development, Data Science, Digital Marketing..."
                value="{{ old('skills') }}">
            @error('skills')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bio -->
        <div>
            <label class="block text-gray-900 font-medium mb-2" for="bio">
                Biografi Singkat
            </label>
            <textarea name="bio" id="bio" rows="4"
                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-gray-700 placeholder-gray-400"
                placeholder="Ceritakan pengalaman dan latar belakang Anda sebagai pengajar atau profesional...">{{ old('bio') }}</textarea>
            @error('bio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- CV Upload -->
            <div>
                <label class="block text-gray-900 font-medium mb-2">
                    Upload CV
                </label>
                <div class="relative group">
                    <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="updateFileName(this, 'cv-label')">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors group-hover:border-blue-400 bg-white">
                        <div class="mb-3">
                            <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p id="cv-label" class="text-sm text-gray-500 font-medium">PDF atau DOC</p>
                        <p class="text-xs text-gray-400 mt-1">Maks. 2MB</p>
                    </div>
                </div>
                @error('cv')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- KTP Upload -->
            <div>
                <label class="block text-gray-900 font-medium mb-2">
                    Foto KTP
                </label>
                <div class="relative group">
                    <input type="file" name="ktp" id="ktp" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="updateFileName(this, 'ktp-label')">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors group-hover:border-blue-400 bg-white">
                        <div class="mb-3">
                            <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <p id="ktp-label" class="text-sm text-gray-500 font-medium">JPG atau PNG</p>
                        <p class="text-xs text-gray-400 mt-1">Maks. 2MB</p>
                    </div>
                </div>
                @error('ktp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NPWP Upload -->
            <div>
                <label class="block text-gray-900 font-medium mb-2">
                    Foto NPWP (Opsional)
                </label>
                <div class="relative group">
                    <input type="file" name="npwp" id="npwp" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="updateFileName(this, 'npwp-label')">
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors group-hover:border-blue-400 bg-white">
                        <div class="mb-3">
                            <svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <p id="npwp-label" class="text-sm text-gray-500 font-medium">JPG atau PNG</p>
                        <p class="text-xs text-gray-400 mt-1">Maks. 2MB</p>
                    </div>
                </div>
                @error('npwp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 flex items-center justify-center gap-2 mt-8">
            <span>Kirim Pengajuan</span>
        </button>
    </form>

    <script>
        function updateFileName(input, labelId) {
            const label = document.getElementById(labelId);
            if (input.files && input.files.length > 0) {
                label.textContent = input.files[0].name;
                label.classList.add('text-gray-900');
                label.classList.remove('text-gray-500');
            } else {
                if (labelId === 'cv-label') label.textContent = 'PDF atau DOC';
                if (labelId === 'ktp-label') label.textContent = 'JPG atau PNG';
                if (labelId === 'npwp-label') label.textContent = 'JPG atau PNG';
                label.classList.add('text-gray-500');
                label.classList.remove('text-gray-900');
            }
        }
    </script>
@endsection
