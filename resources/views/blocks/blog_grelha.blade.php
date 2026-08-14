{{-- Grelha assimétrica de artigos da Home (destaque + 2 horizontais + 1 vertical) --}}
@php
    $posts = \App\Models\Post::published()->orderByDesc('published_at')->take(4)->get();
@endphp

<section class="mt-24 xl:mt-[128px]">
    <div class="flex flex-wrap items-center justify-between gap-6">
        @if (filled($data['titulo'] ?? null))
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                {{ $data['titulo'] }}
            </h2>
        @endif
        <x-pill variant="outline-dark" :href="route('blog.index')" mono>{{ $data['botao_texto'] ?? 'Ver todos os Artigos' }}</x-pill>
    </div>

    @if ($posts->isNotEmpty())
        <div class="mt-14 grid gap-8 xl:grid-cols-12">
            @php $featured = $posts->first(); @endphp
            <a href="{{ route('blog.show', $featured->slug) }}" class="group xl:col-span-5">
                @if ($featured->featured_image)
                    <div class="h-[384px] overflow-hidden">
                        <img src="{{ asset('storage/'.$featured->featured_image) }}" alt="{{ $featured->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105">
                    </div>
                @endif
                <div class="bg-white px-10 py-9">
                    <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $featured->published_at?->translatedFormat('j F, Y') }}</p>
                    <h3 class="mt-3 text-[28px] font-bold leading-[1.2] tracking-[-0.03em] xl:text-[32px]">{{ $featured->title }}</h3>
                    <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ \Illuminate\Support\Str::limit($featured->excerpt, 140, ' ...') }}</p>
                </div>
            </a>

            <div class="grid gap-8 xl:col-span-4">
                @foreach ($posts->slice(1, 2) as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group grid grid-cols-[minmax(0,42%)_minmax(0,58%)] overflow-hidden">
                        @if ($post->featured_image)
                            <div class="overflow-hidden">
                                <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" class="size-full min-h-[220px] object-cover transition duration-300 group-hover:scale-105">
                            </div>
                        @endif
                        <div class="bg-white px-7 py-8">
                            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $post->published_at?->translatedFormat('j F, Y') }}</p>
                            <h3 class="mt-3 text-2xl font-bold leading-[1.2] tracking-[-0.03em] xl:text-[26px]">{{ $post->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>

            @php $last = $posts->slice(3, 1)->first(); @endphp
            @if ($last)
                <a href="{{ route('blog.show', $last->slug) }}" class="group xl:col-span-3">
                    @if ($last->featured_image)
                        <div class="h-[288px] overflow-hidden">
                            <img src="{{ asset('storage/'.$last->featured_image) }}" alt="{{ $last->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105">
                        </div>
                    @endif
                    <div class="bg-white px-8 py-8">
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $last->published_at?->translatedFormat('j F, Y') }}</p>
                        <h3 class="mt-3 text-[28px] font-bold leading-[1.2] tracking-[-0.03em]">{{ $last->title }}</h3>
                        <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ \Illuminate\Support\Str::limit($last->excerpt, 200, ' ...') }}</p>
                    </div>
                </a>
            @endif
        </div>
    @endif
</section>
