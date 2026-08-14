@php
    $alturas = ['pequeno' => 'h-8 xl:h-12', 'medio' => 'h-16 xl:h-24', 'grande' => 'h-24 xl:h-40'];
@endphp
<div class="{{ $alturas[$data['altura'] ?? 'medio'] ?? $alturas['medio'] }}"></div>
