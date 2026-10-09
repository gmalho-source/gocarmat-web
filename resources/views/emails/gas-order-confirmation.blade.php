<x-mail::message marca="QSCMC">
Olá, {{ $order->name }}. Recebemos o seu pedido de entrega de gás com sucesso. Brevemente entraremos em contacto para confirmar o dia e hora de entrega do seu pedido.

## Os dados do seu pedido

- **Nome:** {{ $order->name }}
@if ($order->company)
- **Empresa:** {{ $order->company }}
@endif
- **E-mail:** {{ $order->email }}
- **Telefone:** {{ $order->phone }}
- **Opção pretendida:** {{ $order->bilha }}
- **Janela horária para entrega:** {{ $order->janela_entrega }}

O pagamento é feito apenas no ato da entrega, por MB WAY ou Multibanco.

Se precisar de falar connosco entretanto:

- **Telefone:** 214 419 243 / 214 416 266
- **E-mail:** qscmcgasoeiras@gocarmat.pt

Até breve!
**Equipa QSCMC**
</x-mail::message>
