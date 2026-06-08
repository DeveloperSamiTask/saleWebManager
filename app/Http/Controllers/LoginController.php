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

        $companiesArray = explode(',', $user->companies);

        session([
            'user' => $user,
            'companies' => $user->companies,
            'companies_array' => $companiesArray
        ]);

        $redirectRoute = $this->getRedirectRoute($user);

        return response()->json([
            'icon' => 'success',
            'message' => 'Inicio de sesión exitoso',
            'redirect_url' => route($redirectRoute)
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

    private function getRedirectRoute($user)
    {
        $userCompanies = array_map('intval', explode(',', $user->companies));
        $idRol = $user->idrol;

        // Mapa de redirección: [idEmpresa => [idRol => 'nombre_de_ruta']]
        // Ejemplo:
        // Empresa 2:
        //    - Rol 1 → Boleteria
        //    - Rol 2 → cupon.validate

        $redirectMap = [
            1 => [
                1 => 'Dashboard',
                2 => 'Boleteria',
                3 => 'Dashboard',
            ],
            2 => [
                1 => 'Boleteria',
                2 => 'cupon.validate',
                7 => 'coupon.index',
            ],
            3 => [
                2 => 'cupon.validate',
            ],
        ];

        $default = 'Dashboard';

        foreach ($userCompanies as $companyId) {
            if (isset($redirectMap[$companyId][$idRol])) {
                return $redirectMap[$companyId][$idRol];
            }
        }

        return $default;
    }
}
