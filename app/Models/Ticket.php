<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'qr_cupon';
    protected $primaryKey = 'id_coupon';
    protected $fillable = ['code_coupon', 'tickets', 'date_generate', 'date_used', 'method', 'method_data', 'code'];
    public $timestamps = false;

    public static function getList($filters)
    {
        // Inicializa la consulta
        $query = self::query();

        // Aplica los filtros
        if (isset($filters['code_coupon'])) {
            $query->where('code_coupon', $filters['code_coupon']);
        }
        // Agrega más condiciones de filtrado según sea necesario

        // Obtén los registros filtrados
        $tickets = $query->get();

        // Retorna los registros
        return $tickets;
    }
}
