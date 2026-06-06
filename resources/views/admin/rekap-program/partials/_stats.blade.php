<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 p-6">
        <p class="text-sm text-slate-500 dark:text-gray-400">Total Peserta</p>
        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ number_format($totalPaidUsers) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 p-6">
        <p class="text-sm text-slate-500 dark:text-gray-400">Total Pemasukan</p>
        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 p-6">
        <p class="text-sm text-slate-500 dark:text-gray-400">Peserta Selesai</p>
        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ number_format($completedUsers) }}</p>
        <p class="text-xs text-slate-400 mt-1">Berdasarkan sertifikat.</p>
    </div>
</div>
