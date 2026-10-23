<div class="flex flex-wrap items-start gap-4">
    <nav class="panel w-[220px] max-md:w-full" aria-label="{{ __('Settings') }}">
        <div class="panel-strip">
            <div class="panel-tab">{{ __('Pengaturan') }}</div>
        </div>

        @foreach ([
            ['route' => 'profile.edit', 'label' => __('Profil')],
            ['route' => 'security.edit', 'label' => __('Keamanan')],
        ] as $link)
            <a href="{{ route($link['route']) }}" wire:navigate @class([
                'block border-t border-line px-3 py-3 text-sm font-semibold no-underline',
                'bg-thread text-ink-strong' => request()->routeIs($link['route']),
                'text-link' => ! request()->routeIs($link['route']),
            ])>{{ $link['label'] }}</a>
        @endforeach
    </nav>

    <div class="flex min-w-0 flex-1 flex-col gap-3">
        {{ $slot }}
    </div>
</div>
