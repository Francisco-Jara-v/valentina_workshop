<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
        'unidad_medida',
        'stock_minimo',
        'precio_unitario',
    ];

    public function stockBajo(): bool
    {
        return $this->cantidad <= $this->stock_minimo;
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
