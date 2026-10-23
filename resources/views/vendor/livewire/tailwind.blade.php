@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';

$pageName = $paginator->getPageName();
$edge = 'px-[9px] py-1 text-[#9ca3af]';
$link = 'cursor-pointer rounded-sm border-0 bg-transparent px-[9px] py-1 font-medium text-link hover:text-link-hover';
@endphp

<div>
    @if ($paginator->total() > 0)
        <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-end gap-1.5 px-4 py-3 text-sm">
            @if ($paginator->onFirstPage())
                <span class="{{ $edge }}">{{ __('Sebelumnya') }}</span>
            @else
                <button type="button" wire:click="previousPage('{{ $pageName }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="{{ $link }}">{{ __('Sebelumnya') }}</button>
            @endif

            @forelse ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true" class="{{ $edge }}">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="rounded-sm bg-tab px-[9px] py-1 font-medium text-white">{{ $page }}</span>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $pageName }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="{{ $link }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</button>
                        @endif
                    @endforeach
                @endif
            @empty
                <span aria-current="page" class="rounded-sm bg-tab px-[9px] py-1 font-medium text-white">1</span>
            @endforelse

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('{{ $pageName }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="{{ $link }}">{{ __('Berikutnya') }}</button>
            @else
                <span class="{{ $edge }}">{{ __('Berikutnya') }}</span>
            @endif
        </nav>
    @endif
</div>
