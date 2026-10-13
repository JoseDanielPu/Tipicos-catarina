@extends('layouts.app')

@section('titulo', 'Productos')

@section('contenido')
    <h3 class="mb-4">Agregar Nuevo Producto</h3>

    <div class="row">
        <div class="col-md-7">
            <h5>Productos Existentes</h5>
            <table class="table table-bordered bg-white">
                <tbody>
                    @forelse ($productos as $producto)
                        <tr>
                            <td class="align-middle">{{ $producto->nombre_producto }}</td>
                            <td class="text-end" style="width: 100px">
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar"
                                        data-form-action="{{ route('productos.destroy', $producto) }}">
                                    Borrar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-muted">No hay productos registrados todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="col-md-5">
            <h5>Nombre del Nuevo Producto</h5>
            <form action="{{ route('productos.store') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="nombre_producto" class="form-control" placeholder="Ej. Cortes" required>
                <button type="submit" class="btn btn-primary">Crear</button>
            </form>
            @error('nombre_producto')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">

                <!-- Header centrado vertical y horizontalmente -->
                <div class="modal-header flex-column align-items-center position-relative pb-0 border-0">

                    <i class="bi bi-exclamation-triangle display-4 mb-2"></i>
                    <h5 class="modal-title fw-bold">Alerta</h5>                    
                </div>

                <!-- Cuerpo con el mensaje -->
                <div class="modal-body fs-5 py-3">
                    ¿Desea eliminar este producto?
                </div>

                <!-- Botones centrados -->
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">No</button>
                    <form id="formEliminar" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Sí</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        document.getElementById('modalEliminar').addEventListener('show.bs.modal', function (event) {
            const boton = event.relatedTarget;
            const url = boton.getAttribute('data-form-action');
            document.getElementById('formEliminar').setAttribute('action', url);
        });
    </script>
@endsection