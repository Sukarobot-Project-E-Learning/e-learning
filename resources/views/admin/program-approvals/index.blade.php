@extends('admin.layouts.app')

@section('title', 'Persetujuan Program')

@section('content')
    <div class="container px-6 mx-auto">
        <!-- Page Header -->
        <div class="my-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Persetujuan Program dari Instruktur</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Review dan setujui atau tolak program yang diajukan oleh instruktur</p>
        </div>

        <!-- Program Approvals Table -->
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Judul Program</th>
                            <th class="px-4 py-3">Instruktur</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal Diajukan</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($approvals ?? [] as $approval)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div>
                                        <p class="font-semibold">{{ $approval->title }}</p>
                                        @if($approval->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit($approval->description, 50) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div>
                                    <p class="font-medium">{{ $approval->instructor_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $approval->instructor_email ?? '' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $approval->category ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $approval->type == 'online' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($approval->type == 'offline' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200') }}">
                                    {{ ucfirst($approval->type ?? '-') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if($approval->status == 'pending')
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                    Menunggu
                                </span>
                                @elseif($approval->status == 'approved')
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                    Disetujui
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                    Ditolak
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $approval->created_at ? date('d M Y', strtotime($approval->created_at)) : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('admin.program-approvals.show', $approval->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Detail</a>
                                    @if($approval->status == 'pending')
                                    <form action="{{ route('admin.program-approvals.approve', $approval->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui program ini?');">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400">Setujui</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada program yang menunggu persetujuan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @include('components.pagination', ['items' => $approvals ?? null])
        </div>
    </div>
@endsection

