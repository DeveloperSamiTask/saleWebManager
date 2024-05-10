<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LogDNI extends Model
{
    use HasFactory;
    protected $table = 'log_dni';
    protected $primaryKey = 'id_logdni';
    protected $fillable = ['detcart_id', 'names_ticket', 'user_send', 'dni_before', 'dni_after', 'status_change', 'user_acepted'];


    public function detCart()
    {
        return $this->belongsTo(DetCart::class, 'intCartdetId');
    }

    public function userSend()
    {
        return $this->belongsTo(User::class, 'user_send');
    }
    public function userAcepted()
    {
        return $this->belongsTo(User::class, 'user_acepted');
    }

    public static function getTableLogs($startDate, $endDate)
    {
        $query = LogDNI::query();
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $log = $query->orderByDesc('created_at')->get();

        $data = [];
        foreach ($log as $row) {
            $data[] = [
                'id' => $row->id_logdni,
                'detcart' => $row->detcart_id,
                'names' => $row->names_ticket,
                'user_send' => optional($row->userSend)->usuario,
                'dniBefore' => $row->dni_before,
                'dniAfter' => $row->dni_after,
                'status' => $row->status_change,
                'user_acepted' => optional($row->userAcepted)->usuario,
                'dateInsert' => Carbon::parse($row->created_at)->formatLocalized('%d %B %Y %H:%M:%S'), // Cambiar el formato de la fecha de inserción
                'dateAcepted' => Carbon::parse($row->updated_at)->formatLocalized('%d %B %Y %H:%M:%S'), // Cambiar el formato de la fecha de aceptación
            ];
        }

        return $data;
    }
}
