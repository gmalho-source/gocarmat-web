<section class="mt-16 xl:mt-24">
    @if (filled($data['titulo'] ?? null))
        <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[44px]">
            {{ $data['titulo'] }}
        </h2>
    @endif

    <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($data['imagens'] ?? [] as $imagem)
            <img src="{{ asset('storage/'.$imagem) }}" alt="" class="h-[280px] w-full rounded-[16px] object-cover" loading="lazy">
        @endforeach
    </div>
</section>
