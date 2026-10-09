{{-- 'colunas' => 1 dá uma lista de uma só coluna (ex: ao lado de outro
     bloco, num grid de página); por omissão são 2 colunas.
     'abrir_primeiro' abre a primeira pergunta por omissão.
     'margem' substitui a margem superior por omissão (mt-16 xl:mt-24) —
     usar '' quando o bloco já vem dentro de um grid de página com a sua
     própria margem. --}}
@php
    $colunas = (int) ($data['colunas'] ?? 2) === 1 ? '' : 'sm:grid-cols-2';
    $margem = $data['margem'] ?? 'mt-16 xl:mt-24';
@endphp

<section class="{{ $margem }}">
    <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-4xl">
        {{ $data['titulo'] ?? 'Perguntas frequentes' }}
    </h2>

    <div class="mt-8 grid gap-4 {{ $colunas }}">
        @foreach ($data['itens'] ?? [] as $item)
            <details class="group h-fit rounded-2xl bg-white open:bg-carbono" {{ ($data['abrir_primeiro'] ?? false) && $loop->first ? 'open' : '' }}>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-8 py-6 text-lg font-bold tracking-[-0.3px] text-carbono transition hover:opacity-70 group-open:text-lima">
                    {{ $item['pergunta'] }}
                    <span class="shrink-0 text-2xl leading-none text-energia transition group-open:rotate-45 group-open:text-lima" aria-hidden="true">+</span>
                </summary>
                <p class="px-8 pb-7 text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                    {{ $item['resposta'] }}
                </p>
            </details>
        @endforeach
    </div>
</section>
