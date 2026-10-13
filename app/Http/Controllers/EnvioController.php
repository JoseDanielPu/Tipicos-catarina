<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Envio $envio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Envio $envio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Envio $envio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Envio $envio)
    {
        //
    }

    public function completar(Envio $envio)
    {
        $envio->load(['venta.cliente.direccion', 'venta.tipoPago', 'venta.detalleVentas.producto']);

        return view('envios.completar', compact('envio'));
    }

    public function guardar(Request $request, Envio $envio)
    {
        $request->validate([
            'no_guia' => 'required|string|max:50',
            'fecha' => 'required|date',
        ]);

        $envio->update($request->only('no_guia', 'fecha'));

        return redirect()->route('ventas.index')->with('success', 'Datos de envío guardados correctamente.');
    }
}
