<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'cedula_usuario';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; // por si tu tabla no tiene created_at/updated_at

    protected $fillable = [
        'cedula_usuario',
        'nombre_usuario',
        'email_usuario',
        'contrasena',
        'sexo',
        'numero_telefono_usuario',
        'estado',
    ];

    protected $hidden = [
        'contrasena',
    ];
}
