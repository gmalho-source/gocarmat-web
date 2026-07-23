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
    ];
@endphp
<svg {{ $attributes->merge(['class' => 'size-5']) }} viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="{{ $paths[$name] ?? '' }}" />
</svg>
