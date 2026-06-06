<div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Peserta</h2>
        <span class="text-xs font-semibold text-slate-500 dark:text-gray-400">{{ number_format($paidUsers->count()) }} data</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-slate-500 dark:text-gray-400 border-b border-slate-100 dark:border-gray-700">
                    <th class="py-3 pr-4">Nama</th>
                    <th class="py-3 pr-4">Alamat</th>
                    <th class="py-3 pr-4">Tanggal Bayar</th>
                    <th class="py-3 pr-4">Nominal</th>
                    <th class="py-3">Status Selesai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                @forelse ($paidUsers as $user)
                    <tr class="text-slate-700 dark:text-gray-200">
                        <td class="py-3 pr-4 font-semibold text-slate-900 dark:text-white">{{ $user->name }}</td>
                        <td class="py-3 pr-4 text-slate-500 dark:text-gray-400">{{ $user->address }}</td>
                        <td class="py-3 pr-4">{{ $user->paid_at ? \Carbon\Carbon::parse($user->paid_at)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                        <td class="py-3 pr-4">Rp {{ number_format($user->amount ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3">
                            @if ($user->is_completed)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Selesai</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Belum</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500 dark:text-gray-400">
                            Belum ada peserta berbayar untuk program ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
