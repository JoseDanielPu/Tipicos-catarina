<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $datos = $request->filled(['desde', 'hasta']) ? $this->datos($request) : null;

        return view('reportes.index', compact('datos'));
    }

    public function pdf(Request $request)
    {
        $datos = $this->datos($request);

        return Pdf::loadView('reportes.pdf', $datos)
            ->setPaper('letter')
            ->stream("reporte_{$request->desde}_{$request->hasta}.pdf");
    }

    private function datos(Request $request): array
    {
        $request->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
        ]);

        $inicio = Carbon::parse($request->desde)->startOfDay();
        $fin = Carbon::parse($request->hasta)->endOfDay();

        $ventas = Venta::with(['cliente.direccion', 'tipoPago', 'envio'])
            ->whereBetween('fecha', [$inicio, $fin])
            ->whereHas('envio', fn ($e) => $e->where('estado', 'Entregado'))
            ->orderBy('fecha')
            ->get();

        $colores = DetalleVenta::select(
                DB::raw('LOWER(TRIM(color)) as color'),
                DB::raw('SUM(cantidad) as total')
            )
            ->whereHas('venta', fn ($q) => $q
                ->whereBetween('fecha', [$inicio, $fin])
                ->whereHas('envio', fn ($e) => $e->where('estado', 'Entregado')))
            ->groupBy(DB::raw('LOWER(TRIM(color))'))
            ->orderByDesc('total')
            ->get();

        return [
            'ventas' => $ventas,
            'colores' => $colores,
            'desde' => $inicio,
            'hasta' => $fin,
            'totalGeneral' => $ventas->sum('total'),
        ];
    }
}
