<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @group Autenticación
     * Registrar un nuevo usuario
     *
     * @unauthenticated
     *
     * @bodyParam name string required Nombre del usuario.
     * @bodyParam email string required Correo del usuario.
     * @bodyParam password string required Contraseña del usuario.
     *
     * @response 201 {
     *     "user": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "email": "john@example.com",
     *         "created_at": "2023-01-01T12:00:00Z",
     *         "updated_at": "2023-01-01T12:00:00Z"
     *     }
     * }
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'user' => $user
        ], 201);
    }

    /**
     * @group Autenticación
     * Autenticar un usuario registrado
     *
     * @unauthenticated
     *
     * @bodyParam email string required Correo del usuario.
     * @bodyParam password string required Contraseña del usuario.
     *
     * @response 200 {
     *     "token": "eyJ1eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImJhNz..."
     * }
     * @response 401 {
     *     "message": "Unauthorized"
     * }
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'token' => $user->createToken('auth-token')->plainTextToken
        ], 200);
    }
}
