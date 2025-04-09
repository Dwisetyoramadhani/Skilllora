<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function handle(Request $request) {
        try {
            $request->validate([
                'email'=>'required|email',
                'password'=>'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)){
                throw ValidationException::withMessages([
                    'email' => ['Email atau password yang anda masukan salah']
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}