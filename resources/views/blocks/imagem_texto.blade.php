@php $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'branco'); @endphp
@php $imagemEsquerda = ($data['posicao_imagem'] ?? 'direita') === 'esquerda'; @endphp

<section class="mt-6 grid items-center gap-10 overflow-hidden rounded-[32px] px-8 py-14 sm:px-12 xl:grid-cols-2 xl:px-24 {{ $f['sec'] }}">
    <div class="{{ $imagemEsquerda ? 'xl:order-2' : '' }}">
        @if (filled($data['titulo'] ?? null))
            <h2 class="text-3xl font-bold leading-[1.2] tracking-[-0.03em] sm:text-4xl {{ $f['titulo'] }}">
                {{ $data['titulo'] }}
            </h2>
        @endif
        <div class="prose-post mt-5 {{ $f['texto'] }}">
            {!! $data['texto'] ?? '' !!}
        </div>
    </div>

    <div class="{{ $imagemEsquerda ? 'xl:order-1' : '' }}">
        <img src="{{ asset('storage/'.$data['imagem']) }}" alt="{{ $data['titulo'] ?? '' }}" class="w-full rounded-[24px] object-cover">
    </div>
</section>
