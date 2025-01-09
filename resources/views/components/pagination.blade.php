@if ($paginator->hasPages())
    <nav id="pagination-nav" class="flex justify-center mt-8">
        <ul class="flex flex-wrap items-center gap-1">
            {{-- Anterior --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="inline-flex h-10 w-10 items-center justify-center rounded-lg border bg-white text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </a>
                @endif
            </li>

            {{-- Números das Páginas --}}
            @foreach ($elements as $element)
                {{-- Separador --}}
                @if (is_string($element))
                    <li>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-700">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Links das Páginas --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-vermelho-asteca text-white">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                   class="inline-flex h-10 w-10 items-center justify-center rounded-lg border bg-white text-gray-700 hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Próxima --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="inline-flex h-10 w-10 items-center justify-center rounded-lg border bg-white text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                @else
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif 