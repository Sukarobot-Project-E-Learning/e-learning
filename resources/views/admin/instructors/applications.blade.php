@extends('admin.layouts.app')

@section('title', 'Pengajuan Menjadi Instruktur')

@push('styles')
<style>
    .sort-icon { transition: transform 0.2s ease; }
    .sort-icon.asc { transform: rotate(180deg); }
    .search-highlight { background-color: rgb(254 215 170); padding: 0 2px; border-radius: 2px; }
</style>
@endpush

@section('content')
<div class="container px-6 mx-auto" x-data="applicationTable()">
    <div class="my-6 flex items-center justify-between flex-wrap gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Pengajuan Menjadi Instruktur</h2>
        <div class="flex items-center gap-3">
            <button type="button" @click="acceptAllSelected()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Terima (<span x-text="selectedIds.length"></span>)
            </button>
            <button type="button" @click="rejectAllSelected()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Tolak (<span x-text="selectedIds.length"></span>)
            </button>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-orange-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                <input type="text" x-model="search" @input.debounce.300ms="filterTable()" placeholder="Cari nama, email, keahlian..." class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                <span x-show="search" @click="search=''; filterTable()" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"><svg class="w-4 h-4 text-gray-400 hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></span>
            </div>
            <select x-model.number="perPage" @change="filterTable()" class="px-4 py-2.5 text-sm border-2 border-orange-200 rounded-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-1">
                <span>Menampilkan</span>
                <span class="font-semibold text-orange-600" x-text="showingFrom"></span>-<span class="font-semibold text-orange-600" x-text="showingTo"></span>
                <span>dari</span><span class="font-semibold text-orange-600" x-text="total"></span><span>pengajuan</span>
            </div>
            <div x-show="search" class="text-orange-600 text-xs sm:text-sm">Hasil pencarian untuk "<span x-text="search" class="font-semibold"></span>"</div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="block lg:hidden space-y-3 mb-6">
        <template x-for="(app, index) in applications" :key="app.id">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-start gap-3">
                    <input type="checkbox" :value="app.id" x-model="selectedIds" class="mt-1 w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <template x-if="app.avatar">
                        <img :src="app.avatar.startsWith('http') ? app.avatar : '/' + app.avatar" class="w-12 h-12 rounded-lg object-cover border-2 border-orange-200">
                    </template>
                    <template x-if="!app.avatar">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        </div>
                    </template>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 dark:text-white truncate" x-html="highlightText(app.name)"></h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate" x-html="highlightText(app.email || '-')"></p>
                        <p class="text-xs text-gray-400 mt-1" x-text="formatDate(app.created_at)"></p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-300 truncate" x-html="highlightText(app.skills || '-')"></p>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex gap-2">
                            <template x-if="app.cv_path"><a :href="'/' + app.cv_path" target="_blank" class="px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded hover:bg-blue-600">CV</a></template>
                            <template x-if="app.ktp_path"><a :href="'/' + app.ktp_path" target="_blank" class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded hover:bg-green-600">KTP</a></template>
                        </div>
                        <div class="flex gap-2">
                            <a :href="'/admin/instructor-applications/' + app.id" class="px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg">Detail</a>
                            <button @click="deleteApplication(app.id)" class="px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 rounded-lg">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template x-if="applications.length === 0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <p class="text-gray-500 dark:text-gray-400">Belum ada pengajuan instruktur.</p>
            </div>
        </template>
    </div>

    <!-- Desktop Table -->
    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-orange-100 dark:border-gray-700 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" @change="toggleAll($event.target.checked)" :checked="allSelected" class="w-4 h-4 text-orange-600 bg-white border-gray-300 rounded focus:ring-orange-500">
                        </th>
                        <template x-for="col in columns" :key="col.key">
                            <th @click="col.sortable && sortBy(col.key)" :class="{'cursor-pointer hover:bg-orange-600': col.sortable}" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span x-text="col.label"></span>
                                    <template x-if="col.sortable">
                                        <svg :class="{'sort-icon': true, 'asc': sortKey === col.key && sortDir === 'asc', 'opacity-30': sortKey !== col.key}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </template>
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100 dark:divide-gray-700">
                    <template x-for="(app, index) in applications" :key="app.id">
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="app.id" x-model="selectedIds" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <template x-if="app.avatar"><img :src="app.avatar.startsWith('http') ? app.avatar : '/' + app.avatar" class="w-8 h-8 rounded-full mr-3 object-cover border-2 border-orange-200"></template>
                                    <template x-if="!app.avatar"><div class="w-8 h-8 rounded-full bg-gray-200 mr-3"></div></template>
                                    <div>
                                        <a :href="'/admin/instructor-applications/' + app.id" class="font-semibold text-orange-600 hover:text-orange-800 dark:text-orange-400" x-html="highlightText(app.name)"></a>
                                        <p class="text-xs text-gray-600 dark:text-gray-400" x-html="highlightText(app.email || '-')"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <div class="truncate max-w-xs" :title="app.skills" x-html="highlightText(app.skills ? (app.skills.length > 50 ? app.skills.substring(0, 50) + '...' : app.skills) : '-')"></div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex space-x-2">
                                    <template x-if="app.cv_path"><a :href="'/' + app.cv_path" target="_blank" class="px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded hover:bg-blue-600">CV</a></template>
                                    <template x-if="!app.cv_path"><span class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded">No CV</span></template>
                                    <template x-if="app.ktp_path"><a :href="'/' + app.ktp_path" target="_blank" class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded hover:bg-green-600">KTP</a></template>
                                    <template x-if="!app.ktp_path"><span class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-200 rounded">No KTP</span></template>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300" x-text="formatDate(app.created_at)"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a :href="'/admin/instructor-applications/' + app.id" class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                    <button @click="deleteApplication(app.id)" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="applications.length === 0">
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500"><p class="text-lg font-medium">Belum ada pengajuan instruktur.</p></td></tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Desktop Pagination -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-orange-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <button @click="prevPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Sebelumnya</button>
                <div class="flex items-center gap-1">
                    <template x-for="page in visiblePages" :key="page">
                        <button @click="page !== '...' && goToPage(page)" :class="page === currentPage ? 'bg-orange-500 text-white' : page === '...' ? 'cursor-default' : 'hover:bg-orange-100 text-gray-700'" class="w-10 h-10 text-sm font-medium rounded-lg transition-colors" x-text="page"></button>
                    </template>
                </div>
                <button @click="nextPage()" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-4 py-2 text-sm font-medium text-orange-600 rounded-lg transition-colors flex items-center gap-2">Selanjutnya<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
            </div>
        </div>
    </div>

    <!-- Mobile Pagination -->
    <div class="block lg:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <button @click="prevPage()" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-3 py-2 text-sm font-medium text-orange-600 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
            <div class="flex items-center gap-1">
                <template x-for="page in visiblePages" :key="page">
                    <button @click="page !== '...' && goToPage(page)" :class="page === currentPage ? 'bg-orange-500 text-white' : page === '...' ? 'cursor-default' : 'hover:bg-orange-100 text-gray-700'" class="w-10 h-10 text-sm font-medium rounded-lg transition-colors" x-text="page"></button>
                </template>
            </div>
            <button @click="nextPage()" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'" class="px-3 py-2 text-sm font-medium text-orange-600 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
        </div>
    </div>
