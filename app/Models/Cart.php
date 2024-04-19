<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'intCartId';
    protected $fillable = ['invoice'];
    public $timestamps = false;

    public function detCart()
    {
        return $this->hasMany(DetCart::class, 'intCartId');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'intClienteId');
    }


    public static function getTableEntries($startDate, $endDate)
    {
        // Incluir el filtro de fechas si se proporcionan $startDate y $endDate
        $query = Cart::query();
        if ($startDate && $endDate) {
            $query->whereBetween('dateCartFreg', [$startDate, $endDate]);
        }

        // Obtener los resultados ordenados por fecha de carrito
        $cart = $query->orderByDesc('dateCartFreg')->get();

        // Procesar los resultados y construir el arreglo de datos
        $data = [];
        foreach ($cart as $row) {
            $data[] = [
                'id' => $row->intCartId,
                'code' => $row->varCartCreserva,
                'buyer' => $row->varCartTitulo,
                'quantity' => $row->intCartCant,
                'dinner' => $row->decCartTotal,
                'purchase' => $row->dateCartFreg,
                'invoice' => $row->invoice,
                // Aquí estás accediendo a la relación detCart usando optional() para manejar casos en los que detCart es null
                'status' => $row->intCartSt,
            ];
        }

        return $data;
    }
    public static function findTicketById($id)
    {
        $cart = self::find($id);
        if (!$cart) {
            return null; // El combo no fue encontrado
        }

        return [
            'id' => $cart->intCartId,
            'client' => $cart->varCartTitulo,
            'type_doc' => $cart->intCartTdoc,
            'dni' => optional($cart->client)->charClienteDni,
            'mail' => optional($cart->client)->sClieMail,
            'ruc' => $cart->charCartRuc,
            'rs' => $cart->varCartRsocial,
            'address' => $cart->varCartDirec,
            'subtotal' => $cart->decCartStotal,
            'igv' => $cart->decCartIgv,
            'total' => $cart->decCartTotal,
            'code' => $cart->varCartCreserva,
            'purchase' => $cart->dateCartFreg,
            'invoice' => $cart->invoice,
        ];
    }

    public static function chartEntries($startDate, $endDate)
    {
        $query = Cart::query();

        if ($startDate && $endDate) {
            $query->whereBetween('dateCartFreg', [$startDate, $endDate]);
        }

        $carts = $query->orderBy('dateCartFreg')->get();

        // Procesar los resultados y construir el arreglo de datos
        $data = $carts->groupBy(function ($cart) {
            return Carbon::parse($cart->dateCartFreg)->format('Y-m-d');
        })->map(function ($group) {
            // Inicializar contadores para los valores de shiftCart
            $shiftCartCount1 = 0;
            $shiftCartCount2 = 0;

            // Contar los valores de shiftCart
            foreach ($group as $item) {
                if ($item->shiftCart == '1') {
                    $shiftCartCount1++;
                } elseif ($item->shiftCart == '2') {
                    $shiftCartCount2++;
                }
            }

            return [
                'total_quantity' => $group->sum('intCartCant'),
                'total_dinner' => $group->sum('decCartTotal'),
                'shift_cart_count_1' => $shiftCartCount1,
                'shift_cart_count_2' => $shiftCartCount2,
            ];
        });

        return $data;
    }
}
