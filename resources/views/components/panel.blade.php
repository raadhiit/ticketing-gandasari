@props(['title', 'count' => null])

<section {{ $attributes->class('panel') }} aria-label="{{ $title }}">
    <div class="panel-strip">
        <div class="panel-tab">{{ $title }}@if (! is_null($count))<span class="cnt">{{ $count }}</span>@endif</div>
    </div>
    {{ $slot }}
</section>
