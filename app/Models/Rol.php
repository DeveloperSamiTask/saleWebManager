<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'idrol';
    public $timestamps = false;
    protected $fillable = [
        'rol',
        'estado',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'idrol', 'idrol');
    }

}
