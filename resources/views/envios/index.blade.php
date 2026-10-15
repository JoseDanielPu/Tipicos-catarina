@extends('layouts.app')

@section('titulo', 'Envíos')

@section('contenido')
    <h3 class="mb-4">Envíos</h3>

    <form method="GET" action="{{ route('envios.index') }}" class="row g-2 mb-3">
        <div class="col-md-8">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por nombre o No. Guía" value="{{ request('buscar') }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <table class="table table-bordered bg-white align-middle">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>No. Guía</th>
                <th>Total</th>
                <th>Estado</th>
                <th style="width: 200px">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($envios as $envio)
                <tr>
                    <td>{{ $envio->venta->cliente->nombre_cliente }}</td>
                    <td>{{ $envio->no_guia }}</td>
                    <td>Q{{ number_format($envio->venta->total, 2) }}</td>
                    <td>
                        <span class="badge {{ $envio->estado === 'Entregado' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $envio->estado }}
                        </span>
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('ventas.show', $envio->venta) }}" class="btn btn-sm btn-warning">Ver</a>
                        @if ($envio->estado !== 'Entregado')
                            <form action="{{ route('envios.entregado', $envio) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">Entregado</button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminar"
                                data-form-action="{{ route('ventas.destroy', $envio->venta) }}">
                            Borrar
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No hay envíos registrados todavía.</td></tr>
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
                <div class="modal-body">
                    ¿Desea eliminar este envío? También se eliminará la venta asociada.
                </div>
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