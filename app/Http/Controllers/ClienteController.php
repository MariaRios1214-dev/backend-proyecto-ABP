<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $q = Cliente::query();

        if ($search !== '') {
            $q->where('cedula_cliente', 'ilike', "%{$search}%")
                ->orWhere('nombre_cliente', 'ilike', "%{$search}%")
                ->orWhere('email_cliente', 'ilike', "%{$search}%");
        }

        // paginado simple
        $clientes = $q->orderBy('nombre_cliente')->paginate(10);

        return response()->json($clientes);
    }

    public function show(string $cedula)
    {
        $cliente = Cliente::find($cedula);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'cedula_cliente' => ['required', 'string', 'max:20'],
            'nombre_cliente' => ['required', 'string', 'max:200'],
            'email_cliente' => ['required', 'email', 'max:200'],
            'sexo_cliente' => ['nullable', 'in:M,F,O'],
            'direccion_cliente' => ['nullable', 'string', 'max:255'],
            'numero_telefono_cliente' => ['nullable', 'string', 'max:30'],
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $v->errors(),
            ], 422);
        }

        // evitar duplicado por PK
        if (Cliente::find($request->cedula_cliente)) {
            return response()->json(['message' => 'Ya existe un cliente con esa cédula'], 409);
        }

        $cliente = Cliente::create($v->validated());

        return response()->json([
            'message' => 'Cliente creado',
            'cliente' => $cliente,
        ], 201);
    }

    public function update(Request $request, string $cedula)
    {
        $cliente = Cliente::find($cedula);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $v = Validator::make($request->all(), [
            'nombre_cliente' => ['sometimes', 'required', 'string', 'max:200'],
            'email_cliente' => ['sometimes', 'required', 'email', 'max:200'],
            'sexo_cliente' => ['sometimes', 'nullable', 'string', 'max:20'],
            'direccion_cliente' => ['sometimes', 'nullable', 'string', 'max:255'],
            'numero_telefono_cliente' => ['sometimes', 'nullable', 'string', 'max:30'],
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $v->errors(),
            ], 422);
        }

        $cliente->fill($v->validated());
        $cliente->save();

        return response()->json([
            'message' => 'Cliente actualizado',
            'cliente' => $cliente,
        ]);
    }

    public function destroy(string $cedula)
    {
        $cliente = Cliente::find($cedula);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado']);
    }
}
