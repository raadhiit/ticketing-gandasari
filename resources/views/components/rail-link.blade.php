@props(['href', 'label', 'active' => false])

<a href="{{ $href }}" wire:navigate title="{{ $label }}" aria-label="{{ $label }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->class(['rail-link', 'is-active' => $active]) }}>
    <svg class="rail-icon" viewBox="0 0 24 24" aria-hidden="true">{{ $slot }}</svg>
</a>
