<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetCart extends Model
{
    use HasFactory;

    protected $table = 'cartdet';
    protected $primaryKey = 'intCartdetId';
    protected $fillable = ['ticketstatus', 'ticketdateuse'];
    public $timestamps = false;


    public function cart()
    {
        return $this->belongsTo(Cart::class, 'intCartId');
    }


    public static function getAllCombos()
    {
        $cartdets = self::all();
        $data = [];
        foreach ($cartdets as $cartdet) {
            $fullName = implode(' ', [$cartdet->varCartdetApepat, $cartdet->varCartdetApemat, $cartdet->varCartdetNombres]);
            $data[] = [
                'id' => $cartdet->intCartdetId,
                'document' => $cartdet->charCartdetDni,
                'name' => $fullName,
                'price' => $cartdet->decCartdetStotal,
                'purchase' => optional($cartdet->cart)->dateCartFreg, // Ajuste aquí
                'income' => $cartdet->dateCartdetFreg,
                'sure' => $cartdet->varCartdetseguro,
            ];
        }

        return $data;
    }

    public static function findTicketById($id)
    {
        $cartdet = self::find($id);
        if (!$cartdet) {
            return null; // El combo no fue encontrado
        }

        $fullName = implode(' ', [$cartdet->varCartdetApepat, $cartdet->varCartdetApemat, $cartdet->varCartdetNombres]);

        return [
            'id' => $cartdet->intCartdetId,
            'document' => $cartdet->charCartdetDni,
            'name' => $fullName,
            'price' => $cartdet->decCartdetStotal,
            'shift' => $cartdet->shiftCart,
            'device' => $cartdet->deviceCart,
            'purchase' => optional($cartdet->cart)->dateCartFreg,
            'income' => $cartdet->dateCartdetFreg,
            'sure' => $cartdet->varCartdetseguro,
            'status' => $cartdet->ticketstatus,
            'used' => $cartdet->ticketdateuse,
        ];
    }
}
