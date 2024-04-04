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
                'purchase' => optional($cartdet->cart)->dateCartFreg,
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

    public static function getTableEntries($startDate, $endDate)
    {
        $query = self::with('cart');

        if ($startDate && $endDate) {
            $query->whereHas('cart', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('dateCartFreg', [$startDate, $endDate]);
            });
        }

        $cartdets = $query->orderByDesc('dateCartdetFreg')->get();
        $data = [];
        foreach ($cartdets as $cartDet) {
            $fullName = implode(' ', [$cartDet->varCartdetApepat, $cartDet->varCartdetApemat, $cartDet->varCartdetNombres]);

            $data[] = [
                'id' => $cartDet->intCartdetId,
                'price' => $cartDet->decCartdetStotal,
                'shift' => $cartDet->shiftCart,
                'nameP' => $fullName,
                'name' => optional($cartDet->cart)->varCartTitulo,
                'device' => $cartDet->deviceCart,
                'code' => optional($cartDet->cart)->varCartCreserva,
                'purchase' => optional($cartDet->cart)->dateCartFreg,
                'income' => $cartDet->dateCartdetFreg,
                'sure' => $cartDet->varCartdetseguro,
                'status' => $cartDet->ticketstatus,
                'used' => $cartDet->ticketdateuse,
            ];
        }
        return $data;
    }
}
