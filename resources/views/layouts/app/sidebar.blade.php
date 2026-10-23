@php
    $user = auth()->user();
    $routeTicket = request()->route('ticket');
    $ticketNumber = $routeTicket instanceof \App\Models\Ticket
        ? $routeTicket->ticket_number
        : (is_scalar($routeTicket) ? \App\Models\Ticket::whereKey($routeTicket)->value('ticket_number') : null);

    // [icon, breadcrumb parent, page title] keyed by route name.
    $pages = [
        'dashboard' => ['home', null, __('Dashboard')],
        'tickets.index' => ['inbox', null, __('Tickets')],
        'tickets.create' => ['doc', __('Tickets'), __('Buat Ticket Baru')],
        'tickets.show' => ['doc', __('Tickets'), $ticketNumber],
        'tickets.edit' => ['doc', __('Tickets'), $ticketNumber ? $ticketNumber.' — '.__('Edit') : __('Edit')],
        'reports' => ['chart', null, __('Laporan')],
        'activity-log' => ['clock', null, __('Activity Log')],
        'settings.departments' => ['building', null, __('Departemen')],
        'settings.ticket-categories' => ['tag', null, __('Kategori Ticket')],
        'settings.users' => ['users', null, __('Pengguna')],
        'profile.edit' => ['users', null, __('Pengaturan Profil')],
        'security.edit' => ['users', null, __('Keamanan')],
    ];
    [$pageIcon, $pageParent, $pageTitle] = $pages[request()->route()?->getName()] ?? ['doc', null, $title ?? config('app.name')];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen">
    <div class="flex min-h-screen flex-col min-[701px]:flex-row">
        {{-- Rail --}}
        <nav class="flex shrink-0 flex-row items-center print:hidden overflow-x-auto bg-rail p-0 min-[701px]:sticky min-[701px]:top-0 min-[701px]:h-screen min-[701px]:w-14 min-[701px]:flex-col min-[701px]:gap-0.5 min-[701px]:overflow-visible min-[701px]:py-2.5"
            aria-label="{{ __('Menu utama') }}">
            <livewire:notification-bell :sidebar="true" />
            <div class="rail-sep"></div>

            @can('ticket.assign')
                <x-rail-link :href="route('dashboard')" :label="__('Dashboard')" :active="request()->routeIs('dashboard')">
                    <path d="M3 10.5 12 3l9 7.5" />
                    <path d="M5 9.5V21h14V9.5" />
                    <path d="M10 21v-6h4v6" />
                </x-rail-link>
            @endcan

            <x-rail-link :href="route('tickets.index')" :label="__('Tickets')" :active="request()->routeIs('tickets.*')">
                <path d="M22 12h-6l-2 3h-4l-2-3H2" />
                <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
            </x-rail-link>

            @can('report.view')
                <x-rail-link :href="route('reports')" :label="__('Laporan')" :active="request()->routeIs('reports')">
                    <path d="M3 20h18" />
                    <rect x="5" y="11" width="3" height="7" />
                    <rect x="10.5" y="6" width="3" height="12" />
                    <rect x="16" y="13" width="3" height="5" />
                </x-rail-link>
            @endcan

            @can('activity-log.view')
                <x-rail-link :href="route('activity-log')" :label="__('Activity Log')" :active="request()->routeIs('activity-log')">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 7v5l3 2" />
                </x-rail-link>
            @endcan

            @can('settings.manage')
                <div class="rail-sep"></div>

                <x-rail-link :href="route('settings.departments')" :label="__('Departemen')" :active="request()->routeIs('settings.departments')">
                    <rect x="5" y="3" width="14" height="18" rx="1" />
                    <path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2" />
                </x-rail-link>

                <x-rail-link :href="route('settings.ticket-categories')" :label="__('Kategori')" :active="request()->routeIs('settings.ticket-categories')">
                    <path d="M3 3h9l9 9-9 9-9-9z" />
                    <circle cx="7.5" cy="7.5" r="1.3" />
                </x-rail-link>

                <x-rail-link :href="route('settings.users')" :label="__('Pengguna')" :active="request()->routeIs('settings.users')">
                    <circle cx="9" cy="8" r="3.5" />
                    <path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6" />
                    <path d="M16 4.6a3.5 3.5 0 0 1 0 6.8M18 14.3c2.2.7 3.5 2.6 3.5 5.7" />
                </x-rail-link>
            @endcan
        </nav>

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Top bar --}}
            <header class="flex h-14 items-center print:hidden gap-7 overflow-hidden bg-white px-5">
                <flux:dropdown position="bottom" align="start">
                    <button type="button" data-test="sidebar-menu-button"
                        class="flex cursor-pointer items-center gap-2 border-0 bg-transparent p-0 text-[15px] font-semibold whitespace-nowrap text-ink-strong">
                        <span class="flex size-[22px] items-center justify-center rounded-full bg-[#c2610c] text-xs font-bold text-white">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                        {{ $user->name }}
                        <svg class="size-3.5 fill-none stroke-current" style="stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
                    </button>

                    <flux:menu>
                        <div class="px-2 py-1.5 text-start text-sm leading-tight">
                            <div class="truncate font-semibold text-ink-strong">{{ $user->name }}</div>
                            <div class="truncate text-xs text-muted">{{ $user->email }}</div>
                        </div>

                        <flux:menu.separator />

                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer" data-test="logout-button">
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>

                <span class="text-[15px] font-semibold whitespace-nowrap text-ink-strong">Ticketing — PT Gandasari Group Investama</span>
            </header>

            {{-- Title bar --}}
            <div class="box-border flex min-h-[58px] print:hidden flex-wrap items-center gap-3 bg-titlebar px-5 py-3">
                <svg class="size-[26px] shrink-0 fill-none stroke-[#5b4bc4]" style="stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round" viewBox="0 0 24 24" aria-hidden="true">
                    @switch($pageIcon)
                        @case('home')
                            <path d="M3 10.5 12 3l9 7.5" /><path d="M5 9.5V21h14V9.5" />
                            @break
                        @case('inbox')
                            <path d="M22 12h-6l-2 3h-4l-2-3H2" /><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                            @break
                        @case('chart')
                            <path d="M3 20h18" /><rect x="5" y="11" width="3" height="7" /><rect x="10.5" y="6" width="3" height="12" /><rect x="16" y="13" width="3" height="5" />
                            @break
                        @case('clock')
                            <circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" />
                            @break
                        @case('building')
                            <rect x="5" y="3" width="14" height="18" rx="1" /><path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2" />
                            @break
                        @case('tag')
                            <path d="M3 3h9l9 9-9 9-9-9z" /><circle cx="7.5" cy="7.5" r="1.3" />
                            @break
                        @case('users')
                            <circle cx="9" cy="8" r="3.5" /><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6" /><path d="M16 4.6a3.5 3.5 0 0 1 0 6.8M18 14.3c2.2.7 3.5 2.6 3.5 5.7" />
                            @break
                        @default
                            <path d="M6 3h9l4 4v14H6z" /><path d="M14 3v5h5M9 13h6M9 17h6" />
                    @endswitch
                </svg>

                @if ($pageParent)
                    <nav aria-label="Breadcrumb" class="flex items-center gap-2.5">
                        <a href="{{ route('tickets.index') }}" wire:navigate class="text-xl font-semibold text-muted no-underline hover:text-ink-strong">{{ $pageParent }}</a>
                        <span class="text-xl text-muted" aria-hidden="true">›</span>
                        <h1 class="m-0 text-xl font-bold text-ink-strong">{{ $pageTitle }}</h1>
                    </nav>
                @else
                    <h1 class="m-0 text-xl font-bold text-ink-strong">{{ $pageTitle }}</h1>
                @endif
            </div>

            <main class="flex flex-1 flex-col gap-3 px-4 pt-4 pb-7">
                {{ $slot }}
            </main>
        </div>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
