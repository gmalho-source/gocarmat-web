@extends('layouts.site')

@section('title', $page->metaTitle().' — GOCARMAT')
@section('meta_description', $page->metaDescription())

@push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $page->metaTitle() }}">
    @if ($page->metaDescription())
        <meta property="og:description" content="{{ $page->metaDescription() }}">
    @endif
    @if ($page->og_image)
        <meta property="og:image" content="{{ asset('storage/'.$page->og_image) }}">
    @endif
    <link rel="canonical" href="{{ url('/'.$page->slug) }}">
@endpush

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">
    @forelse ($page->content ?? [] as $bloco)
        @php
            $vista = 'blocks.'.($bloco['type'] ?? '');
            $dados = $bloco['data'] ?? [];
        @endphp

        @if (view()->exists($vista))
            @include($vista, ['data' => $dados])
        @endif
    @empty
        <section class="mt-16">
            <h1 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                {{ $page->title }}
            </h1>
            <p class="mt-6 text-base font-light leading-[1.68]">Esta página ainda não tem conteúdo.</p>
        </section>
    @endforelse

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
