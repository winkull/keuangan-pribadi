<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $username = $request->username;
            $password = $request->password;

            if (Auth::attempt(['username' => $username, 'password' => $password], true)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Username or Password is wrong'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
