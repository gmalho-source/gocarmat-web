@props(['url'])
@php
    // Logo embutido em base64 para o email mostrar sempre a imagem correta,
    // independentemente do ambiente (localhost, staging protegido por
    // password, etc.) conseguir servir um URL público ou não.
    $logoPath = public_path('images/logo-email-white-small.png');
    $logoBase64 = is_readable($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if ($logoBase64)
<img src="data:image/png;base64,{{ $logoBase64 }}" class="logo" alt="GOCARMAT">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
