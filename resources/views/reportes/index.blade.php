@extends('layouts.app')

@section('titulo', 'Reportes')

@section('contenido')
    <h3 class="mb-4">Generar reporte</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('reportes.index') }}" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control"
                   value="{{ request('desde', now()->startOfMonth()->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control"
                   value="{{ request('hasta', now()->format('Y-m-d')) }}" required>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Ver reporte</button>
            @if ($datos)
                <a href="{{ route('reportes.pdf', ['desde' => request('desde'), 'hasta' => request('hasta')]) }}"
                   target="_blank" class="btn btn-danger">Exportar PDF</a>
            @endif
        </div>
    </form>

    @if ($datos)
        <h5>Ventas del periodo ({{ $datos['desde']->format('d/m/Y') }} al {{ $datos['hasta']->format('d/m/Y') }})</h5>
        <table class="table table-bordered bg-white mb-4">
            <thead>
                <tr><th>Fecha</th><th>Cliente</th><th>Departamento</th><th>No. Guía</th><th>Tipo de pago</th><th class="text-end">Total</th></tr>
            </thead>
            <tbody>
                @forelse ($datos['ventas'] as $venta)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $venta->cliente->nombre_cliente }}</td>
                        <td>{{ $venta->cliente->direccion->departamento ?? '—'}}</td>
                        <td>{{ $venta->envio->no_guia ?? '—' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $venta->tipoPago->nombre)) }}</td>
                        <td class="text-end">Q{{ number_format($venta->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No hay ventas en este periodo.</td></tr>
                @endforelse
            </tbody>
            @if ($datos['ventas']->isNotEmpty())
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total del periodo</th>
                        <th class="text-end">Q{{ number_format($datos['totalGeneral'], 2) }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>

        <h5>Colores más vendidos</h5>
        <table class="table table-bordered bg-white" style="max-width: 400px">
            <thead><tr><th>Color</th><th class="text-end">Unidades</th></tr></thead>
            <tbody>
                @forelse ($datos['colores'] as $c)
                    <tr>
                        <td>{{ \Illuminate\Support\Str::ucfirst($c->color) }}</td>
                        <td class="text-end">{{ (int) $c->total }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-muted">Sin datos en este periodo.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
@endsection