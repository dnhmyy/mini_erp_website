@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-start py-6 space-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-400 bg-slate-100 rounded-xl cursor-default">
                Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-800 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all duration-200">
                Prev
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center space-x-2">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="px-2 text-slate-400 font-bold">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page">
                                <span class="inline-flex items-center justify-center h-10 w-10 text-sm font-bold bg-[#00271b] text-white rounded-xl shadow-sm">{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center justify-center h-10 w-10 text-sm font-bold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all duration-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-800 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all duration-200">
                Next
            </a>
        @else
            <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-400 bg-slate-100 rounded-xl cursor-default">
                Next
            </span>
        @endif
    </nav>
@endif
