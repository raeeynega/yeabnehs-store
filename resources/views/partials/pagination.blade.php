@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-1.5">
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2.5 text-[12px] text-gray-300 border border-gray-100 rounded-lg font-medium">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2.5 text-[12px] border border-gray-200 rounded-lg hover:border-brand hover:text-brand transition-colors font-medium">&laquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-2.5 text-[12px] text-gray-300">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2.5 text-[12px] bg-brand text-white rounded-lg font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2.5 text-[12px] border border-gray-200 rounded-lg hover:border-brand hover:text-brand transition-colors font-medium">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2.5 text-[12px] border border-gray-200 rounded-lg hover:border-brand hover:text-brand transition-colors font-medium">&raquo;</a>
        @else
            <span class="px-4 py-2.5 text-[12px] text-gray-300 border border-gray-100 rounded-lg font-medium">&raquo;</span>
        @endif
    </nav>
@endif
