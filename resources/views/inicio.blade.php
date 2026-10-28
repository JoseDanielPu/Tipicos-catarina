@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Últimas Ventas</h3>
        <a href="#" class="btn btn-primary">Generar reporte</a>
    </div>

    <div class="row g-3 mb-5">
        @forelse ($ultimasVentas as $venta)
            <div class="col-md-4 col-lg">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ $venta->cliente->nombre_cliente }}</h6>
                        <div class="small text-muted">No. Guía: {{ $venta->envio->no_guia ?? 'Pendiente' }}</div>
                        <div class="mt-2">Total: <strong>Q{{ number_format($venta->total, 2) }}</strong></div>
                        @php
                            $colorPago = match ($venta->tipoPago->nombre) {
                                'contra_entrega'  => '#198754', // verde
                                'transferencia'   => '#6f42c1', // morado
                                'previo_deposito' => '#fd7e14', // naranja
                                'efectivo'        => '#cd03ff', // rosado
                                default          => '#6c757d', // gris
                                };
                        @endphp
                        <span class="badge" style="background-color: {{ $colorPago }}; color: white;">
                            {{ $venta->tipoPago->nombre }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Todavía no hay ventas registradas.</p>
        @endforelse
    </div>

    <h3 class="mb-3">Colores más vendidos <small class="text-muted fs-6">(este mes)</small></h3>
    <form method="GET" action="{{ url('/') }}" class="mb-3">
        <select name="producto_id" class="form-select w-auto" onchange="this.form.submit()">
            <option value="">Todos los productos</option>
            @foreach ($productos as $p)
                <option value="{{ $p->id }}" @selected(request('producto_id') == $p->id)>
                    {{ $p->nombre_producto }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="card">
        <div class="card-body">
            @if ($grafica)
                {!! $grafica->container() !!}
            @else
                <p class="text-muted mb-0">No hay ventas de este producto este mes.</p>
            @endif
        </div>
    </div>

    @if ($grafica)
        <script src="{{ $grafica->cdn() }}"></script>
        {{ $grafica->script() }}
    @endif

@endsection