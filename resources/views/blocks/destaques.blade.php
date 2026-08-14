@php $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'carbono'); @endphp
@php $escuro = in_array($data['fundo'] ?? 'carbono', ['carbono', 'energia']); @endphp

<section class="mt-6 rounded-[32px] px-8 py-14 sm:px-12 xl:px-24 {{ $f['sec'] }}">
    @if (filled($data['titulo'] ?? null))
        <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-4xl {{ $f['titulo'] }}">
            {{ $data['titulo'] }}
        </h2>
    @endif

    <div class="mt-8 flex flex-wrap gap-4">
        @foreach ($data['itens'] ?? [] as $item)
            <span class="rounded-full px-[30px] py-[15px] text-base font-semibold leading-[1.68] tracking-[-0.36px] sm:text-lg {{ $escuro ? 'bg-lima text-tecnico' : 'bg-carbono text-lima' }}">
                {{ is_array($item) ? ($item['texto'] ?? '') : $item }}
            </span>
        @endforeach
    </div>
</section>
