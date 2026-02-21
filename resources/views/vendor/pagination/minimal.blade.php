@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-2">
        <div class="flex flex-1 justify-start items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-default rounded-lg leading-5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-lg leading-5 hover:text-brand-primary hover:border-brand-primary transition-colors focus:z-10 focus:outline-none focus:ring ring-slate-300 active:bg-slate-100 active:text-slate-700" aria-label="{{ __('pagination.previous') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden md:flex items-center space-x-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-400 bg-white cursor-default leading-5">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-brand-primary border border-brand-primary rounded-lg leading-5">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg leading-5 hover:text-brand-primary hover:border-brand-primary transition-colors focus:z-10 focus:outline-none focus:ring ring-slate-300 active:bg-slate-100 active:text-slate-700" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-lg leading-5 hover:text-brand-primary hover:border-brand-primary transition-colors focus:z-10 focus:outline-none focus:ring ring-slate-300 active:bg-slate-100 active:text-slate-700" aria-label="{{ __('pagination.next') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-default rounded-lg leading-5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
        
        <div class="hidden sm:block">
            <p class="text-xs text-slate-500 leading-5">
                {!! __('Showing') !!}
                <span class="font-bold text-slate-800">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-bold text-slate-800">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-bold text-slate-800">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>
    </nav>
@endif
