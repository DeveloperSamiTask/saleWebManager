<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;

use App\Models\Cart;
use App\Models\DetCart;

class CashierReport extends Controller
{
    public function index()
    {
        $data['title'] = "Reporte Entradas Webs";
        return view('reports.sale_webs', $data);
    }
    public function tableCashier(Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            // Fecha de hoy
            $endDate = new DateTime();
            $endDate->setTime(23, 59, 59);
            $endDateString = $endDate->format('Y-m-d H:i:s');

            // Fecha de hace un mes
            $startDate = new DateTime();
            $startDate->modify('-1 month');
            $startDate->setTime(0, 0, 0);
            $startDateString = $startDate->format('Y-m-d H:i:s');

            $startDate = $startDateString;
            $endDate = $endDateString;
        }

        $data = Cart::getTableEntries($startDate, $endDate);
        return response()->json(['data' => $data]);
    }
    public function Invoice($id)
    {
        $data['title'] = "Reporte Venta";

        // Llama al método findTicketById() del modelo Cart
        $ticket = Cart::findTicketById($id);
        $entries = DetCart::findEntriesById($id);

        if (!$ticket) {
            return abort(404);
        }
        $data['ticket'] = $ticket;
        $data['entries'] = $entries;


        return view('reports.invoice', $data);
    }
    public function check(Request $request)
    {
        $id = intval($request->input('ticket'));
        try {
            $cart = Cart::find($id);

            // Verificar si $cart es nulo
            if (!$cart) {
                return response()->json(['message' => 'El carrito no fue encontrado.'], 404);
            }

            $cart->invoice = 1;
            $cart->save();
            return response()->json(['message' => 'Se ha validado la venta.']);
        } catch (\Exception $e) {
            Log::error('Error al validar la venta: ' . $e->getMessage());
            return response()->json(['message' => 'Ha ocurrido un error al validar la venta. Por favor, contacta al administrador.'], 500);
        }
    }
}
