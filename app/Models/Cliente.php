<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable =['nombre_cliente', 'telefono'];
    public function direccion()
    {
        return $this->hasOne(Direccion::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
