<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'tipo_pago_id', 'fecha', 'notas', 'total'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoPago()
    {
        return $this->belongsTo(TipoPago::class);
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function envio()
    {
        return $this->hasOne(Envio::class);
    }
}