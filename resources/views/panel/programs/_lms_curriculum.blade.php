<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden" id="lms-app">
    <div class="px-5 sm:px-8 py-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-1">Kurikulum LMS</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola bab dan materi pembelajaran program</p>
        </div>
        <button type="button" onclick="openSectionModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors focus:ring-4 focus:ring-orange-200">
            <i class="fas fa-plus mr-2"></i> Tambah Bab Baru
        </button>
    </div>

    <!-- Curriculum container -->
    <div class="p-5 sm:p-8 bg-gray-50 dark:bg-gray-900/50 min-h-[300px]" id="sections-container">
        <!-- Sections will be rendered here dynamically -->
        <div class="text-center py-10" id="loading-indicator">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-orange-600 mx-auto mb-3"></div>
            <p class="text-gray-500 text-sm">Memuat kurikulum...</p>
        </div>
    </div>
</div>

<!-- Section Modal -->
<div id="sectionModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="sectionModalContent">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h4 class="text-lg font-bold text-gray-800 dark:text-white" id="sectionModalTitle">Tambah Bab</h4>
            <button onclick="closeSectionModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="sectionForm">
            <div class="p-6">
                <input type="hidden" id="section_id" name="section_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Bab <span class="text-red-500">*</span></label>
                    <input type="text" id="section_title" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors" placeholder="Contoh: Pengenalan Robotika">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3">
                <button type="button" onclick="closeSectionModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors text-sm font-medium">Batal</button>
                <button type="button" onclick="submitSection(event)" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors text-sm font-medium">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Lesson Modal -->
<div id="lessonModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm overflow-y-auto py-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-4xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 mx-auto my-auto" id="lessonModalContent">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h4 class="text-lg font-bold text-gray-800 dark:text-white" id="lessonModalTitle">Tambah Materi</h4>
            <button onclick="closeLessonModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="lessonForm">
            <div class="p-6">
                <input type="hidden" id="lesson_id" name="lesson_id">
                <input type="hidden" id="lesson_section_id" name="section_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Materi <span class="text-red-500">*</span></label>
                        <input type="text" id="lesson_title" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors" placeholder="Judul materi">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Materi <span class="text-red-500">*</span></label>
                        <select id="lesson_type" onchange="toggleLessonType()" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors">
                            <option value="video">Video URL (YouTube)</option>
                            <option value="text">Artikel / Teks (Rich Editor)</option>
                        </select>
                    </div>
                </div>

                <div id="video_field" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL Video YouTube <span class="text-red-500">*</span></label>
                    <input type="url" id="lesson_video_url" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors" placeholder="https://youtube.com/watch?v=...">
                    <p class="text-xs text-gray-500 mt-1">Sistem akan otomatis mengatur video agar bisa diputar langsung di dalam panel siswa.</p>
                </div>

                <div id="text_field" class="hidden mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konten Teks/Artikel <span class="text-red-500">*</span></label>
                    <!-- Wrapper with specific height for CKEditor so modal doesn't jump -->
                    <div class="min-h-[400px]">
                       <textarea id="lesson_content" name="content" class="w-full hidden"></textarea>
                    </div>
                </div>

            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3 sticky bottom-0">
                <button type="button" onclick="closeLessonModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors text-sm font-medium">Batal</button>
                <button type="button" onclick="submitLesson(event)" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors text-sm font-medium">Simpan Materi</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>

