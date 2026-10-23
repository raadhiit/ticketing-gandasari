@props(['priority'])

@php
    $priority = strtolower((string) ($priority instanceof \BackedEnum ? $priority->value : $priority));
@endphp

<span {{ $attributes->class('inline-flex items-center') }}><span class="dot dot-{{ $priority }}"></span>{{ ucfirst($priority) }}</span>
