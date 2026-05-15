<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoInsumo extends Model
{
    protected $table = 'producto_insumo';

    protected $fillable = [
        'producto_id',
        'insumo_id',
        'cantidad',
        ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
