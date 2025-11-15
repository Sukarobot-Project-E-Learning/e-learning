@if(isset($items) && $items->hasPages())
<div class="flex flex-col items-center justify-between px-4 py-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 sm:flex-row sm:px-6">
    <!-- Showing Info -->
    <div class="flex items-center mb-4 sm:mb-0">
        <span class="text-sm text-gray-700 dark:text-gray-400">
            Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $items->firstItem() ?? 0 }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ $items->lastItem() ?? 0 }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $items->total() }}</span> results
        </span>
    </div>

    <!-- Pagination Controls -->
    <nav aria-label="Table navigation">
        <ul class="inline-flex items-center -space-x-px">
            <!-- Previous Button -->
            <li>
                <a href="{{ $items->previousPageUrl() ?? '#' }}" 
                   class="flex items-center justify-center px-3 py-2 ml-0 text-sm leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white {{ !$items->onFirstPage() ? '' : 'opacity-50 cursor-not-allowed' }}"
                   @if($items->onFirstPage()) onclick="return false;" @endif>
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Previous
                </a>
            </li>
            
            <!-- Page Numbers -->
            @php
                $currentPage = $items->currentPage();
                $lastPage = $items->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp
            
            @if($startPage > 1)
                <li>
                    <a href="{{ $items->url(1) }}" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                </li>
                @if($startPage > 2)
                <li>
                    <span class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span>
                </li>
                @endif
            @endif
            
            @for($page = $startPage; $page <= $endPage; $page++)
                @if($page == $currentPage)
                <li>
                    <a href="{{ $items->url($page) }}" aria-current="page" class="flex items-center justify-center px-4 py-2 text-sm font-semibold text-white border border-purple-600 bg-purple-600 hover:bg-purple-700 dark:border-purple-500 dark:bg-purple-600 dark:hover:bg-purple-700">{{ $page }}</a>
                </li>
                @else
                <li>
                    <a href="{{ $items->url($page) }}" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $page }}</a>
                </li>
                @endif
            @endfor
            
            @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)
                <li>
                    <span class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">...</span>
                </li>
                @endif
                <li>
                    <a href="{{ $items->url($lastPage) }}" class="flex items-center justify-center px-4 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $lastPage }}</a>
                </li>
            @endif
            
            <!-- Next Button -->
            <li>
                <a href="{{ $items->nextPageUrl() ?? '#' }}" 
                   class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white {{ $items->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                   @if(!$items->hasMorePages()) onclick="return false;" @endif>
                    Next
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</div>
@endif

