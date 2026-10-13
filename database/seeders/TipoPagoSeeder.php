<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoPago;

class TipoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = ['efectivo', 'transferencia', 'previo_deposito', 'contra_entrega'];

        foreach ($tipos as $tipo) {
            TipoPago::firstOrCreate(['nombre' => $tipo]);
        }
    }
}
