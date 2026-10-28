<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_producto'];

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}