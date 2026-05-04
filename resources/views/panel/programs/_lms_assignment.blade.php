<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none overflow-hidden" id="assignment-app">
    <div class="px-5 sm:px-8 py-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-1">Tugas Post-Test</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola tugas akhir untuk peserta program</p>
        </div>
        <button id="addAssignmentBtn" type="button" onclick="openAssignmentModal()" class="hidden bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors focus:ring-4 focus:ring-orange-200">
            <i class="fas fa-plus mr-2"></i> Tambah Post-Test
        </button>
    </div>

    <div class="p-5 sm:p-8 bg-gray-50 dark:bg-gray-900/50 min-h-[200px]" id="assignments-container">
        <!-- Load state -->
        <div class="text-center py-10" id="assign-loading-indicator">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-orange-600 mx-auto mb-3"></div>
            <p class="text-gray-500 text-sm">Memuat tugas...</p>
        </div>
    </div>
</div>

<!-- Modal Assignment -->
<div id="assignmentModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm overflow-y-auto py-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 mx-auto my-auto" id="assignmentModalContent">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h4 class="text-lg font-bold text-gray-800 dark:text-white" id="assignmentModalTitle">Tambah Post-Test</h4>
            <button onclick="closeAssignmentModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="assignmentForm">
            <div class="p-6 space-y-4">
                <input type="hidden" id="assignment_id" name="assignment_id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Post-Test <span class="text-red-500">*</span></label>
                    <input type="text" id="assignment_title" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors" placeholder="Tugas Akhir: Membuat Prototype">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Tugas <span class="text-red-500">*</span></label>
                    <textarea id="assignment_description" rows="5" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors" placeholder="Jelaskan secara detail apa yang harus dilakukan peserta..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ekstensi File yang Diizinkan <span class="text-red-500">*</span></label>
                        <input type="text" id="assignment_allowed_extensions" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors" placeholder="pdf, zip, docx" value="pdf, zip, rar">
                        <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma (contoh: pdf, zip, jpg)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Batas Pengumpulan (Bila Ada)</label>
                        <input type="date" id="assignment_due_date" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 transition-colors">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3">
                <button type="button" onclick="closeAssignmentModal()" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors text-sm font-medium">Batal</button>
                <button type="button" onclick="submitAssignment(event)" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors text-sm font-medium">Simpan Post-Test</button>
            </div>
        </div>
    </div>
</div>

