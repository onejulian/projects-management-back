<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUserbyEmail(string $email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'El usuario no se encuentra.'
            ], 400);
        }
        return response()->json($user, 200);
    }
}
