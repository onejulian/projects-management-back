<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255', 
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|string|min:8', 
        ]);

        if ($validator->fails()) { 
            return response()->json([
                'message' => 'Error de validación.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|string|email|max:255', 
            'password' => 'required|string|min:8', 
        ]);

        if ($validator->fails()) { 
            return response()->json([
                'message' => 'Error de validación.',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente.',
        ]);
    }
}
