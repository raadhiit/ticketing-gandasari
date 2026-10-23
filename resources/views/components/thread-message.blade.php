@props(['name', 'color' => '#475569', 'role' => null, 'internal' => false, 'when' => null])

<div {{ $attributes->class('msg') }}>
    <div class="avatar" style="background: {{ $color }}" aria-hidden="true">{{ mb_strtoupper(mb_substr($name ?: 'U', 0, 1)) }}</div>

    <div class="min-w-0 flex-1">
        <div class="msg-head">
            <b>{{ $name }}</b>

            @if ($role)
                <span class="role role-{{ $role }}">{{ $role === 'reporter' ? __('Pelapor') : __('Agent') }}</span>
            @endif

            @if ($internal)
                <span class="role role-internal">{{ __('Internal') }}</span>
            @endif

            @if ($when)
                <span class="when">{{ $when }}</span>
            @endif
        </div>

        {{ $slot }}
    </div>
</div>
