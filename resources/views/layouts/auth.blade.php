<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen">
    <main class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-[420px]">
            <a href="{{ route('home') }}" class="mb-4 flex items-center justify-center gap-2.5 no-underline">
                <x-app-logo-icon class="size-9 object-contain" />
                <span class="text-[15px] font-semibold text-ink-strong">Ticketing — PT Gandasari Group Investama</span>
            </a>

            <section class="panel" aria-label="{{ $title }}">
                <div class="panel-strip">
                    <h1 class="panel-tab m-0">{{ $title }}</h1>
                </div>

                <div class="flex flex-col gap-4 p-5">
                    {{ $slot }}
                </div>
            </section>
        </div>
    </main>
</body>

</html>
