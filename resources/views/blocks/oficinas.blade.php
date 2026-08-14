<section class="mt-16 xl:mt-24">
    @if (filled($data['titulo'] ?? null))
        <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[44px]">
            {{ $data['titulo'] }}
        </h2>
    @endif
    @include('partials.offices-grid')
</section>
