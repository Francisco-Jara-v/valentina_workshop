<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_costo',
        'valor_venta',
        'stock_minimo',
        'stock_taller',
    ];

    public function insumo()
    {
        return $this->belongsToMany(Insumo::class,'producto_insumo')
                    ->withPivot('cantidad');
    }

    public function productoInsumos()
    {
        return $this->hasMany(ProductoInsumo::class);
    }

    public function inventarioTienda()
    {
        return $this->hasOne(InventarioTienda::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}
