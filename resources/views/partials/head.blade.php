<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Ticketing Gandasari') : config('app.name', 'Ticketing Gandasari') }}
</title>

<link rel="icon" href="{{ asset('storage/img/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('storage/img/logo.png') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
