{{-- Mapa com as 4 oficinas assinaladas. Leaflet + OpenStreetMap: sem necessidade de chave de API. --}}
@php
    $oficinas = \App\Models\Office::active()->whereNotNull('latitude')->whereNotNull('longitude')->get();
    $idMapa = 'mapa-oficinas-'.uniqid();
    $marcadores = $oficinas->map(function ($oficina) {
        return [
            'nome' => $oficina->name,
            'endereco' => $oficina->address_line1.', '.$oficina->address_line2,
            'lat' => $oficina->latitude,
            'lng' => $oficina->longitude,
            'googleMapsUrl' => filled($oficina->maps_url)
                ? $oficina->maps_url
                : 'https://www.google.com/maps/search/?api=1&query='.$oficina->latitude.','.$oficina->longitude,
        ];
    })->values();
@endphp

@if ($oficinas->isNotEmpty())
    <section class="mt-20 xl:mt-[110px]">
        @if (filled($data['titulo'] ?? null))
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">{{ $data['titulo'] }}</h2>
        @endif

        <div id="{{ $idMapa }}" class="mt-12 h-[420px] w-full xl:h-[500px]"></div>
    </section>

    @once
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endonce

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var elemento = document.getElementById('{{ $idMapa }}');
            if (!elemento || typeof L === 'undefined') return;

            var oficinas = @json($marcadores);

            var mapa = L.map(elemento.id);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(mapa);

            var marcadores = [];
            var marcadorInicial = null;
            oficinas.forEach(function (oficina) {
                var marcador = L.marker([oficina.lat, oficina.lng])
                    .addTo(mapa)
                    .bindPopup(
                        '<strong>' + oficina.nome + '</strong><br>' + oficina.endereco +
                        '<br><a href="' + oficina.googleMapsUrl + '" target="_blank" rel="noopener">Ver no Google Maps</a>'
                    );
                marcadores.push(marcador);
                if (oficina.nome === 'Adroana') {
                    marcadorInicial = marcador;
                }
            });

            if (marcadores.length > 1) {
                mapa.fitBounds(L.featureGroup(marcadores).getBounds(), { padding: [40, 40] });
            } else if (marcadores.length === 1) {
                mapa.setView([oficinas[0].lat, oficinas[0].lng], 14);
            }

            if (marcadorInicial) {
                marcadorInicial.openPopup();
            }
        });
    </script>
@endif
