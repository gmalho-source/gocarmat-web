@php $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'branco'); @endphp

<section class="mt-6 rounded-[32px] px-8 py-14 sm:px-12 xl:px-24 {{ $f['sec'] }}">
    @if (filled($data['titulo'] ?? null))
        <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[44px] {{ $f['titulo'] }}">
            {{ $data['titulo'] }}
        </h2>
    @endif

    <div class="prose-post mt-6 max-w-[860px] {{ $f['texto'] }}">
        {!! $data['corpo'] !!}
    </div>
</section>
