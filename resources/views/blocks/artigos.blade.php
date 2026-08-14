@php
    $artigos = \App\Models\Post::published()
        ->orderByDesc('published_at')
        ->take((int) ($data['quantidade'] ?? 3))
        ->get();
@endphp

@if ($artigos->isNotEmpty())
    <section class="mt-16 xl:mt-24">
        <div class="flex flex-wrap items-center justify-between gap-6">
            @if (filled($data['titulo'] ?? null))
                <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[44px]">
                    {{ $data['titulo'] }}
                </h2>
            @endif
            <x-pill variant="outline-dark" :href="route('blog.index')" mono>Ver todos os Artigos</x-pill>
        </div>

        <div class="mt-10 grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($artigos as $artigo)
                <a href="{{ route('blog.show', $artigo->slug) }}" class="group flex flex-col bg-white transition hover:-translate-y-1">
                    @if ($artigo->featured_image)
                        <div class="h-[220px] shrink-0 overflow-hidden">
                            <img src="{{ asset('storage/'.$artigo->featured_image) }}" alt="{{ $artigo->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                        </div>
                    @endif
                    <div class="px-8 py-7">
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $artigo->published_at?->translatedFormat('j F, Y') }}</p>
                        <h3 class="mt-2 text-xl font-bold leading-[1.25] tracking-[-0.03em]">{{ $artigo->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif
