{{-- Barra superior mínima da landing Repsol Gás: logo (texto, sem ficheiro de
     marca — QSCMC não tem logótipo, é só "QSCMC" em JetBrains Mono) +
     contactos da QSCMC, sem menu. --}}
<div class="flex flex-wrap items-center justify-between gap-y-4 py-4 sm:py-6">
    <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-3 transition hover:opacity-80">
        <span class="font-mono text-4xl font-extrabold uppercase leading-none tracking-[-0.03em] text-carbono sm:text-5xl 2xl:text-[53px]">QSCMC</span>
        <span class="font-mono text-[10px] font-extrabold uppercase leading-[1.4] tracking-[0.3px] text-carbono/70">
            Empresa do<br>Grupo GOCARMAT
        </span>
    </a>

    <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
        @if (filled($data['telefones'] ?? null))
            <div class="flex items-center gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center bg-carbono">
                    <x-ui.icon name="phone" class="size-4 text-lima" />
                </span>
                <div class="flex flex-col leading-tight">
                    @foreach ($data['telefones'] as $telefone)
                        <a href="tel:{{ preg_replace('/\s+/', '', $telefone) }}" class="whitespace-nowrap text-sm font-bold tracking-[-0.16px] text-energia hover:underline sm:text-base">{{ $telefone }}</a>
                    @endforeach
                </div>
            </div>
        @endif

        @if (filled($data['email'] ?? null))
            <a href="mailto:{{ $data['email'] }}" class="flex items-center gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center bg-carbono">
                    <x-ui.icon name="envelope" class="size-4 text-lima" />
                </span>
                <span class="break-all text-sm font-bold uppercase tracking-[-0.16px] text-energia hover:underline">{{ $data['email'] }}</span>
            </a>
        @endif
    </div>
</div>
