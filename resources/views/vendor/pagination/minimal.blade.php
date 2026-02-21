@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-6">
        <div class="flex flex-1 justify-start items-center space-x-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center h-9 w-9 text-slate-300 bg-white border border-slate-100 rounded-full cursor-default transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-9 w-9 text-slate-500 bg-white border border-slate-200 rounded-full hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2" aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden lg:flex items-center space-x-1.5">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true" class="px-2 text-slate-400 text-xs font-bold tracking-widest">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="inline-flex items-center justify-center h-9 w-9 text-sm font-bold bg-brand-primary text-white rounded-full shadow-md shadow-brand-primary/20 transition-all duration-200">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center h-9 w-9 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:text-brand-primary hover:border-brand-primary transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-slate-300" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-9 w-9 text-slate-500 bg-white border border-slate-200 rounded-full hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2" aria-label="{{ __('pagination.next') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center h-9 w-9 text-slate-300 bg-white border border-slate-100 rounded-full cursor-default transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            @endif
        </div>
        
        <div class="hidden sm:flex items-center space-x-1 text-slate-400 text-[11px] font-medium tracking-wide">
            <span>Showing</span>
            <span class="text-slate-700 font-bold">{{ $paginator->firstItem() }}</span>
            <span>to</span>
            <span class="text-slate-700 font-bold">{{ $paginator->lastItem() }}</span>
            <span>of</span>
            <span class="text-slate-700 font-bold">{{ $paginator->total() }}</span>
        </div>
    </nav>
@endif
