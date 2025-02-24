<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'TARJETA';
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'cClieCode', 'cClieCode');
    }

    public static function getAllPartners($startDate, $endDate)
    {

        $query = self::with(['client']);

        $query->whereBetween('dEmisDate', [$startDate, $endDate]);
        $partners =  $query->orderByDesc('id')->get();
        $data = [];
        foreach ($partners as $partner) {

            $data[] = [
                'id' => $partner->id,
                'card' => $partner->cClieCode,
                'client' => optional($partner->client)->sClieApel . " " . optional($partner->client)->sClieName,
                'document' => optional($partner->client)->charClienteDni,
                'date_start' => $partner->dEmisDate,
                'date_end' => $partner->dCaduDate,
            ];
        }

        return $data;
    }
}
