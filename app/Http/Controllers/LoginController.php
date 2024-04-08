<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('usuario', $username)->first();

        if (!$user) {
            return response()->json(['icon' => 'error', 'message' => 'Usuario no encontrado']);
        }

        if (password_verify($password, $user->clave)) {
            Auth::loginUsingId($user->idusuario);

            session(['user' => $user]);
            return response()->json([
                'icon' => 'success',
                'message' => 'Inicio de sesión exitoso',
                'redirect_url' => route('Dashboard')
            ]);
        } else {
            return response()->json(['icon' => 'error', 'message' => 'Contraseña incorrecta']);
        }
    }
    public function logout()
    {
        Auth::logout();
        session::forget('user');
        return redirect('/')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
