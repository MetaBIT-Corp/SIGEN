@if ($paginator->hasPages())
    <ul class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link bg-danger text-white">Anterior</span>
            </li>
        @else
            <li class="page-item ">
                <a id="previous_btn" class="page-link bg-danger text-white" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item ">
                <a id="next_btn" class="page-link bg-danger text-white" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a>
            </li>
        @else
            <li class="page-item disabled " aria-disabled="true">
                <span class="page-link bg-danger text-white">Siguiente</span>
            </li>
        @endif
    </ul>
@endif
