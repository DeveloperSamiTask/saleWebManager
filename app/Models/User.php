<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'usuarios';
    protected $primaryKey = 'idusuario';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario',
        'clave',
        'idrol',
        'companies',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'clave',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }

    public static function list()
    {
        $users = self::with('rol')->get();

        // Cargamos todas las empresas una vez
        $allCompanies = Companies::pluck('name', 'id')->toArray(); // [1 => 'Empresa A', 2 => 'Empresa B', ...]

        $data = [];

        foreach ($users as $row) {
            // Obtener IDs como array
            $companyIds = explode(',', $row->companies);

            // Obtener los nombres desde el array cacheado
            $companyNames = array_map(function ($id) use ($allCompanies) {
                return $allCompanies[$id] ?? 'Desconocido';
            }, $companyIds);

            $data[] = [
                'id' => $row->idusuario,
                'name' => $row->usuario,
                'idrol' => $row->idrol,
                'rol' => optional($row->rol)->rol,
                'companies' => implode(', ', $companyNames),
                'idcompanies' => $row->companies,
                'status' => $row->estado,
            ];
        }

        return $data;
    }
}
