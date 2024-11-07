@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center mt-16">
    <ul class="flex items-center gap-3">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li>
            <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </span>
        </li>
        @else
        <li>
            <a href="#"
                data-page="{{ $paginator->currentPage() - 1 }}"
                class="px-4 py-2 text-gray-700 hover:bg-vermelho-asteca hover:text-white rounded-md transition duration-300">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
        @endif

        {{-- Numbered Pages --}}
        @php
        $start = max($paginator->currentPage() - 4, 1);
        $end = min($paginator->currentPage() + 4, $paginator->lastPage());
        @endphp

        @for ($i = $start; $i <= $end; $i++)
            <li>
            <a href="#"
                data-page="{{ $i }}"
                class="px-4 py-2 rounded-md transition duration-300 {{ $i == $paginator->currentPage() ? 'bg-vermelho-asteca text-white' : 'text-gray-700 hover:bg-vermelho-asteca hover:text-white' }}">
                {{ $i }}
            </a>
            </li>
            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li>
                <a href="#"
                    data-page="{{ $paginator->currentPage() + 1 }}"
                    class="px-4 py-2 text-gray-700 hover:bg-vermelho-asteca hover:text-white rounded-md transition duration-300">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            @else
            <li>
                <span class="px-4 py-2 text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </li>
            @endif
    </ul>
</nav>
@endif