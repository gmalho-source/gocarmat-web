@props(['url'])
{{--
    Imagem por URL, não em base64: o Gmail (sobretudo a app mobile) não
    renderiza data URIs de forma fiável em imagens de email, mesmo quando
    o resto do cliente as suporta. /images/ é servido publicamente mesmo
    com o staging protegido por password (só as rotas da aplicação ficam
    atrás da autenticação).
--}}
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('images/logo-email-white-small.png') }}" class="logo" alt="GOCARMAT">
</a>
</td>
</tr>
