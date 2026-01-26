<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pedido;
use App\Models\Insumo;
use Filament\Notifications\Notification;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $fillable = [
        'pedido_id',
        'insumo_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    protected static function booted()
    {
        static::created(function ($detalle) {

            $insumo = Insumo::find($detalle->insumo_id);

            if (! $insumo) {
                return;
            }

            // Descontar stock
            $insumo->cantidad -= $detalle->cantidad;
            $insumo->save();

            // ⚠️ Stock bajo
            if ($insumo->cantidad <= $insumo->stock_minimo) {
                Notification::make()
                    ->title('⚠️ Stock bajo')
                    ->body("El insumo {$insumo->nombre} tiene solo {$insumo->cantidad} unidades.")
                    ->danger()
                    ->send();
            }
        });
    }
}
