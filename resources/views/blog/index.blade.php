@extends('layouts.site')

@section('title', 'Blog — GOCARMAT · Dicas e novidades sobre o seu automóvel')
@section('meta_description', 'Artigos, dicas de manutenção e novidades do mundo automóvel pela equipa GOCARMAT: travões, pneus, revisões, elétricos e muito mais.')

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    <h1 class="mt-10 font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
        Gocarmat Blog
    </h1>

    @if ($posts->onFirstPage() && $posts->isNotEmpty())
        {{-- Destaque --}}
        @php $featured = $posts->first(); @endphp
        <a href="{{ route('blog.show', $featured->slug) }}" class="group mt-12 block">
            @if ($featured->featured_image)
                <div class="h-[320px] overflow-hidden sm:h-[420px] xl:h-[520px]">
                    <img src="{{ asset('storage/' . $featured->featured_image) }}" alt="{{ $featured->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105">
                </div>
            @endif
            <div class="bg-white px-8 py-9 sm:px-12">
                <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $featured->published_at?->translatedFormat('j F, Y') }}</p>
                <h2 class="mt-3 max-w-[900px] text-3xl font-bold leading-[1.2] tracking-[-0.03em] sm:text-4xl">{{ $featured->title }}</h2>
                <p class="mt-4 max-w-[820px] text-base font-light leading-[1.68] tracking-[-0.16px]">{{ \Illuminate\Support\Str::limit($featured->excerpt, 220, ' ...') }}</p>
            </div>
        </a>
    @endif

    <div class="mt-12 grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($posts->skip($posts->onFirstPage() ? 1 : 0) as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group flex flex-col bg-white transition hover:-translate-y-1">
                @if ($post->featured_image)
                    <div class="h-[240px] shrink-0 overflow-hidden">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                    </div>
                @endif
                <div class="flex flex-1 flex-col px-8 py-8">
                    <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $post->published_at?->translatedFormat('j F, Y') }}</p>
                    <h2 class="mt-3 text-2xl font-bold leading-[1.2] tracking-[-0.03em]">{{ $post->title }}</h2>
                    <p class="mt-3 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ \Illuminate\Support\Str::limit($post->excerpt, 120, ' ...') }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-16">
        {{ $posts->links() }}
    </div>

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
