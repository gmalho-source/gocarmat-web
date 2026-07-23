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
    ];
@endphp
<svg {{ $attributes->merge(['class' => 'size-5']) }} viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="{{ $paths[$name] ?? '' }}" />
</svg>
