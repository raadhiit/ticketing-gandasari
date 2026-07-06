@php
    $platformItems = collect([
        auth()->user()->can('ticket.assign')
            ? [
                'label' => __('Dashboard'),
                'icon' => 'home',
                'href' => route('dashboard'),
                'current' => request()->routeIs('dashboard'),
            ]
            : null,
        [
            'label' => __('Tickets'),
            'icon' => 'inbox',
            'href' => route('tickets.index'),
            'current' => request()->routeIs('tickets.*'),
        ],
        auth()->user()->can('report.view')
            ? [
                'label' => __('Laporan'),
                'icon' => 'chart-bar',
                'href' => route('reports'),
                'current' => request()->routeIs('reports'),
            ]
            : null,
        auth()->user()->can('activity-log.view')
            ? [
                'label' => __('Activity Log'),
                'icon' => 'clock',
                'href' => route('activity-log'),
                'current' => request()->routeIs('activity-log'),
            ]
            : null,
    ])
        ->filter()
        ->values();

    $settingsItems = collect([
        [
            'label' => __('Departemen'),
            'icon' => 'building-office',
            'href' => route('settings.departments'),
            'current' => request()->routeIs('settings.departments'),
        ],
        [
            'label' => __('Kategori'),
            'icon' => 'tag',
            'href' => route('settings.ticket-categories'),
            'current' => request()->routeIs('settings.ticket-categories'),
        ],
        [
            'label' => __('Pengguna'),
            'icon' => 'users',
            'href' => route('settings.users'),
            'current' => request()->routeIs('settings.users'),
        ],
    ])->values();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-100/70 dark:bg-zinc-950">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
        {{-- Header --}}
        <flux:sidebar.header class="px-4 py-4">
            <div class="flex min-w-0 items-center gap-3">
                <div class="min-w-0 flex-1 overflow-hidden">
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate class="min-w-0" />
                </div>

                <div class="hidden shrink-0 lg:flex">
                    <livewire:notification-bell :sidebar="true" />
                </div>

                <flux:sidebar.collapse class="shrink-0 lg:hidden" />
            </div>
        </flux:sidebar.header>

        {{-- Quick Action --}}
        @if (Route::has('tickets.create'))
            <div class="px-4 pb-4">
                <flux:button :href="route('tickets.create')" wire:navigate icon="plus" variant="primary"
                    class="w-full rounded-xl">
                    {{ __('Buat Ticket') }}
                </flux:button>
            </div>
        @endif

        {{-- Navigation --}}
        <flux:sidebar.nav class="px-3">
            <div class="space-y-6">
                <div>
                    <p
                        class="mb-2 px-3 text-xs font-semibold uppercase tracking-[0.16em] text-zinc-400 dark:text-zinc-500">
                        {{ __('Platform') }}
                    </p>

                    <div class="space-y-1">
                        @can('ticket.assign')
                            <flux:sidebar.item icon="home" :href="route('dashboard')"
                                :current="request()->routeIs('dashboard')" wire:navigate class="rounded-xl">
                                {{ __('Dashboard') }}
                            </flux:sidebar.item>
                        @endcan

                        <flux:sidebar.item icon="inbox" :href="route('tickets.index')"
                            :current="request()->routeIs('tickets.*')" wire:navigate class="rounded-xl">
                            {{ __('Tickets') }}
                        </flux:sidebar.item>

                        @can('report.view')
                            <flux:sidebar.item icon="chart-bar" :href="route('reports')"
                                :current="request()->routeIs('reports')" wire:navigate class="rounded-xl">
                                {{ __('Laporan') }}
                            </flux:sidebar.item>
                        @endcan

                        @can('activity-log.view')
                            <flux:sidebar.item icon="clock" :href="route('activity-log')"
                                :current="request()->routeIs('activity-log')" wire:navigate class="rounded-xl">
                                {{ __('Activity Log') }}
                            </flux:sidebar.item>
                        @endcan
                    </div>
                </div>

                @can('settings.manage')
                    <div>
                        <p
                            class="mb-2 px-3 text-xs font-semibold uppercase tracking-[0.16em] text-zinc-400 dark:text-zinc-500">
                            {{ __('Settings') }}
                        </p>

                        <div class="space-y-1">
                            <flux:sidebar.item icon="building-office" :href="route('settings.departments')"
                                :current="request()->routeIs('settings.departments')" wire:navigate class="rounded-xl">
                                {{ __('Departemen') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="tag" :href="route('settings.ticket-categories')"
                                :current="request()->routeIs('settings.ticket-categories')" wire:navigate
                                class="rounded-xl">
                                {{ __('Kategori') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="users" :href="route('settings.users')"
                                :current="request()->routeIs('settings.users')" wire:navigate class="rounded-xl">
                                {{ __('Pengguna') }}
                            </flux:sidebar.item>
                        </div>
                    </div>
                @endcan
            </div>
        </flux:sidebar.nav>

        <flux:spacer />

        {{-- User Menu --}}
        <div class="px-3 pb-3">
            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-1.5 dark:border-zinc-800 dark:bg-zinc-900">
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </div>
        </div>
    </flux:sidebar>

    {{-- Mobile Header --}}
    <flux:header
        class="border-b border-zinc-200/80 bg-white/95 backdrop-blur-xl lg:hidden dark:border-zinc-800 dark:bg-zinc-950/95">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <div class="ml-2 min-w-0">
            <p class="truncate text-sm font-semibold text-zinc-900 dark:text-white">
                {{ config('app.name') }}
            </p>
        </div>

        <flux:spacer />

        <div class="flex items-center gap-2">
            <div class="rounded-xl border border-zinc-200/70 bg-white p-1 dark:border-zinc-800 dark:bg-zinc-900">
                <livewire:notification-bell />
            </div>

            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <div class="px-2 py-2">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Theme') }}
                        </p>

                        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
                        </flux:radio.group>
                    </div>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
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
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
