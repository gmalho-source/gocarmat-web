@php
    // Com mais de 4 oficinas, a última (a mais recente) fica em destaque numa
    // linha própria acima da grelha de 4, a ocupar 3/4 da largura e centrada
    // — evita ficar sozinha e desalinhada no fim de uma grelha de 4 colunas.
    $destaque = $offices->count() > 4 ? $offices->last() : null;
    $resto = $destaque ? $offices->slice(0, -1) : $offices;
@endphp

<div class="mt-14">
    @if ($destaque)
        <div class="mx-auto mb-6 sm:w-3/4 xl:mb-10">
            @include('partials.offices-grid-card', ['office' => $destaque])
        </div>
    @endif

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4 xl:gap-10">
        @foreach ($resto as $office)
            @include('partials.offices-grid-card', ['office' => $office])
        @endforeach
    </div>
</div>
