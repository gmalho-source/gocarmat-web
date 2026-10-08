@props(['url', 'marca' => null])
{{--
    Imagem por URL, não em base64: o Gmail (sobretudo a app mobile) não
    renderiza data URIs de forma fiável em imagens de email, mesmo quando
    o resto do cliente as suporta. /images/ é servido publicamente mesmo
    com o staging protegido por password (só as rotas da aplicação ficam
    atrás da autenticação).

    Com $marca definida (ex: QSCMC, que não tem logótipo), mostra só o nome
    em texto em vez do logótipo GOCARMAT.
--}}
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
@if ($marca)
<span style="font-family: 'Courier New', monospace; font-size: 30px; font-weight: 800; letter-spacing: -1px; color: #ffffff;">{{ $marca }}</span>
@else
<img src="{{ asset('images/logo-email-white-small.png') }}" class="logo" alt="GOCARMAT">
@endif
</a>
</td>
</tr>
