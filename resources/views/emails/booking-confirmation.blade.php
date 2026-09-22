<x-mail::message>
# Recebemos o seu pedido!

Obrigado por contactar a GOCARMAT. O seu pedido de marcação para **{{ $booking->service }}** na oficina **{{ $booking->office->name ?? '' }}** foi recebido com sucesso.

@if ($booking->notes)
**A sua mensagem:**

<x-mail::panel>
{{ $booking->notes }}
</x-mail::panel>
@endif

A nossa equipa de Apoio ao Cliente vai entrar em contacto consigo com a maior brevidade possível, através do e-mail ou do telefone que nos indicou, para confirmar a data.

Se precisar de falar connosco entretanto:

- **Apoio ao Cliente:** apoiocliente@gocarmat.pt

Até já,
**Equipa GOCARMAT**
A sua oficina multimarca · Grande Lisboa
</x-mail::message>
