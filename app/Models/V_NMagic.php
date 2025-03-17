<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_NMagic extends Model
{
    use HasFactory;

    protected $table = 'validar_tarjeta';
    protected $primaryKey = 'idvalidar_tarjeta';


    public function customer()
    {
        return $this->belongsTo(Client::class, 'client');
    }

    public function partner()
    {
        return $this->hasMany(Partner::class, 'cClieCode', 'client');
    }


    public static function show($start, $end)
    {
        $query = self::with('customer', 'partner')->whereBetween('fecha', [$start->toDateString(), $end->toDateString()])->get();

        $data = [];

        foreach ($query as $item) {
            $data[] = [
                'id' => $item->idvalidar_tarjeta,
                'card' => $item->partner->pluck('nTarjNumb')->implode(', ') ?? 'No disponible',
                'client' => $item->customer->sClieApel . ' ' . $item->customer->sClieName,
                'type' => "NUMERO MAGICO",
                'document' => $item->customer->charClienteDni,
                'birthday' => $item->customer->dNacmDate,
                'register' => optional($item->partner->first())->dEmisDate,
                'date' => $item->fecha,
                'user' => $item->usuario,
            ];
        }
        return $data;
    }
}
