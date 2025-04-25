<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Box;

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

        if (!password_verify($password, $user->clave)) {
            return response()->json(['icon' => 'error', 'message' => 'Contraseña incorrecta']);
        }

        Auth::loginUsingId($user->idusuario);
        session(['user' => $user]);

        // Obtener roles y empresas
        $userCompanies = explode(',', $user->companies); // ejemplo: "1,3" → [1, 3]
        $idRol = $user->idrol;

        // Redirección personalizada
        if ($idRol == 2 && in_array(3, $userCompanies)) { // si es cajero y empresa 3
            $redirectUrl = route('cupon.validate');
        } else {
            $redirectUrl = route('Dashboard');
        }

        return response()->json([
            'icon' => 'success',
            'message' => 'Inicio de sesión exitoso',
            'redirect_url' => $redirectUrl
        ]);
    }

    public function logout(Request $request)
    {
        $boxValue = session('box');
        Box::where('name_box', $boxValue)->update(['cashier_box' => '']);
        Auth::logout();
        session::forget(['user', 'box']);
        return response()->json(['status' => '200', 'box' => $boxValue]);
    }
}
