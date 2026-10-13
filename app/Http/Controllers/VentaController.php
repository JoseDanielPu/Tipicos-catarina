<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Envio;
use App\Models\Producto;
use App\Models\TipoPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ventas = Venta::with(['cliente', 'tipoPago', 'envio'])
            ->when($request->buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->whereHas('cliente', fn ($c) => $c->where('nombre_cliente', 'like', "%{$buscar}%"))
                    ->orWhereHas('envio', fn ($e) => $e->where('no_guia', 'like', "%{$buscar}%"));
                });
            })
            ->when($request->tipo_pago_id, fn ($query, $id) => $query->where('tipo_pago_id', $id))
            ->latest()
            ->get();

        $tiposPago = TipoPago::orderBy('nombre')->get();

        return view('ventas.index', compact('ventas', 'tiposPago'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::orderBy('nombre_producto')->get();
        $tiposPago = TipoPago::orderBy('nombre')->get();
        return view('ventas.create', compact('productos', 'tiposPago'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->reglas());

        DB::transaction(function () use ($request){
            $cliente = Cliente::create([
                'nombre_cliente' => $request->nombre_cliente,
                'telefono' =>$request->telefono,
            ]);
            $cliente->direccion()->create([
                'departamento' => $request->departamento,
                'municipio' => $request->municipio,
                'referencia' => $request->referencia,
            ]);
            $venta = Venta::create([
                'cliente_id' => $cliente->id,
                'tipo_pago_id' => $request->tipo_pago_id,
                'fecha'=> now(),
                'notas' => $request->notas,
                'total' => 0.
            ]);

            $total = 0;
            foreach ($request->productos as $linea) {
                $subtotal = $linea['cantidad'] * $linea['precio'];
                $total += $subtotal;
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $linea['producto_id'],
                    'cantidad' => $linea['cantidad'],
                    'color' => $linea['color'] ?? null,
                    'precio' => $linea['precio'],
                    'subtotal' => $subtotal,
                    'detalle_producto' => $linea['detalle_producto'] ?? null,
                ]);
            }
            $venta->update(['total' => $total]);

            Envio::create([
                'venta_id' => $venta->id,
                'estado' => 'Pendiente',
            ]);
        });
        return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente.direccion', 'tipoPago', 'detalleVentas.producto', 'envio']);

        return view('ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        $venta->load(['cliente.direccion', 'detalleVentas']);

        $productos = producto::orderby('nombre_producto')->get();
        $tiposPago = TipoPago::orderby('nombre')->get();

        $lineas = $venta->detalleVentas->map(fn ($l) => [
            'producto_id' => $l->producto_id,
            'cantidad' => $l->cantidad,
            'color' => $l->color,
            'precio' => $l->precio,
            'detalle_producto' => $l->detalle_producto,
        ])->values();

        return view('ventas.edit', compact('venta', 'productos', 'tiposPago', 'lineas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        $request->validate(array_merge($this->reglas(), ['fecha' => 'required|date']));

        DB::transaction(function () use ($request, $venta) {
            $venta->cliente->update([
                'nombre_cliente' => $request->nombre_cliente,
                'telefono' => $request->telefono,
            ]);

            $venta->cliente->direccion()->updateOrCreate([], [
                'referencia' => $request->referencia,
                'departamento' => $request->departamento,
                'municipio' => $request->municipio,
            ]);

            // Se reemplazan las líneas: es más simple que comparar una por una
            $venta->detalleVentas()->delete();

            $total = 0;

            foreach ($request->productos as $linea) {
                $subtotal = $linea['cantidad'] * $linea['precio'];
                $total += $subtotal;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $linea['producto_id'],
                    'cantidad' => $linea['cantidad'],
                    'color' => $linea['color'],
                    'precio' => $linea['precio'],
                    'subtotal' => $subtotal,
                    'detalle_producto' => $linea['detalle_producto'] ?? null,
                ]);
            }

            $venta->update([
                'tipo_pago_id' => $request->tipo_pago_id,
                'fecha' => $request->fecha,
                'notas' => $request->notas,
                'total' => $total,
            ]);
        });

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        DB::transaction(function () use ($venta) {
            $venta->detalleVentas()->delete();
            $venta->envio()->delete();
            $venta->delete();
        });

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }

    private function reglas(): array
    {
        return [
            'nombre_cliente' => 'required|string|max:150',
            'telefono' => 'required|string|max:20',
            'departamento' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'referencia' => 'required|string|max:255',
            'tipo_pago_id' => 'required|exists:tipo_pagos,id',
            'notas' => 'nullable|string|max:255',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.color' => 'required|string|max:50',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.detalle_producto' => 'nullable|string|max:255',
        ];
    }
}

