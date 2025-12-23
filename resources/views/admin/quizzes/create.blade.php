@extends('admin.layouts.app')

@section('title', 'Buat Tugas/Postest')

@section('content')
    <div class="container px-6 mx-auto" x-data="quizForm()">
        <!-- Page Header -->
        <div class="my-6">
            <div class="flex items-start justify-between">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Buat Tugas/Postest</h2>
                <div class="flex flex-col items-end" style="gap: 16px;">
                    <a href="{{ route('admin.quizzes.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="button" 
                            @click="submitForm()"
                            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-orange-600 border border-transparent rounded-lg active:bg-orange-600 hover:bg-orange-700 focus:outline-none focus:shadow-outline-orange">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Tugas/Postest</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-800">
            <form id="quizForm" action="{{ route('admin.quizzes.store') }}" method="POST">
                @csrf
                <div class="px-6 py-6 space-y-6">
                    <!-- Judul Tugas/Postest -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="title">
                            Judul Tugas/Postest <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               x-model="formData.title" 
                               id="title" 
                               name="title"
                               required
                               placeholder="Masukkan judul tugas/postest"
                               class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500">
                    </div>

                    <!-- Program -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="program_id">
                            Program <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.program_id" 
                                id="program_id" 
                                name="program_id"
                                required
                                class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                            <option value="">Pilih Program</option>
                            <option value="1">Strategi Digital Marketing</option>
                            <option value="2">Workshop Branding</option>
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="description">
                            Deskripsi
                        </label>
                        <textarea x-model="formData.description" 
                                  id="description" 
                                  name="description"
                                  rows="3"
                                  placeholder="Masukkan deskripsi tugas/postest"
                                  class="block w-full px-4 py-3 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 focus:ring-opacity-40 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300 dark:placeholder-gray-500"></textarea>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Pertanyaan</h3>
                            <button type="button"
                                    @click="addQuestion()"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Pertanyaan
                            </button>
                        </div>

                        <!-- Questions List -->
                        <div class="space-y-6" x-show="questions.length > 0">
                            <template x-for="(question, index) in questions" :key="index">
                                <div class="p-4 border border-gray-200 rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                    <div class="flex items-start justify-between mb-3">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300" x-text="'Pertanyaan ' + (index + 1)"></span>
                                        <button type="button"
                                                @click="removeQuestion(index)"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Question Text -->
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Pertanyaan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               x-model="question.text"
                                               :name="'questions[' + index + '][text]'"
                                               required
                                               placeholder="Masukkan pertanyaan"
                                               class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                    </div>

                                    <!-- Question Type -->
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Tipe Pertanyaan <span class="text-red-500">*</span>
                                        </label>
                                        <select x-model="question.type"
                                                :name="'questions[' + index + '][type]'"
                                                @change="question.type === 'multiple_choice' ? (question.options = ['', '']) : (question.options = [])"
                                                required
                                                class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                            <option value="multiple_choice">Pilihan Ganda</option>
                                            <option value="essay">Essay</option>
                                            <option value="true_false">Benar/Salah</option>
                                        </select>
                                    </div>

                                    <!-- Multiple Choice Options -->
                                    <div x-show="question.type === 'multiple_choice'" class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Pilihan Jawaban <span class="text-red-500">*</span>
                                        </label>
                                        <template x-for="(option, optIndex) in question.options" :key="optIndex">
                                            <div class="flex items-center gap-2 mb-2">
                                                <input type="text" 
                                                       x-model="question.options[optIndex]"
                                                       :name="'questions[' + index + '][options][' + optIndex + ']'"
                                                       required
                                                       placeholder="Masukkan pilihan jawaban"
                                                       class="flex-1 px-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                                <button type="button"
                                                        @click="removeOption(index, optIndex)"
                                                        x-show="question.options.length > 2"
                                                        class="p-2 text-red-600 hover:text-red-800 dark:text-red-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button"
                                                @click="addOption(index)"
                                                class="mt-2 text-sm text-orange-600 hover:text-orange-800 dark:text-orange-400">
                                            + Tambah Pilihan
                                        </button>
                                    </div>

                                    <!-- Correct Answer (for multiple choice) -->
                                    <div x-show="question.type === 'multiple_choice'" class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Jawaban Benar <span class="text-red-500">*</span>
                                        </label>
                                        <select x-model="question.correct_answer"
                                                :name="'questions[' + index + '][correct_answer]'"
                                                required
                                                class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                            <option value="">Pilih Jawaban Benar</option>
                                            <template x-for="(option, optIndex) in question.options" :key="optIndex">
                                                <option :value="optIndex" x-text="'Pilihan ' + (optIndex + 1) + ': ' + option"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- True/False Options -->
                                    <div x-show="question.type === 'true_false'" class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Jawaban Benar <span class="text-red-500">*</span>
                                        </label>
                                        <select x-model="question.correct_answer"
                                                :name="'questions[' + index + '][correct_answer]'"
                                                required
                                                class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                            <option value="">Pilih Jawaban</option>
                                            <option value="true">Benar</option>
                                            <option value="false">Salah</option>
                                        </select>
                                    </div>

                                    <!-- Points -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Poin <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                               x-model="question.points"
                                               :name="'questions[' + index + '][points]'"
                                               min="1"
                                               value="1"
                                               required
                                               class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-orange-400 focus:outline-none focus:ring focus:ring-orange-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-orange-300">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Empty State -->
                        <div x-show="questions.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mb-4">Belum ada pertanyaan. Klik tombol "Tambah Pertanyaan" untuk menambahkan.</p>
                            <button type="button"
                                    @click="addQuestion()"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-lg hover:bg-orange-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Pertanyaan Pertama
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function quizForm() {
            return {
                formData: {
                    title: '',
                    program_id: '',
                    description: ''
                },
                questions: [],

                addQuestion() {
                    this.questions.push({
                        text: '',
                        type: 'multiple_choice',
                        options: ['', ''],
                        correct_answer: '',
                        points: 1
                    });
                },

                removeQuestion(index) {
                    this.questions.splice(index, 1);
                },

                addOption(questionIndex) {
                    this.questions[questionIndex].options.push('');
                },

                removeOption(questionIndex, optionIndex) {
                    this.questions[questionIndex].options.splice(optionIndex, 1);
                },

                submitForm() {
                    // Validate form
                    if (!this.formData.title || !this.formData.program_id) {
                        alert('Harap isi judul dan pilih program');
                        return;
                    }

                    if (this.questions.length === 0) {
                        alert('Harap tambahkan minimal satu pertanyaan');
                        return;
                    }

                    // Validate each question
                    for (let i = 0; i < this.questions.length; i++) {
                        const q = this.questions[i];
                        if (!q.text || !q.type) {
                            alert(`Pertanyaan ${i + 1} belum lengkap`);
                            return;
                        }

                        if (q.type === 'multiple_choice' && (!q.options || q.options.length < 2)) {
                            alert(`Pertanyaan ${i + 1}: Minimal harus ada 2 pilihan jawaban`);
                            return;
                        }

                        if ((q.type === 'multiple_choice' || q.type === 'true_false') && !q.correct_answer) {
                            alert(`Pertanyaan ${i + 1}: Harap pilih jawaban yang benar`);
                            return;
                        }
                    }

                    // Submit form
                    document.getElementById('quizForm').submit();
                }
            }
        }
    </script>
    @endpush
@endsection

