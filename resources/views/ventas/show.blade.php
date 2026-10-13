@extends('layouts.app')

@section('titulo', 'Datos de la Venta')

@section('contenido')
    <h3 class="mb-4">Datos de la Venta</h3>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Datos de la Venta y Envío</h5>
            <div class="row g-2">
                <div class="col-md-6"><strong>Cliente:</strong> {{ $venta->cliente->nombre_cliente }}</div>
                <div class="col-md-6"><strong>Teléfono:</strong> {{ $venta->cliente->telefono }}</div>
                <div class="col-md-6"><strong>Departamento:</strong> {{ $venta->cliente->direccion->departamento ?? '—' }}</div>
                <div class="col-md-6"><strong>Municipio:</strong> {{ $venta->cliente->direccion->municipio ?? '—' }}</div>
                <div class="col-md-12"><strong>Dirección exacta:</strong> {{ $venta->cliente->direccion->referencia ?? '—' }}</div>
                <div class="col-md-6"><strong>Tipo de pago:</strong> {{ ucfirst(str_replace('_', ' ', $venta->tipoPago->nombre)) }}</div>
                <div class="col-md-6"><strong>Fecha:</strong> {{ $venta->fecha }}</div>
                <div class="col-md-6"><strong>No. Guía:</strong> {{ $venta->envio->no_guia ?? 'Pendiente' }}</div>
                <div class="col-md-6"><strong>Estado del envío:</strong> {{ $venta->envio->estado ?? '—' }}</div>
                <div class="col-md-12"><strong>Notas:</strong> {{ $venta->notas ?: '—' }}</div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Productos</h5>
            <table class="table table-sm mb-0">
                <thead>
                    <tr><th>Producto</th><th>Color</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th><th>Especificación</th></tr>
                </thead>
                <tbody>
                    @foreach ($venta->detalleVentas as $linea)
                        <tr>
                            <td>{{ $linea->producto->nombre_producto }}</td>
                            <td>{{ $linea->color }}</td>
                            <td>{{ $linea->cantidad }}</td>
                            <td>Q{{ number_format($linea->precio, 2) }}</td>
                            <td>Q{{ number_format($linea->subtotal, 2) }}</td>
                            <td>{{ $linea->detalle_producto ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end mt-2"><strong>Total: Q{{ number_format($venta->total, 2) }}</strong></div>
        </div>
    </div>

    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Regresar</a>
@endsection