</div>

<script>
function applicationTable() {
    return {
        applications: @json($applications->items()),
        selectedIds: [],
        search: '{{ request("search") }}',
        perPage: {{ $applications->perPage() }},
        currentPage: {{ $applications->currentPage() }},
        sortKey: '{{ request("sort", "created_at") }}',
        sortDir: '{{ request("dir", "desc") }}',
        total: {{ $applications->total() }},
        lastPage: {{ $applications->lastPage() }},
        loading: false,
        columns: [
            { key: 'name', label: 'Nama User', sortable: true },
            { key: 'skills', label: 'Keahlian', sortable: false },
            { key: 'documents', label: 'Dokumen', sortable: false },
            { key: 'created_at', label: 'Tanggal Pengajuan', sortable: true },
            { key: 'actions', label: 'Action', sortable: false }
        ],
        get totalPages() { return this.lastPage || 1; },
        get showingFrom() { return this.total ? ((this.currentPage - 1) * this.perPage) + 1 : 0; },
        get showingTo() { return Math.min(this.currentPage * this.perPage, this.total); },
        get allSelected() { return this.applications.length > 0 && this.selectedIds.length === this.applications.length; },
        get visiblePages() {
            const pages = []; const total = this.totalPages; const current = this.currentPage;
            if (total <= 7) { for (let i = 1; i <= total; i++) pages.push(i); }
            else { pages.push(1); if (current > 3) pages.push('...'); for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i); if (current < total - 2) pages.push('...'); pages.push(total); }
            return pages;
        },
        toggleAll(checked) {
            this.selectedIds = checked ? this.applications.map(a => a.id) : [];
        },
        filterTable() { this.currentPage = 1; this.fetchData(); },
        sortBy(key) { this.sortDir = this.sortKey === key && this.sortDir === 'asc' ? 'desc' : 'asc'; this.sortKey = key; this.fetchData(); },
        goToPage(page) { if (page !== '...') { this.currentPage = page; this.fetchData(); } },
        prevPage() { if (this.currentPage > 1) { this.currentPage--; this.fetchData(); } },
        nextPage() { if (this.currentPage < this.totalPages) { this.currentPage++; this.fetchData(); } },
        fetchData() {
            this.loading = true;
            this.selectedIds = [];
            const params = new URLSearchParams({
                search: this.search || '',
                per_page: String(this.perPage),
                page: String(this.currentPage),
                sort: this.sortKey || 'created_at',
                dir: this.sortDir || 'desc'
            });
            fetch('{{ route('admin.instructor-applications.index') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(json => {
                this.applications = json.data || [];
                this.total = json.total || 0;
                this.lastPage = json.last_page || 1;
                this.perPage = json.per_page || this.perPage;
                this.currentPage = json.current_page || this.currentPage;
            })
            .catch(err => console.error(err))
            .finally(() => { this.loading = false; });
        },
        highlightText(text) {
            if (!this.search || !text) return text;
            const regex = new RegExp(`(${this.search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return String(text).replace(regex, '<span class="search-highlight">$1</span>');
        },
        formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        acceptAllSelected() {
            if (this.selectedIds.length === 0) {
                Swal.fire('Peringatan', 'Pilih minimal satu pengajuan untuk disetujui', 'warning');
                return;
            }
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menyetujui ${this.selectedIds.length} pengajuan instruktur`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, setujui semua!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let completed = 0;
                    this.selectedIds.forEach(id => {
                        fetch(`/admin/instructor-applications/${id}/approve`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        }).finally(() => {
                            completed++;
                            if (completed === this.selectedIds.length) {
                                Swal.fire('Berhasil!', 'Semua pengajuan yang dipilih telah disetujui.', 'success').then(() => location.reload());
                            }
                        });
                    });
                }
            });
        },
        rejectAllSelected() {
            if (this.selectedIds.length === 0) {
                Swal.fire('Peringatan', 'Pilih minimal satu pengajuan untuk ditolak', 'warning');
                return;
            }
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menolak ${this.selectedIds.length} pengajuan instruktur`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, tolak semua!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let completed = 0;
                    this.selectedIds.forEach(id => {
                        fetch(`/admin/instructor-applications/${id}/reject`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        }).finally(() => {
                            completed++;
                            if (completed === this.selectedIds.length) {
                                Swal.fire('Berhasil!', 'Semua pengajuan yang dipilih telah ditolak.', 'success').then(() => location.reload());
                            }
                        });
                    });
                }
            });
        },
        deleteApplication(id) {
            Swal.fire({ title: "Hapus pengajuan?", text: "Data tidak dapat dikembalikan!", icon: "warning", showCancelButton: true, confirmButtonColor: "#ef4444", cancelButtonColor: "#6b7280", confirmButtonText: "Ya, hapus!" }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/instructor-applications/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                    .then(r => r.json()).then(res => { if (res.success) { Swal.fire("Dihapus!", res.message, "success").then(() => location.reload()); } else { Swal.fire("Error!", res.message || "Terjadi kesalahan", "error"); } });
                }
            });
        }
    }
}
</script>

@if(session('success'))
<script>Swal.fire({ title: "{{ session('success') }}", icon: "success", timer: 3000, showConfirmButton: false });</script>
@endif
@if(session('error'))
<script>Swal.fire({ title: "Error!", text: "{{ session('error') }}", icon: "error" });</script>
@endif
@endsection
