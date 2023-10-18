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
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <a href="#"
                        class="btn btn-circle btn-icon btn-sm border-0 btn-light-primary mr-2 my-1 disabled">{{ $element }}</a>
                @endif
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a href="{{ $url }}"
                                class="btn btn-circle btn-icon btn-sm border-0 btn-light-primary btn-hover-primary mr-2 my-1 active font-weight-bold">{{ $page }}</a>
                        @else
                            <a href="{{ $url }}"
                                class="btn btn-circle btn-icon btn-sm border-0 btn-light-primary mr-2 my-1">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

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
