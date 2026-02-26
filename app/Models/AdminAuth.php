<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class AdminAuth extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';
    protected $primaryKey = 'cedula_usuario';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cedula_usuario',
        'nombre_usuario',
        'email_usuario',
        'contrasena',
        'sexo',
        'numero_telefono_usuario',
        'estado',
    ];

    protected $hidden = ['contrasena'];
}
