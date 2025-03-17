<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_Birthday extends Model
{
    use HasFactory;

    protected $table = 'validar_cumple';
    protected $primaryKey = 'IDVALIDARCUMPL';
    public $timestamps = false;


    public function client()
    {
        return $this->belongsTo(Client::class, 'IDSOCIO');
    }

    public function partner()
    {
        return $this->hasMany(Partner::class, 'cClieCode', 'IDSOCIO');
    }

    public static function show($start, $end)
    {
        $query = self::with('client', 'partner')->whereBetween('FECHA', [$start->toDateString(), $end->toDateString()])->get();

        $data = [];

        foreach ($query as $item) {
            $data[] = [
                'id' => $item->IDVALIDARCUMPL,
                'card' => $item->partner->pluck('nTarjNumb')->implode(', ') ?? 'No disponible',
                'client' => $item->client->sClieApel . ' ' . $item->client->sClieName,
                'type' => "CUMPLEAÑOS",
                'document' => $item->client->charClienteDni,
                'birthday' => $item->client->dNacmDate,
                'register' => optional($item->partner->first())->dEmisDate,
                'date' => $item->FECHA,
                'user' => $item->USUARIO,
            ];
        }
        return $data;
    }
}
