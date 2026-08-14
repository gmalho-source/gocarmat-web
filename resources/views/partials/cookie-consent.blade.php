@php
    $ga4 = \App\Models\Setting::get('ga4_id');
    $pixel = \App\Models\Setting::get('meta_pixel_id');
    $temTracking = filled($ga4) || filled($pixel);
@endphp

@if ($temTracking)
    {{-- Consentimento RGPD: nada de tracking é carregado antes do "aceitar".
         A escolha fica em localStorage; recusar não carrega nada. --}}
    <div id="cookie-banner" hidden
         class="fixed inset-x-4 bottom-4 z-50 mx-auto max-w-[880px] rounded-2xl bg-carbono px-6 py-5 shadow-2xl sm:px-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <p class="text-[15px] font-light leading-[1.6] text-gelo">
                Usamos cookies de análise para entender como o site é utilizado e melhorar a sua experiência.
                Pode aceitar ou recusar — os cookies essenciais ao funcionamento do site são sempre usados.
                <a href="{{ url('/politica-de-privacidade') }}" class="underline hover:text-lima">Saber mais</a>.
            </p>
            <div class="flex shrink-0 gap-3">
                <button type="button" data-cookie-recusar
                        class="rounded-full border-2 border-gelo px-6 py-2.5 text-[14px] font-semibold text-gelo transition hover:opacity-80">
                    Recusar
                </button>
                <button type="button" data-cookie-aceitar
                        class="rounded-full bg-lima px-7 py-2.5 text-[14px] font-semibold text-carbono transition hover:opacity-85">
                    Aceitar
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const CHAVE = 'gocarmat_cookies';
            const banner = document.getElementById('cookie-banner');
            const ga4 = @json($ga4);
            const pixel = @json($pixel);

            function carregarTracking() {
                if (ga4) {
                    const s = document.createElement('script');
                    s.async = true;
                    s.src = 'https://www.googletagmanager.com/gtag/js?id=' + ga4;
                    document.head.appendChild(s);
                    window.dataLayer = window.dataLayer || [];
                    window.gtag = function () { window.dataLayer.push(arguments); };
                    gtag('js', new Date());
                    gtag('config', ga4, { anonymize_ip: true });
                }

                if (pixel) {
                    (function (f, b, e, v, n, t, s) {
                        if (f.fbq) return; n = f.fbq = function () {
                            n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                        };
                        if (!f._fbq) f._fbq = n;
                        n.push = n; n.loaded = true; n.version = '2.0'; n.queue = [];
                        t = b.createElement(e); t.async = true; t.src = v;
                        s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s);
                    })(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
                    fbq('init', pixel);
                    fbq('track', 'PageView');
                }
            }

            const escolha = localStorage.getItem(CHAVE);

            if (escolha === 'aceito') {
                carregarTracking();
            } else if (escolha !== 'recusado') {
                banner.hidden = false;
            }

            banner.querySelector('[data-cookie-aceitar]').addEventListener('click', function () {
                localStorage.setItem(CHAVE, 'aceito');
                banner.hidden = true;
                carregarTracking();
            });

            banner.querySelector('[data-cookie-recusar]').addEventListener('click', function () {
                localStorage.setItem(CHAVE, 'recusado');
                banner.hidden = true;
            });
        })();
    </script>
@endif
