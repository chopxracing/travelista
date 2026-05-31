@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="d-flex justify-content-end">
        <ul class="pagination pagination-sm mb-0" style="border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="border-left: 1px solid #dee2e6; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                        <i class="fas fa-chevron-left"></i> Назад
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="border-left: 1px solid #dee2e6; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6; transition: all 0.2s; color: #007bff;">
                        <i class="fas fa-chevron-left"></i> Назад
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" style="padding: 0.5rem 0.75rem; color: #6c757d;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="background-color: #007bff; border-color: #007bff; padding: 0.5rem 0.75rem;">
                                    {{ $page }}
                                    <span class="sr-only">(current)</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="padding: 0.5rem 0.75rem; color: #007bff; transition: all 0.2s;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="border-right: 1px solid #dee2e6; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6; transition: all 0.2s; color: #007bff;">
                        Далее <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="border-right: 1px solid #dee2e6; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                        Далее <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .pagination a.page-link:hover {
            background-color: #e9ecef;
            color: #0056b3;
        }
        
        .pagination a.page-link:active {
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        }
        
        .pagination .page-link {
            border: 1px solid #dee2e6;
        }
    </style>
@endif
