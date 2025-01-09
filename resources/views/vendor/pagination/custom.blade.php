@if ($paginator->hasPages())
    <nav id="pagination-nav">
        <ul class="flex flex-wrap justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <button disabled class="h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="h-10 w-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-700">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="h-10 w-10 flex items-center justify-center rounded-lg bg-vermelho-asteca text-white">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" 
                                   class="h-10 w-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="h-10 w-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li>
                    <button disabled class="h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </li>
            @endif
        </ul>
    </nav>
@endif