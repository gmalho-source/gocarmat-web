{{-- Dois painéis lado a lado (Missão / Visão) --}}
<section class="mt-6 grid overflow-hidden rounded-[32px] lg:grid-cols-2">
    <div class="bg-energia px-8 py-14 sm:px-12 xl:px-24 xl:py-20">
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-white sm:text-[52px]">{{ $data['titulo_1'] }}</h2>
        <p class="mt-6 max-w-[560px] text-lg font-light leading-[1.68] tracking-[-0.18px] text-gelo">{{ $data['texto_1'] }}</p>
    </div>
    <div class="bg-tecnico px-8 py-14 sm:px-12 xl:px-24 xl:py-20">
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-white sm:text-[52px]">{{ $data['titulo_2'] }}</h2>
        <p class="mt-6 max-w-[560px] text-lg font-light leading-[1.68] tracking-[-0.18px] text-gelo">{{ $data['texto_2'] }}</p>
    </div>
</section>
