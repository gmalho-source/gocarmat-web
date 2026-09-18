@php
    $ga4Id = \App\Models\Setting::get('ga4_id');
    $gtmId = \App\Models\Setting::get('gtm_id');
@endphp

@if (filled($ga4Id) || filled($gtmId))
    {{-- Google Consent Mode v2 — tem de vir antes do gtag.js/GTM e da
         Iubenda: começa tudo "negado" e a Iubenda atualiza o estado sozinha
         (googleAdditionalConsentMode, configurado abaixo) assim que o
         visitante decidir. Sem isto, o GA4/GTM nunca respeitam o consentimento. --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('consent', 'default', {
            ad_storage: 'denied',
            ad_user_data: 'denied',
            ad_personalization: 'denied',
            analytics_storage: 'denied',
            wait_for_update: 500,
        });
        gtag('set', 'ads_data_redaction', true);
        gtag('set', 'url_passthrough', true);
    </script>
@endif

@if (filled($gtmId))
    {{-- Google Tag Manager — carrega o contentor; as tags lá dentro (GA4,
         Ads, etc.) ficam a cargo da configuração feita no próprio GTM. --}}
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
            var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l !== 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $gtmId }}');
    </script>
@endif

@if (filled($ga4Id))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', '{{ $ga4Id }}');
    </script>
@endif

{{-- Iubenda — Privacy Controls / Cookie Solution.
     Gerado no painel Iubenda do cliente (Cookie Solution → Configure/Install).
     googleAdditionalConsentMode liga automaticamente o estado de consentimento
     do GA4/GTM (acima) às escolhas do visitante no banner — não precisa de
     mais nenhuma configuração de "purpose" para o Analytics funcionar. --}}
<script type="text/javascript">
var _iub = _iub || [];
_iub.csConfiguration = {"askConsentAtCookiePolicyUpdate":true,"enableTcf":true,"floatingPreferencesButtonDisplay":"bottom-right","googleAdditionalConsentMode":true,"perPurposeConsent":true,"reloadOnConsent":true,"siteId":2498738,"tcfPurposes":{"2":"consent_only","7":"consent_only","8":"consent_only","9":"consent_only","10":"consent_only"},"whitelabel":false,"cookiePolicyId":35917140,"banner":{"acceptButtonDisplay":true,"closeButtonRejects":true,"customizeButtonDisplay":true,"explicitWithdrawal":true,"fontSizeBody":"12px","listPurposes":true,"ownerName":"gocarmat","position":"bottom","rejectButtonDisplay":true,"showPurposesToggles":true,"showTotalNumberOfProviders":true}};
_iub.csLangConfiguration = {"pt":{"cookiePolicyId":35917140}};
</script>
<script type="text/javascript" src="//cs.iubenda.com/sync/2498738.js"></script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/tcf/stub-v2.js"></script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/tcf/safe-tcf-v2.js"></script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>
