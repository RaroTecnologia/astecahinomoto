@if ($paginator->hasPages())
    <nav id="pagination-nav" class="flex justify-center mt-8">
        <div class="flex flex-wrap justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-2 bg-white hover:bg-gray-50 text-gray-600 rounded-lg border transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 bg-vermelho-asteca text-white rounded-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               class="px-3 py-2 bg-white hover:bg-gray-50 text-gray-600 rounded-lg border transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-2 bg-white hover:bg-gray-50 text-gray-600 rounded-lg border transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif