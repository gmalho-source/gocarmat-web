<x-mail::message>
# Novo pedido de gás recebido no site

**Nome:** {{ $order->name }}
@if ($order->company)
**Empresa:** {{ $order->company }}
@endif
**E-mail:** {{ $order->email }}
**Telefone:** {{ $order->phone }}
**Bilha:** {{ $order->bilha }}
**Janela de entrega:** {{ $order->janela_entrega }}

**Recebido a:** {{ $order->created_at->format('d/m/Y H:i') }}

<x-mail::button :url="url('/admin/gas-orders/'.$order->id)">
Ver no backoffice
</x-mail::button>

GOCARMAT — gocarmat.pt
</x-mail::message>
