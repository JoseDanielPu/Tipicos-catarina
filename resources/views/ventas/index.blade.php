@extends('layouts.app')

@section('titulo', 'Ventas')


@section('contenido')
    <h3 class="mb-4">Ventas</h3>

    <form method="GET" action="{{ route('ventas.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por nombre o No. Guía" value="{{ request('buscar') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>

        <div class="col-md-3">
            <select name="tipo_pago_id" class="form-select">
                <option value="">Filtrar por tipo de pago</option>
                @foreach ($tiposPago as $tipo)
                    <option value="{{ $tipo->id }}" @selected(request('tipo_pago_id') == $tipo->id)>
                        {{ ucfirst(str_replace('_', ' ', $tipo->nombre)) }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <table class="table table-bordered bg-white align-middle">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>No. Guía</th>
                <th>Total</th>
                <th>Tipo de pago</th>
                <th style="width: 300px">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $venta->cliente->nombre_cliente }}</td>
                    <td>{{ $venta->envio->no_guia ?? '—' }}</td>
                    <td>Q{{ number_format($venta->total, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $venta->tipoPago->nombre)) }}</td>
                    <td>
                        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-sm btn-primary">Editar</a>
                        @if ($venta->envio)
                            <a href="{{ route('envios.completar', $venta->envio) }}" class="btn btn-sm btn-info">Completar</a>
                        @endif
                        <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-warning">Ver</a>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminar"
                                data-form-action="{{ route('ventas.destroy', $venta) }}">
                            Borrar
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No se encontraron ventas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">¿Desea eliminar esta venta?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <form id="formEliminar" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sí</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('modalEliminar').addEventListener('show.bs.modal', function (event) {
            document.getElementById('formEliminar')
                .setAttribute('action', event.relatedTarget.getAttribute('data-form-action'));
        });
    </script>
@endsection