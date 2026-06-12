<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
        'imagen',
        'categoria',
        'moneda',
        'activo',
    ];

    protected $casts = [
        'precio' => 'float',
        'activo' => 'boolean',
    ];
}
