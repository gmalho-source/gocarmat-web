<div class="mt-14 grid gap-6 sm:grid-cols-2 xl:grid-cols-4 xl:gap-10">
    @foreach ($offices as $office)
        <div class="relative bg-white pb-12 pl-12 pr-6 pt-10">
            <div class="absolute right-0 top-0 flex size-[75px] items-center justify-center bg-cloud text-energia">
                <x-ui.icon name="location-dot" class="size-10" />
            </div>
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $office->schedule }}</p>
            <h3 class="mt-3 font-mono text-[32px] font-bold leading-[1.2] tracking-[-0.03em]">{{ $office->name }}</h3>
            <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px]">
                {{ $office->address_line1 }}<br>{{ $office->address_line2 }}
            </p>
            <div class="mt-8 flex items-start gap-3">
                <x-ui.icon name="phone" class="mt-0.5 size-5 shrink-0 text-energia" />
                <div>
                    <p class="text-lg font-bold leading-[1.2] tracking-[-0.54px]">{{ $office->phones }}</p>
                    <p class="mt-1 text-[15px] font-light leading-[1.45] tracking-[-0.15px]">{{ $office->phone_note }}</p>
                </div>
            </div>
            <div class="mt-5 flex items-center gap-3">
                <x-ui.icon name="envelope" class="size-5 shrink-0 text-energia" />
                <a href="mailto:{{ $office->email }}" class="break-all text-lg font-bold leading-[1.2] tracking-[-0.54px] hover:text-energia">{{ $office->email }}</a>
            </div>
        </div>
    @endforeach
</div>
