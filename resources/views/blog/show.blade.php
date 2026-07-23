@extends('layouts.site')

@section('title', $post->metaTitle() . ' · GOCARMAT Blog')
@section('meta_description', $post->metaDescription())

@push('meta')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->metaTitle() }}">
    <meta property="og:description" content="{{ $post->metaDescription() }}">
    @php $ogImage = $post->og_image ?: $post->featured_image; @endphp
    @if ($ogImage)
        <meta property="og:image" content="{{ asset('storage/' . $ogImage) }}">
    @endif
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    <link rel="canonical" href="{{ route('blog.show', $post->slug) }}">
@endpush

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    <article class="mx-auto mt-10 max-w-[860px]">
        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">
            <a href="{{ route('blog.index') }}" class="text-energia hover:underline">Blog</a>
            <span class="mx-2 text-carbono/40">/</span>
            {{ $post->published_at?->translatedFormat('j F, Y') }}
        </p>

        <h1 class="mt-6 text-4xl font-bold leading-[1.15] tracking-[-0.03em] sm:text-5xl">{{ $post->title }}</h1>

        @if ($post->categories->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-3">
                @foreach ($post->categories as $category)
                    <span class="rounded border border-carbono/40 px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] text-carbono/70">{{ $category->name }}</span>
                @endforeach
            </div>
        @endif

        @if ($post->featured_image)
            <div class="mt-10 overflow-hidden rounded-[24px]">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full object-cover">
            </div>
        @endif

        <div class="prose-post mt-10">
            {!! $post->body !!}
        </div>
    </article>

    {{-- CTA --}}
    <section class="mx-auto mt-20 flex max-w-[860px] flex-col items-start gap-6 rounded-[32px] bg-energia px-10 py-10 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold leading-[1.2] tracking-[-0.03em] text-white">O seu carro precisa de atenção?</h2>
            <p class="mt-2 text-base font-light text-gelo">Marque o seu serviço numa das 4 oficinas GOCARMAT.</p>
        </div>
        <x-pill variant="lima" :href="route('marcacoes')">Marcar Serviço</x-pill>
    </section>

    {{-- RELACIONADOS --}}
    @if ($related->isNotEmpty())
        <section class="mt-24">
            <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-4xl">
                Artigos relacionados
            </h2>
            <div class="mt-10 grid gap-8 sm:grid-cols-3">
                @foreach ($related as $item)
                    <a href="{{ route('blog.show', $item->slug) }}" class="group flex flex-col bg-white transition hover:-translate-y-1">
                        @if ($item->featured_image)
                            <div class="h-[200px] shrink-0 overflow-hidden">
                                <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                            </div>
                        @endif
                        <div class="px-7 py-7">
                            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $item->published_at?->translatedFormat('j F, Y') }}</p>
                            <h3 class="mt-2 text-xl font-bold leading-[1.25] tracking-[-0.03em]">{{ $item->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
