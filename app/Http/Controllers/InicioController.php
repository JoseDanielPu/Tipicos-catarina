<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Producto;


class InicioController extends Controller
{
    public function index(Request $request){
        $ultimasVentas = Venta::with(['cliente', 'tipoPago', 'envio'])
            ->latest()
            ->take(5)
            ->get();

            //los colores mas vendidos 

            $colores = DetalleVenta::select(
                DB::raw('LOWER(TRIM(color)) as color'),
                DB::raw('SUM(cantidad) as total')
            )
            ->when($request->producto_id, fn ($q, $id) => $q->where('producto_id', $id))
            ->whereHas('venta', fn ($q) => $q
                ->whereYear('fecha', now()->year)
                ->whereMonth('fecha', now()->month))
            ->groupBy(DB::raw('LOWER(TRIM(color))'))
            ->orderByDesc('total')
            ->take(8)
            ->get();

        $productos = Producto::orderBy('nombre_producto')->get();

        $nombreFiltro = $request->producto_id
            ? (Producto::find($request->producto_id)?->nombre_producto ?? 'todos los productos')
            : 'todos los productos';

        $grafica = null;

        if ($colores->isNotEmpty()) {
            $grafica = (new LarapexChart)->donutChart()
                ->setTitle('Colores más vendidos')
                ->setSubtitle("Unidades vendidas este mes · {$nombreFiltro}")
                ->addData($colores->pluck('total')->map(fn ($t) => (int) $t)->all())
                ->setLabels($colores->map(fn ($c) => \Illuminate\Support\Str::ucfirst($c->color) . ' (' . (int) $c->total . ')')->all())
                ->setColors($colores->pluck('color')->map(fn ($c) => $this->hexDeColor($c))->all());
        }

        return view('inicio', compact('ultimasVentas', 'grafica', 'productos'));
    }
    private function hexDeColor(string $nombre): string{
        $mapa = config('colores');
        $limpio = \Illuminate\Support\Str::ascii(mb_strtolower(trim($nombre)));

        if (isset($mapa[$limpio])) {
            return $mapa[$limpio];
        }

        // Nombre compuesto ("verde limón"): se usa la palabra base más larga que contenga
        $claves = array_keys($mapa);
        usort($claves, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($claves as $clave) {
            if (str_contains($limpio, $clave)) {
                return $mapa[$clave];
            }
        }

        return '#9E9E9E';
    }
}

