<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        h2 { font-size: 13px; margin: 18px 0 6px; }
        .periodo { color: #666; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background: #e9ecef; }
        .der { text-align: right; }
    </style>
</head>
<body>
    <h1>Típicos Catarina — Reporte de ventas</h1>
    <div class="periodo">Periodo: {{ $desde->format('d/m/Y') }} al {{ $hasta->format('d/m/Y') }}</div>

    <h2>Listado de ventas</h2>
    <table>
        <thead>
            <tr><th>Fecha</th><th>Cliente</th><th>Departamento</th><th>No. Guía</th><th>Tipo de pago</th><th class="der">Total</th></tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $venta->cliente->nombre_cliente }}</td>
                    <td>{{ $venta->cliente->direccion->departamento ?? '—'}}</td>
                    <td>{{ $venta->envio->no_guia ?? '—' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $venta->tipoPago->nombre)) }}</td>
                    <td class="end">Q{{ number_format($venta->total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No hay ventas en este periodo.</td></tr>
            @endforelse
        </tbody>
        @if ($ventas->isNotEmpty())
            <tfoot>
                <tr>
                    <th colspan="5" class="der">Total del periodo</th>
                    <th class="der">Q{{ number_format($totalGeneral, 2) }}</th>
                </tr>
            </tfoot>
        @endif
    </table>

    <h2>Colores más vendidos</h2>
    <table style="width: 50%">
        <thead><tr><th>Color</th><th class="der">Unidades</th></tr></thead>
        <tbody>
            @forelse ($colores as $c)
                <tr>
                    <td>{{ \Illuminate\Support\Str::ucfirst($c->color) }}</td>
                    <td class="der">{{ (int) $c->total }}</td>
                </tr>
            @empty
                <tr><td colspan="2">Sin datos en este periodo.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>