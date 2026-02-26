<?php

namespace App\Http\Controllers;

use App\Models\AdminAuth;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $v->errors(),
            ], 422);
        }

        // 1) Buscar usuario por email
        $user = AdminAuth::where('email_usuario', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // 2) Verificar password (PLANO por ahora)
        // Si luego está hasheada, cambiamos a: Hash::check($request->password, $user->contrasena)
        if ($request->password !== $user->contrasena) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // 3) Verificar que sea admin
        $isAdmin = Administrador::where('cedula_usuario', $user->cedula_usuario)->exists();
        if (!$isAdmin) {
            return response()->json(['message' => 'No autorizado (no es administrador)'], 403);
        }

        // 4) Crear token
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login correcto',
            'token' => $token,
            'user' => [
                'cedula_usuario' => $user->cedula_usuario,
                'nombre_usuario' => $user->nombre_usuario,
                'email_usuario' => $user->email_usuario,
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logout correcto']);
    }
}
