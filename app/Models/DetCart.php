<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetCart extends Model
{
    use HasFactory;

    protected $table = 'cartdet';
    protected $primaryKey = 'intCartdetId';
    protected $fillable = ['charCartdetDni', 'ticketstatus', 'ticketdateuse', 'cashier', 'box'];
    public $timestamps = false;


    public function cart()
    {
        return $this->belongsTo(Cart::class, 'intCartId');
    }
    public function ticket()
    {
        return $this->belongsTo(Entries::class, 'intBoletoId');
    }
    public function  user()
    {
        return $this->belongsTo(User::class, 'cashier');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_cClieCode', 'cClieCode');
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

    public static function findEntriesById($id)
    {
        $entries = self::where('intCartId', $id)->get();

        if ($entries->isEmpty()) {
            return null;
        }

        $data = [];
        foreach ($entries as $entry) {
            $fullName = implode(' ', [$entry->varCartdetApepat, $entry->varCartdetApemat, $entry->varCartdetNombres]);

            $data[] = [
                'id' => $entry->intCartdetId,
                'document' => $entry->charCartdetDni,
                'name' => $fullName,
                'price' => $entry->decCartdetStotal,
                'entrie' => optional($entry->ticket)->varBoletoTitulo,
                'shift' => $entry->shiftCart,
                'device' => $entry->deviceCart,
                'income' => $entry->dateCartdetFreg,
                'sure' => $entry->varCartdetseguro,
                'status' => $entry->ticketstatus,
                'used' => $entry->ticketdateuse,
                'invoice' => $entry->invoice,
            ];
        }

        return $data;
    }

    public static function getTableEntries($startDate, $endDate, $column)
    {
        $query = self::with(['cart', 'client']);

        if ($column === 'entrance') {
            if ($startDate && $endDate) {
                $query->whereBetween('dateCartdetFreg', [$startDate, $endDate]);
            }
        } elseif ($column === 'shop') {
            if ($startDate && $endDate) {
                $query->whereHas('cart', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('dateCartFreg', [$startDate, $endDate]);
                });
            }
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
                'entrie' => $cartDet->intBoletoId,
                'device' => $cartDet->deviceCart,
                'code' => optional($cartDet->cart)->varCartCreserva,
                'purchase' => optional($cartDet->cart)->dateCartFreg,
                'income' => $cartDet->dateCartdetFreg,
                'sure' => $cartDet->varCartdetseguro,
                'status' => $cartDet->ticketstatus,
                'used' => $cartDet->ticketdateuse,
                'coupon' => optional($cartDet->cart)->coupon,
                'dni' => optional($cartDet->client)->charClienteDni, // Aquí obtenemos el campo charClientdni
                'phone' => optional($cartDet->client)->sClieTelf,
                'email' => optional($cartDet->client)->sClieMail,
                'type_doc' => optional($cartDet->cart)->type_doc,
                'number_doc' => optional($cartDet->cart)->number_doc,
            ];
        }
        return $data;
    }
    public static function dashboardEntries($startDate, $endDate)
    {
        $query = self::with('cart');
        if ($startDate && $endDate) {
            $query->whereHas('cart', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('ticketdateuse', [$startDate, $endDate]);
            });
        }
        $cartdets = $query->orderByDesc('ticketdateuse')->get();
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
                'income' => $cartDet->dateCartdetFreg,
                'sure' => $cartDet->varCartdetseguro,
                'status' => $cartDet->ticketstatus,
                'used' => $cartDet->ticketdateuse,
                'cashier' => optional($cartDet->user)->usuario,
                'box' => $cartDet->box,
            ];
        }
        return $data;
    }

    public static function countTicketsForToday()
    {
        // Obtener la fecha actual
        $today = now()->format('Y-m-d');

        // Obtener todos los combos para la fecha actual
        $combosForToday = self::whereDate('dateCartdetFreg', $today)->get(['ticketstatus']);

        // Contar el número total de combos, combos activos e inactivos
        $totalCount = $combosForToday->count();
        $activeCount = $combosForToday->where('ticketstatus', 1)->count();
        $inactiveCount = $combosForToday->where('ticketstatus', 0)->count();

        return [
            'total' => $totalCount,
            'active' => $activeCount,
            'inactive' => $inactiveCount,
        ];
    }
}
