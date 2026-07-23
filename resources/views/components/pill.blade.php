{{-- Botão pill do design system. variant: lima | dark | outline-dark | outline-light | blue --}}
@props(['variant' => 'lima', 'href' => '#', 'mono' => false])
@php
    $styles = [
        'lima' => 'bg-lima border-lima text-carbono',
        'dark' => 'bg-carbono border-carbono text-lima',
        'blue' => 'bg-energia border-energia text-precision',
        'outline-dark' => 'bg-transparent border-carbono text-carbono',
        'outline-light' => 'bg-transparent border-gelo text-gelo',
    ];
    $font = $mono ? 'font-mono font-semibold uppercase' : 'font-semibold';
@endphp
<a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center gap-4 rounded-full border-2 px-[30px] py-[15px] text-[15px] leading-[1.68] tracking-[-0.3px] transition hover:opacity-85 {$styles[$variant]} {$font}"]) }}>
    <span>{{ $slot }}</span>
    <x-ui.icon name="arrow-right" class="size-5 shrink-0" />
</a>
