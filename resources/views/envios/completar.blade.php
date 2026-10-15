@extends('layouts.app')

@section('titulo', 'Completar Envío')

@section('contenido')
    <h3 class="mb-4">Datos de la Venta</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    /*esta es la parde de los datos */

    <div class="card mb-3">
        <div class="card-body">
            <h5>Datos de la Venta y Envío</h5>
            <div class="row g-2">
                <div class="col-md-6"><strong>Cliente:</strong> {{ $envio->venta->cliente->nombre_cliente }}</div>
                <div class="col-md-6"><strong>Teléfono:</strong> {{ $envio->venta->cliente->telefono }}</div>
                <div class="col-md-6"><strong>Departamento:</strong> {{ $envio->venta->cliente->direccion->departamento ?? '—' }}</div>
                <div class="col-md-6"><strong>Municipio:</strong> {{ $envio->venta->cliente->direccion->municipio ?? '—' }}</div>
                <div class="col-md-12"><strong>Dirección exacta:</strong> {{ $envio->venta->cliente->direccion->referencia ?? '—' }}</div>
                <div class="col-md-6"><strong>Tipo de pago:</strong> {{ ucfirst(str_replace('_', ' ', $envio->venta->tipoPago->nombre)) }}</div>
                <div class="col-md-6"><strong>Total:</strong> Q{{ number_format($envio->venta->total, 2) }}</div>
            </div>

            <table class="table table-sm mt-3 mb-0">
                <thead>
                    <tr><th>Producto</th><th>Color</th><th>Cantidad</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach ($envio->venta->detalleVentas as $linea)
                        <tr>
                            <td>{{ $linea->producto->nombre_producto }}</td>
                            <td>{{ $linea->color }}</td>
                            <td>{{ $linea->cantidad }}</td>
                            <td>Q{{ number_format($linea->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form action="{{ route('envios.guardar', $envio) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-body">
                <h5>Agregar No. Guía y fecha de envío</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Número de Guía</label>
                        <input type="text" name="no_guia" class="form-control"
                               value="{{ old('no_guia', $envio->no_guia) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de envío</label>
                        <input type="date" name="fecha" class="form-control"
                               value="{{ old('fecha', $envio->fecha ?: now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('ventas.index') }}" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
@endsection