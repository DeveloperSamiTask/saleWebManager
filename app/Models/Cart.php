<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'intCartId';
    protected $fillable = ['intClienteId'];

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
            'ruc' => $cart->charCartRuc,
            'rs' => $cart->varCartRsocial,
            'address' => $cart->varCartDirec,
            'subtotal' => $cart->decCartStotal,
            'igv' => $cart->decCartIgv,
            'total' => $cart->decCartTotal,
            'code' => $cart->varCartCreserva,
            'purchase' => $cart->dateCartFreg,
        ];
    }

    public static function findEntriesById($id)
    {
        // Buscar el carrito basado en el código de compra
        $cart = Cart::where('intCartId', $id)->first();

        if (!$cart) {
            return null; // El carrito no fue encontrado
        }

        // Obtener las entradas asociadas al carrito
        $entries = $cart->detCart;

        // Procesar las entradas y construir el arreglo de datos
        $data = [];
        foreach ($entries as $entry) {
            $fullName = implode(' ', [$entry->varCartdetApepat, $entry->varCartdetApemat, $entry->varCartdetNombres]);

            $data[] = [
                'id' => $entry->intCartdetId,
                'document' => $entry->charCartdetDni,
                'name' => $fullName,
                'price' => $entry->decCartdetStotal,
                'shift' => $entry->shiftCart,
                'device' => $entry->deviceCart,
                'purchase' => optional($entry->cart)->dateCartFreg,
                'income' => $entry->dateCartdetFreg,
                'sure' => $entry->varCartdetseguro,
                'status' => $entry->ticketstatus,
                'used' => $entry->ticketdateuse,
            ];
        }

        return $data;
    }
}
