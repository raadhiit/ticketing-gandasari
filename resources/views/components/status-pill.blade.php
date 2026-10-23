@props(['status'])

@php
    $status = strtoupper((string) ($status instanceof \BackedEnum ? $status->value : $status));
@endphp

<span {{ $attributes->class(['pill', 'pill-'.strtolower($status)]) }}>{{ str_replace('_', ' ', $status) }}</span>
