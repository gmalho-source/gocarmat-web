<x-mail::message>
# Nova marcação recebida no site

**Nome:** {{ $booking->name }}
@if ($booking->company)
**Empresa:** {{ $booking->company }}
@endif
**E-mail:** {{ $booking->email }}
**Telefone:** {{ $booking->phone }}
**Serviço:** {{ $booking->service }}
@if ($booking->notes)

**Notas:**
{{ $booking->notes }}
@endif

**Newsletter:** {{ $booking->newsletter_opt_in ? 'Sim, aceitou subscrever' : 'Não subscreveu' }}
**Recebida a:** {{ $booking->created_at->format('d/m/Y H:i') }}

<x-mail::button :url="url('/admin/bookings/'.$booking->id)">
Ver no backoffice
</x-mail::button>

GOCARMAT — gocarmat.pt
</x-mail::message>
