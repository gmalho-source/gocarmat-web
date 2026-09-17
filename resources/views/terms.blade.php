@extends('layouts.site')

@section('title', 'Termos e Condições — GOCARMAT')

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">
    <article class="mx-auto mt-10 max-w-[860px]">
        <h1 class="text-4xl font-bold leading-[1.15] tracking-[-0.03em] sm:text-5xl">Termos e Condições</h1>

        {{-- Embed do Iubenda: não remover/alterar as classes, são usadas pelo script deles. --}}
        <p class="mt-10">
            <a href="https://www.iubenda.com/termos-e-condicoes/35917140" class="iubenda-white iubenda-noiframe iubenda-embed iub-body-embed" title="Termos e Condições">Termos e Condições</a>
        </p>
    </article>
    <div class="h-24 xl:h-[128px]"></div>
</div>

<script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
@endsection
