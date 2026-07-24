@extends('layouts.site')

@section('title', 'Blog — GOCARMAT · Dicas e novidades sobre o seu automóvel')
@section('meta_description', 'Artigos, dicas de manutenção e novidades do mundo automóvel pela equipa GOCARMAT: travões, pneus, revisões, elétricos e muito mais.')

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    <div class="mt-10 flex flex-wrap items-center justify-between gap-6">
        <h1 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
            Gocarmat Blog
        </h1>
        <form method="GET" action="{{ route('blog.index') }}" class="flex w-full max-w-[420px] items-center gap-2 rounded-full border-2 border-carbono bg-white py-1 pl-6 pr-1 focus-within:border-energia">
            <x-ui.icon name="search" class="size-5 shrink-0 text-carbono/60" />
            <input type="search" name="q" value="{{ $search ?? '' }}" placeholder="Pesquisar artigos..."
                   class="w-full bg-transparent py-2.5 text-[15px] tracking-[-0.15px] outline-none placeholder:text-carbono/40">
            <button type="submit" class="shrink-0 rounded-full bg-energia px-6 py-2.5 text-[14px] font-semibold text-precision transition hover:opacity-85">
                Pesquisar
            </button>
        </form>
    </div>

    @if (($search ?? '') !== '')
        <p class="mt-8 text-base font-light leading-[1.68] tracking-[-0.16px]">
            {{ $posts->total() }} {{ $posts->total() === 1 ? 'resultado' : 'resultados' }} para
            <strong class="font-semibold">&ldquo;{{ $search }}&rdquo;</strong>
            &middot; <a href="{{ route('blog.index') }}" class="text-energia underline hover:no-underline">limpar pesquisa</a>
        </p>
        @if ($posts->isEmpty())
            <div class="mt-10 rounded-2xl bg-white px-10 py-12">
                <p class="text-2xl font-bold tracking-[-0.03em]">Sem resultados.</p>
                <p class="mt-2 text-base font-light leading-[1.68]">Tente outra palavra — por exemplo &ldquo;pneus&rdquo;, &ldquo;travões&rdquo; ou &ldquo;revisão&rdquo;.</p>
            </div>
        @endif
    @endif

    @if (($search ?? '') === '' && $posts->onFirstPage() && $posts->isNotEmpty())
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
        @foreach ($posts->skip((($search ?? '') === '' && $posts->onFirstPage()) ? 1 : 0) as $post)
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