<script>
    let assignmentsArray = {!! isset($lmsAssignmentJson) ? $lmsAssignmentJson : '[]' !!};
    const assignProgramId = {{ $programId }};
    const assignIsAdmin = {{ $isAdmin ? 'true' : 'false' }};
    const assignPrefix = assignIsAdmin ? 'admin' : 'instructor';

    function mountAssignmentModalToBody() {
        const modal = document.getElementById('assignmentModal');
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        mountAssignmentModalToBody();
        renderAssignments();
    });

    function syncAssignHiddenInput() {
        const hInput = document.getElementById('lms_assignment_json');
        if (hInput) {
            hInput.value = JSON.stringify(assignmentsArray);
        }
    }

    function generateAssignTempId() {
        return 'temp_asg_' + Math.random().toString(36).substr(2, 9);
    }

    function renderAssignments() {
        const container = document.getElementById('assignments-container');
        const addBtn = document.getElementById('addAssignmentBtn');
        
        syncAssignHiddenInput();

        if (assignmentsArray.length > 0) {
            addBtn.classList.add('hidden');
        } else {
            addBtn.classList.remove('hidden');
        }

        if (assignmentsArray.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600">
                    <div class="w-16 h-16 bg-purple-50 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clipboard-check text-2xl text-purple-500"></i>
                    </div>
                    <h4 class="text-large font-bold text-gray-800 dark:text-white mb-2">Belum ada Post-Test</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Tambahkan tugas akhir (Post-test) untuk program ini</p>
                    <button type="button" onclick="openAssignmentModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i> Buat Post-Test
                    </button>
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-4">';
        assignmentsArray.forEach((asg) => {
            const dueDate = asg.due_date ? new Date(asg.due_date).toLocaleDateString('id-ID') : 'Tidak Ada Batas Waktu';
            
            html += `
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-xl flex shrink-0 items-center justify-center text-purple-600 dark:text-purple-400">
                            <i class="fas fa-clipboard-check text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white border-b-0 m-0 pb-1">${escapeHtml(asg.title)}</h4>
                            <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center"><i class="fas fa-file-upload mr-1.5 opacity-70"></i> ${escapeHtml(asg.allowed_extensions)}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                <span class="flex items-center"><i class="far fa-calendar-alt mr-1.5 opacity-70"></i> ${dueDate}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="editAssignment('${asg.id}')" class="p-2 text-gray-500 opacity-100 hover:text-orange-500 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/30 transition-colors">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" onclick="deleteAssignment('${asg.id}')" class="p-2 text-gray-500 opacity-100 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl text-gray-700 dark:text-gray-300 text-sm whitespace-pre-wrap leading-relaxed">${escapeHtml(asg.description)}</div>
            </div>`;
        });
        html += '</div>';

        container.innerHTML = html;
    }

    function openAssignmentModal(id = null) {
        document.getElementById('assignment_id').value = id || '';
        document.getElementById('assignmentModalTitle').innerText = id ? 'Edit Post-Test' : 'Tambah Post-Test';
        
        if (!id) {
            document.getElementById('assignment_title').value = '';
            document.getElementById('assignment_description').value = '';
            document.getElementById('assignment_allowed_extensions').value = 'pdf, zip, rar, doc, docx, png, jpg';
            document.getElementById('assignment_due_date').value = '';
        }

        const modal = document.getElementById('assignmentModal');
        const content = document.getElementById('assignmentModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function editAssignment(id) {
        const asg = assignmentsArray.find(a => String(a.id) === String(id));
        if(!asg) return;
        
        document.getElementById('assignment_id').value = id;
        document.getElementById('assignment_title').value = asg.title || '';
        document.getElementById('assignment_description').value = asg.description || '';
        document.getElementById('assignment_allowed_extensions').value = asg.allowed_extensions || '';
        // format date to YYYY-MM-DD
        document.getElementById('assignment_due_date').value = asg.due_date ? asg.due_date.split(' ')[0] : '';
        
        openAssignmentModal(id);
    }

    function closeAssignmentModal() {
        const modal = document.getElementById('assignmentModal');
        const content = document.getElementById('assignmentModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function submitAssignment(e) {
        if(e) e.preventDefault();
        const id = document.getElementById('assignment_id').value;
        const title = document.getElementById('assignment_title').value.trim();
        const description = document.getElementById('assignment_description').value.trim();
        const extensions = document.getElementById('assignment_allowed_extensions').value.trim();

        if (!title || !description || !extensions) {
            alert('Judul, deskripsi, dan ekstensi file wajib diisi');
            return;
        }

        const payload = {
            id: id || generateAssignTempId(),
            title: title,
            description: description,
            allowed_extensions: extensions,
            due_date: document.getElementById('assignment_due_date').value || null
        };
        
        if (id) {
            const idx = assignmentsArray.findIndex(a => String(a.id) === String(id));
            if (idx !== -1) {
                assignmentsArray[idx] = payload;
            }
        } else {
            assignmentsArray.push(payload);
        }

        closeAssignmentModal();
        renderAssignments();
    }

    function deleteAssignment(id) {
        Swal.fire({
            title: 'Hapus Post-Test?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                assignmentsArray = assignmentsArray.filter(a => String(a.id) !== String(id));
                renderAssignments();
            }
        });
    }

    if (typeof escapeHtml !== 'function') {
        window.escapeHtml = function(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        };
    }
</script>
