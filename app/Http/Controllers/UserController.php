<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data['title'] = "Usuarios";
        $data['companies'] = Companies::all();
        $data['roles'] = Rol::all();
        return view('user.index', $data);
    }

    public function show()
    {

        $users = User::list();
        return response()->json([
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $existingUser = User::where('usuario', $request->user_name)->first();

        if ($existingUser) {
            return response()->json([
                'status'  => false,
                'icon'    => 'warning',
                'message' => 'El nombre de usuario ya está en uso. Por favor elija otro.'
            ]);
        }

        $user = new User();
        $user->fill([
            'usuario'   => $request->user_name,
            'clave'     => Hash::make($request->password),
            'idrol'     => $request->user_role,
            'companies' => is_array($request->user_companies)
                ? implode(',', $request->user_companies)
                : $request->user_companies,
        ]);

        $saved = $user->save();

        return response()->json([
            'status'  => $saved,
            'icon'    => $saved ? 'success' : 'info',
            'message' => $saved
                ? 'Usuario creado correctamente'
                : 'No se pudieron guardar los cambios'
        ]);
    }



    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $user->fill([
            'usuario'   => $request->user_name,
            'companies' => is_array($request->user_companies)
                ? implode(',', $request->user_companies)
                : $request->user_companies,
        ]);

        if ($request->filled('password')) {
            $user->clave = Hash::make($request->password);
        }

        return response()->json([
            'status'  => $user->save(),
            'icon'    => $user->wasChanged() ? 'success' : 'info',
            'message' => $user->wasChanged()
                ? 'Usuario actualizado correctamente'
                : 'No se realizaron cambios'
        ]);
    }
}
