<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente'; // ojo: singular
    protected $primaryKey = 'cedula_cliente';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // por si no tienes created_at/updated_at

    protected $fillable = [
        'cedula_cliente',
        'nombre_cliente',
        'email_cliente',
        'sexo_cliente',
        'direccion_cliente',
        'numero_telefono_cliente',
    ];
}
