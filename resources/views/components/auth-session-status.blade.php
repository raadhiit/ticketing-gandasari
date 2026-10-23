@props([
    'status',
])

@if ($status)
    <div {{ $attributes->class('notice') }}>
        {{ $status }}
    </div>
@endif