<script>
    let lmsData = {!! isset($lmsCurriculumJson) ? $lmsCurriculumJson : '[]' !!};
    const programId = {{ $programId }};
    const isAdmin = {{ $isAdmin ? 'true' : 'false' }};
    const prefix = isAdmin ? 'admin' : 'instructor';
    let ckEditorInstance = null;

    function mountModalToBody(modalId) {
        const modal = document.getElementById(modalId);
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        mountModalToBody('sectionModal');
        mountModalToBody('lessonModal');

        ClassicEditor.create(document.querySelector('#lesson_content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', '|', 'undo', 'redo'],
        })
        .then(editor => {
            ckEditorInstance = editor;
            editor.ui.view.editable.element.style.minHeight = '300px';
        })
        .catch(err => console.error(err));

        renderCurriculum();
    });

    function syncHiddenInput() {
        const hInput = document.getElementById('lms_curriculum_json');
        if (hInput) {
            hInput.value = JSON.stringify(lmsData);
        }
    }

    function generateTempId() {
        return 'temp_' + Math.random().toString(36).substr(2, 9);
    }

    function renderCurriculum() {
        const container = document.getElementById('sections-container');
        syncHiddenInput();
        
        if (lmsData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600">
                    <div class="w-16 h-16 bg-orange-50 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book-open text-2xl text-orange-500"></i>
                    </div>
                    <h4 class="text-large font-bold text-gray-800 dark:text-white mb-2">Belum ada kurikulum</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai buat bab pertama untuk program ini</p>
                    <button type="button" onclick="openSectionModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i> Tambah Bab Baru
                    </button>
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-6">';
        lmsData.forEach((section, sIndex) => {
            html += `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 flex justify-between items-center group">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400 flex items-center justify-center text-sm">${sIndex+1}</span>
                        ${escapeHtml(section.title)}
                    </h4>
                    <div class="flex items-center gap-2 opacity-100 transition-opacity whitespace-nowrap">
                        <button type="button" onclick="openLessonModal('${section.id}')" class="p-1.5 text-sm bg-orange-50 text-orange-600 hover:bg-orange-100 rounded-lg dark:bg-orange-900/40 dark:text-orange-400 dark:hover:bg-orange-900/60" title="Tambah Materi">
                            <i class="fas fa-plus mr-1"></i> Materi
                        </button>
                        <button type="button" onclick="openSectionModal('${section.id}', '${escapeHtml(section.title)}')" class="p-2 text-gray-500 hover:text-orange-500 hover:bg-orange-50 dark:hover:bg-gray-700 rounded-lg" title="Edit Bab">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button type="button" onclick="deleteSection('${section.id}')" class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-gray-700 rounded-lg" title="Hapus Bab">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 bg-white dark:bg-gray-800">
            `;

            if (!section.lessons || section.lessons.length === 0) {
                html += `<div class="text-center py-6 text-sm text-gray-500 dark:text-gray-400 border border-dashed border-gray-200 dark:border-gray-700 rounded-lg">Belum ada materi di bab ini</div>`;
            } else {
                html += `<div class="space-y-3">`;
                section.lessons.forEach((lesson, lIndex) => {
                    const icon = lesson.type === 'video' ? 'fa-play-circle text-red-500' : 'fa-file-alt text-orange-500';
                    const iconBg = lesson.type === 'video' ? 'bg-red-50 dark:bg-red-900/20' : 'bg-orange-50 dark:bg-orange-900/20';
                    const label = lesson.type === 'video' ? 'Video' : 'Artikel';
                    
                    html += `
                        <div class="flex items-center justify-between p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/80 transition-colors group">
                            <div class="flex items-center gap-4 overflow-hidden">
                                <div class="w-10 h-10 rounded-lg ${iconBg} flex items-center justify-center shrink-0">
                                    <i class="fas ${icon} text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h5 class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">${escapeHtml(lesson.title)}</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">${label}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-100 transition-opacity">
                                <button type="button" onclick="editLesson('${section.id}', '${lesson.id}')" class="p-2 text-gray-400 hover:text-orange-500 rounded-lg">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" onclick="deleteLesson('${section.id}', '${lesson.id}')" class="p-2 text-gray-400 hover:text-red-500 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                html += `</div>`;
            }

            html += `
                </div>
            </div>`;
        });
        html += '</div>';

        container.innerHTML = html;
    }

    function openSectionModal(id = null, title = '') {
        document.getElementById('section_id').value = id || '';
        document.getElementById('section_title').value = title;
        document.getElementById('sectionModalTitle').innerText = id ? 'Edit Bab' : 'Tambah Bab';
        
        const modal = document.getElementById('sectionModal');
        const content = document.getElementById('sectionModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeSectionModal() {
        const modal = document.getElementById('sectionModal');
        const content = document.getElementById('sectionModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function submitSection(e) {
        if(e) e.preventDefault();
        const id = document.getElementById('section_id').value;
        const title = document.getElementById('section_title').value.trim();
        
        if (!title) {
            alert('Judul bab wajib diisi');
            return;
        }
        
        if (id) {
            // Edit
            const sec = lmsData.find(s => String(s.id) === String(id));
            if (sec) sec.title = title;
        } else {
            // Add
            lmsData.push({
                id: generateTempId(),
                title: title,
                lessons: []
            });
        }

        closeSectionModal();
        renderCurriculum();
    }

    function deleteSection(id) {
        Swal.fire({
            title: 'Hapus Bab?',
            text: "Semua materi di dalam bab akan ikut terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                lmsData = lmsData.filter(s => String(s.id) !== String(id));
                renderCurriculum();
            }
        });
    }

    function toggleLessonType() {
        const type = document.getElementById('lesson_type').value;
        if(type === 'video') {
            document.getElementById('video_field').classList.remove('hidden');
            document.getElementById('text_field').classList.add('hidden');
        } else {
            document.getElementById('video_field').classList.add('hidden');
            document.getElementById('text_field').classList.remove('hidden');
        }
    }

    function openLessonModal(sectionId) {
        document.getElementById('lesson_section_id').value = sectionId;
        document.getElementById('lesson_id').value = '';
        document.getElementById('lesson_title').value = '';
        document.getElementById('lesson_type').value = 'video';
        document.getElementById('lesson_video_url').value = '';
        if(ckEditorInstance) ckEditorInstance.setData('');
        
        toggleLessonType();
        document.getElementById('lessonModalTitle').innerText = 'Tambah Materi';
        
        const modal = document.getElementById('lessonModal');
        const content = document.getElementById('lessonModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function editLesson(sectionId, lessonId) {
        const section = lmsData.find(s => String(s.id) === String(sectionId));
        const lesson = section.lessons.find(l => String(l.id) === String(lessonId));
        
        document.getElementById('lesson_section_id').value = sectionId;
        document.getElementById('lesson_id').value = lesson.id;
        document.getElementById('lesson_title').value = lesson.title || '';
        document.getElementById('lesson_type').value = lesson.type || 'video';
        document.getElementById('lesson_video_url').value = lesson.video_url || '';
        if(ckEditorInstance) ckEditorInstance.setData(lesson.content || '');
        
        toggleLessonType();
        document.getElementById('lessonModalTitle').innerText = 'Edit Materi';
        
        const modal = document.getElementById('lessonModal');
        const content = document.getElementById('lessonModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeLessonModal() {
        const modal = document.getElementById('lessonModal');
        const content = document.getElementById('lessonModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function submitLesson(e) {
        if(e) e.preventDefault();
        const id = document.getElementById('lesson_id').value;
        const sectionId = document.getElementById('lesson_section_id').value;
        const title = document.getElementById('lesson_title').value.trim();
        const type = document.getElementById('lesson_type').value;
        const videoUrl = document.getElementById('lesson_video_url').value.trim();
        const content = ckEditorInstance ? ckEditorInstance.getData() : '';
        
        if (!title) {
            alert('Judul materi wajib diisi');
            return;
        }
        
        if (type === 'video' && !videoUrl) {
            alert('URL Video wajib diisi');
            return;
        }
        
        const payload = {
            id: id || generateTempId(),
            title: title,
            type: type,
            video_url: videoUrl,
            content: content
        };

        const sec = lmsData.find(s => String(s.id) === String(sectionId));
        if (sec) {
            if (!sec.lessons) sec.lessons = [];
            
            if (id) {
                const idx = sec.lessons.findIndex(l => String(l.id) === String(id));
                if(idx !== -1) sec.lessons[idx] = payload;
            } else {
                sec.lessons.push(payload);
            }
        }

        closeLessonModal();
        renderCurriculum();
    }

    function deleteLesson(sectionId, lessonId) {
        Swal.fire({
            title: 'Hapus Materi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const sec = lmsData.find(s => String(s.id) === String(sectionId));
                if (sec && sec.lessons) {
                     sec.lessons = sec.lessons.filter(l => String(l.id) !== String(lessonId));
                }
                renderCurriculum();
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
</script>
