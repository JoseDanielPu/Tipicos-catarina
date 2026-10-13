<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoPago extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
