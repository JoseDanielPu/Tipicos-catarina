<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Direccion extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'referencia', 'departamento', 'municipio'];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
