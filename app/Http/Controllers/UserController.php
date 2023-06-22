<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateMoreInfo(Request $request)
    {
        $request->validate([
            'more_info' => 'required|string',  
        ]);

        $user = auth()->user();
        $user->more_info = $request->input('more_info');
        $user->save();

        return response()->json('Información actualizada con éxito');
    }
}
