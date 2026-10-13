@extends('layouts.app')

@section('titulo', 'Editar Venta')

@section('contenido')

<style>
    .venta { max-width: 1100px; margin: auto; }
    .caja {
        background: white;
        border: 1px solid #999;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-label { font-size: 14px; }
</style>

<div class="venta">

    <h3 class="mb-4">Editar Venta</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.update', $venta) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="caja">
            <h5>Datos de la Venta y Envío</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre del cliente</label>
                    <input type="text" name="nombre_cliente" class="form-control"
                           value="{{ old('nombre_cliente', $venta->cliente->nombre_cliente) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo de Pago</label>
                    <select name="tipo_pago_id" class="form-select" required>
                        @foreach ($tiposPago as $tipo)
                            <option value="{{ $tipo->id }}"
                                @selected(old('tipo_pago_id', $venta->tipo_pago_id) == $tipo->id)>
                                {{ ucfirst(str_replace('_', ' ', $tipo->nombre)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="departamento" class="form-control"
                           value="{{ old('departamento', $venta->cliente->direccion->departamento ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipio" class="form-control"
                           value="{{ old('municipio', $venta->cliente->direccion->municipio ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección exacta</label>
                    <input type="text" name="referencia" class="form-control"
                           value="{{ old('referencia', $venta->cliente->direccion->referencia ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono', $venta->cliente->telefono) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha/Hora</label>
                    <input type="datetime-local" name="fecha" class="form-control"
                           value="{{ old('fecha', \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Notas</label>
                    <textarea name="notas" class="form-control" rows="3">{{ old('notas', $venta->notas) }}</textarea>
                </div>
            </div>
        </div>

        <div class="caja">
            <h5>Productos</h5>

            <div id="lineas-productos"></div>

            <button type="button" id="btn-agregar-producto" class="btn btn-outline-secondary btn-sm">
                + Agregar Nuevo Producto
            </button>

            <div class="text-end mt-3">
                <strong>Total + Envío: Q. <span id="total-venta">0.00</span></strong>
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('ventas.index') }}" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>

<template id="plantilla-linea">
    <div class="row g-2 align-items-end mb-3 linea-producto">
        <div class="col-md-3">
            <label class="form-label">Producto</label>
            <select name="productos[__i__][producto_id]" class="form-select" required>
                <option value="" disabled selected>Seleccione</option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre_producto }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Color</label>
            <input type="text" name="productos[__i__][color]" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label class="form-label">Cantidad</label>
            <input type="number" name="productos[__i__][cantidad]"
                   class="form-control cantidad" min="1" value="1" required>
        </div>

        <div class="col-md-2">
            <label class="form-label">Precio por unidad</label>
            <input type="number" name="productos[__i__][precio]"
                   class="form-control precio" min="0" step="0.01" required>
        </div>

        <div class="col-md-2">
            <label class="form-label">Especificación</label>
            <input type="text" name="productos[__i__][detalle_producto]" class="form-control">
        </div>

        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-quitar-linea">×</button>
        </div>
    </div>
</template>

<script>
const lineasExistentes = @json($lineas);

let contador = 0;
const contenedor = document.getElementById('lineas-productos');
const plantilla = document.getElementById('plantilla-linea').innerHTML;

function agregarLinea(datos = null) {
    contenedor.insertAdjacentHTML('beforeend', plantilla.replaceAll('__i__', contador++));

    if (datos) {
        const linea = contenedor.lastElementChild;
        linea.querySelector('select').value = datos.producto_id;
        linea.querySelector('[name$="[color]"]').value = datos.color ?? '';
        linea.querySelector('.cantidad').value = datos.cantidad;
        linea.querySelector('.precio').value = datos.precio;
        linea.querySelector('[name$="[detalle_producto]"]').value = datos.detalle_producto ?? '';
    }

    calcularTotal();
}

function calcularTotal() {
    let total = 0;

    document.querySelectorAll('.linea-producto').forEach(linea => {
        const cantidad = parseFloat(linea.querySelector('.cantidad').value) || 0;
        const precio = parseFloat(linea.querySelector('.precio').value) || 0;
        total += cantidad * precio;
    });

    document.getElementById('total-venta').textContent = total.toFixed(2);
}

document.getElementById('btn-agregar-producto').addEventListener('click', () => agregarLinea());

contenedor.addEventListener('input', calcularTotal);

contenedor.addEventListener('click', e => {
    if (e.target.classList.contains('btn-quitar-linea')) {
        e.target.closest('.linea-producto').remove();
        calcularTotal();
    }
});

lineasExistentes.forEach(l => agregarLinea(l));
if (lineasExistentes.length === 0) agregarLinea();
</script>

@endsection