<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function handle(Request $request) {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $user->tokens()->delete();

            return response()->json(['message' => 'Berhasil Logout'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ada kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}