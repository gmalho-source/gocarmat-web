@props(['name'])
@php
    $paths = [
        'arrow-right' => 'M2 10h15.5m0 0-5.5-5.5M17.5 10 12 15.5',
        'arrow-up-right' => 'M5 15 15 5M6.5 5H15v8.5',
        'phone' => 'M4.1 2.5h3.2l1.6 4-2 1.5a11 11 0 0 0 5.1 5.1l1.5-2 4 1.6v3.2c0 .9-.7 1.6-1.6 1.6C9 17.5 2.5 11 2.5 4.1c0-.9.7-1.6 1.6-1.6Z',
        'envelope' => 'M2.5 4.5h15v11h-15zM2.5 5.5 10 11l7.5-5.5',
        'location-dot' => 'M10 18s-6-5.7-6-10a6 6 0 1 1 12 0c0 4.3-6 10-6 10Zm0-7.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z',
        'bolt' => 'M11.5 1.5 3 11.5h5.5L8 18.5l8.5-10H11z',
        'shopping-bag' => 'M4 6.5h12l-1 11H5l-1-11Zm3 0V5a3 3 0 0 1 6 0v1.5',
        'car-burst' => 'M3 12.5 4.5 8a2 2 0 0 1 1.9-1.4h7.2A2 2 0 0 1 15.5 8l1.5 4.5m-14 0v4h1.5l.7-1.5h9.6l.7 1.5H17v-4m-14 0h14M6.2 14.7h.01m7.6 0h.01M9 3.5l1-2 1 2',
        'unlock' => 'M5.5 9V6.5a4.5 4.5 0 0 1 8.8-1.3M4.5 9h11v8.5h-11V9Zm5.5 3v2.5',
        'wrench' => 'M12.5 2.6a4.5 4.5 0 0 0-5.6 5.6L2.5 12.6a1.8 1.8 0 0 0 2.5 2.5l4.4-4.4a4.5 4.5 0 0 0 5.6-5.6L12.6 7.5 10 6.9 9.4 4.3l3.1-1.7Z',
        'shield' => 'M10 1.8 3.5 4.3v5.2c0 4.1 2.8 6.9 6.5 8.7 3.7-1.8 6.5-4.6 6.5-8.7V4.3L10 1.8Zm-2.5 8 1.8 1.8 3.2-3.4',
        'certificate' => 'M10 12.5a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0 0v6l-2.5-1.7L5 18.5v-6m10 6v-6l-2.5 1.7L10 12.5m0-6.7v.01',
        'search' => 'M9 15.5a6.5 6.5 0 1 0 0-13 6.5 6.5 0 0 0 0 13Zm4.6-1.9 4 4',
    ];

    // Ícones vetoriais sólidos (Font Awesome), diferentes dos de traço acima:
    // preenchimento em vez de contorno, cada um com a sua grelha (viewBox) de origem.
    $solidos = [
        'car' => ['viewBox' => '0 0 64 64', 'path' => 'M14 4H52.125L52.875 6L58.875 24H64V60H58V52H6V60H0V24H5.125L11.125 6L11.875 4H14ZM52.5 24L47.875 10H16.125L11.5 24H52.5ZM6 30V46H58V30H6ZM14 42C11.75 42 10 40.25 10 38C10 35.75 11.75 34 14 34C16.25 34 18 35.75 18 38C18 40.25 16.25 42 14 42ZM54 38C54 40.25 52.25 42 50 42C47.75 42 46 40.25 46 38C46 35.75 47.75 34 50 34C52.25 34 54 35.75 54 38Z'],
    ];
@endphp
@if (isset($solidos[$name]))
    <svg {{ $attributes->merge(['class' => 'size-5']) }} viewBox="{{ $solidos[$name]['viewBox'] }}" fill="currentColor" aria-hidden="true">
        <path d="{{ $solidos[$name]['path'] }}" />
    </svg>
@else
    <svg {{ $attributes->merge(['class' => 'size-5']) }} viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="{{ $paths[$name] ?? '' }}" />
    </svg>
@endif
