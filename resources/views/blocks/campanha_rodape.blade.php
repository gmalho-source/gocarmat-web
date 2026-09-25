{{-- Rodapé mínimo, usado nas landing pages de campanha (ver
     layouts/landing.blade.php — estas páginas não usam o rodapé completo do
     site). Colar sempre logo a seguir a um cta_icone com colar_ao_rodape. --}}
<div class="rounded-b-2xl bg-energia px-8 py-8 pb-10 sm:px-12 xl:px-16">
    <div class="flex flex-col items-center gap-6 sm:flex-row sm:justify-between">
        <img src="{{ asset('images/logo-white.svg') }}" alt="GOCARMAT — A sua oficina multimarca" class="h-10 w-auto shrink-0">

        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-center text-sm font-light leading-[1.68] tracking-[-0.16px] text-cristal">
            <span>Gocarmat © {{ date('Y') }} - Todos os Direitos Reservados&nbsp;&nbsp;&nbsp;/</span>
            <a href="{{ url('/politica-de-privacidade') }}" class="transition hover:text-white">Política de Privacidade</a>
            <a href="{{ url('/termos-e-condicoes') }}" class="transition hover:text-white">Termos &amp; Condições</a>
            <a href="https://www.livroreclamacoes.pt" target="_blank" rel="noopener" class="transition hover:text-white">Livro de Reclamações</a>
        </div>

        <a href="https://jelly.pt" target="_blank" rel="noopener" title="Jelly - Digital Marketing &amp; AI" class="shrink-0 transition hover:opacity-80">
            <img src="{{ asset('images/footer-badge.svg') }}" alt="Jelly digital agency logo" class="h-6 w-auto">
        </a>
    </div>
</div>
