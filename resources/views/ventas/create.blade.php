@extends('layouts.app')

@section('titulo', 'Nueva Venta')

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

    <h3 class="mb-4">Registrar Nueva Venta</h3>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf

        <!-- Datos de venta y envío -->
        <div class="caja">

            <h5>Datos de la Venta y Envío</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre del cliente</label>
                    <input type="text" name="nombre_cliente" class="form-control"
                           value="{{ old('nombre_cliente') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo de Pago</label>
                    <select name="tipo_pago_id" class="form-select" required>
                        <option value="" disabled selected>Seleccione el Tipo de Pago</option>
                        @foreach ($tiposPago as $tipo)
                            <option value="{{ $tipo->id }}">
                                {{ ucfirst(str_replace('_', ' ', $tipo->nombre)) }}
                            </option>
                        @endforeach
                    </select>
                </div> 

                <div class="col-md-6">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="departamento" class="form-control"
                           value="{{ old('departamento') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipio" class="form-control"
                           value="{{ old('municipio') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección exacta</label>
                    <input type="text" name="referencia" class="form-control"
                        value="{{ old('referencia') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Notas</label>
                    <textarea name="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
                </div>

            </div>
        </div>


        <!-- Productos -->
        <div class="caja">

            <h5>Productos</h5>

            <div id="lineas-productos"></div>

            <button type="button" id="btn-agregar-producto"
                    class="btn btn-outline-secondary btn-sm">
                + Agregar Nuevo Producto
            </button>

            <div class="text-end mt-3">
                <strong>Total + Envío: Q. <span id="total-venta">0.00</span></strong>
            </div>

        </div>


        <!-- Botones -->
        <div class="text-end">
            <a href="{{ route('ventas.index') }}" class="btn btn-danger">
                Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </div>

    </form>
</div>


<!-- Plantilla de producto -->
<template id="plantilla-linea">

    <div class="row g-2 align-items-end mb-3 linea-producto">

        <div class="col-md-3">
            <label class="form-label">Producto</label>
            <select name="productos[__i__][producto_id]" class="form-select" required>
                <option value="" disabled selected>Seleccione</option>

                @foreach ($productos as $producto)
                    <option value="{{ $producto->id }}">
                        {{ $producto->nombre_producto }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Color</label>
            <input type="text" name="productos[__i__][color]" class="form-control"
                list="lista-colores" autocomplete="off" required>
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
            <input type="text" name="productos[__i__][detalle_producto]"
                   class="form-control">
        </div>

        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-quitar-linea">
                ×
            </button>
        </div>

    </div>

</template>

<datalist id="lista-colores">
    @foreach ($colores as $color)
        <option value="{{ \Illuminate\Support\Str::ucfirst($color) }}">
    @endforeach
</datalist>


<script>
let contador = 0;
const contenedor = document.getElementById('lineas-productos');
const plantilla = document.getElementById('plantilla-linea').innerHTML;

function agregarLinea() {
    contenedor.insertAdjacentHTML(
        'beforeend',
        plantilla.replaceAll('__i__', contador++)
    );
    calcularTotal();
}

function calcularTotal() {
    let total = 0;

    document.querySelectorAll('.linea-producto').forEach(linea => {
        let cantidad = parseFloat(linea.querySelector('.cantidad').value) || 0;
        let precio = parseFloat(linea.querySelector('.precio').value) || 0;
        total += cantidad * precio;
    });

    document.getElementById('total-venta').textContent = total.toFixed(2);
}

document.getElementById('btn-agregar-producto')
    .addEventListener('click', agregarLinea);

contenedor.addEventListener('input', calcularTotal);

contenedor.addEventListener('click', e => {
    if (e.target.classList.contains('btn-quitar-linea')) {
        e.target.closest('.linea-producto').remove();
        calcularTotal();
    }
});

agregarLinea();
</script>

@endsection