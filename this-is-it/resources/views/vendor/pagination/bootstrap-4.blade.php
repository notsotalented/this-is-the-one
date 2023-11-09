@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex flex-wrap py-2 mr-3">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <a href="{{ $paginator->url(1) }}"
                    class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1 disabled"><i
                        class="ki ki-bold-double-arrow-back icon-xs"></i></a>
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1 disabled"><i
                        class="ki ki-bold-arrow-back icon-xs"></i></a>
            @else
                <a href="{{ $paginator->url(1) }}" class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1"><i
                        class="ki ki-bold-double-arrow-back icon-xs"></i></a>
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1"><i
                        class="ki ki-bold-arrow-back icon-xs"></i></a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1"><i
                        class="ki ki-bold-arrow-next icon-xs"></i></a>
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1"><i
                        class="ki ki-bold-double-arrow-next icon-xs"></i></a>
            @else
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1 disabled"><i
                        class="ki ki-bold-arrow-next icon-xs"></i></a>
                <a href="{{ $paginator->url($paginator->lastPage()) }}"
                    class="btn btn-circle btn-icon btn-sm btn-light-primary mr-2 my-1 disabled"><i
                        class="ki ki-bold-double-arrow-next icon-xs"></i></a>
            @endif
            </ul>
        </div>
    </div>
@endif
