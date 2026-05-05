{{-- show pagination only if there is more than 1 page --}}
@if ($paginator->lastPage() > 1)

<ul class="pagination" style="margin:0;">

    {{-- First Page Link --}}
    <li class="{{ ($paginator->currentPage() == 1) ? 'disabled' : '' }}">
        <a href="{{ $paginator->url(1) }}">First</a>
    </li>
    {{-- Previous Page Link --}}
    <li class="{{ ($paginator->currentPage() == 1) ? 'disabled' : '' }}">
        <a href="{{ $paginator->previousPageUrl() }}">«</a>
    </li>

    {{-- Page Number Links --}}
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="{{ ($paginator->currentPage() == $i) ? 'active' : '' }}">
            <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
        </li>
        @endfor

        {{-- Next Page Link --}}
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? 'disabled' : '' }}">
            <a href="{{ $paginator->nextPageUrl() }}">»</a>
        </li>
        {{-- Last Page Link --}}
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? 'disabled' : '' }}">
            <a href="{{ $paginator->url($paginator->lastPage()) }}">Last</a>
        </li>

</ul>

@endif