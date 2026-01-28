<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['nombre', 'descripcion'];

    public function notas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Nota::class);
    }
}
