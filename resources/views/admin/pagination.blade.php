@if ($paginator->hasPages())
    <nav class="admin-pagination" role="navigation" aria-label="Pagination">
        <span>Showing {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }} of {{ $paginator->total() }}</span>
        <div>
            @if ($paginator->onFirstPage())
                <span class="disabled" aria-disabled="true">&lsaquo; Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; Prev</a>
            @endif
            <span class="current">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">Next &rsaquo;</a>
            @else
                <span class="disabled" aria-disabled="true">Next &rsaquo;</span>
            @endif
        </div>
    </nav>
@endif
