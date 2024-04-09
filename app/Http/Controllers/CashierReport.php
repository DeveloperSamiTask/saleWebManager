<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use App\Models\Cart;

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

        if (!$ticket) {
            return abort(404);
        }
        $data['ticket'] = $ticket;


        return view('reports.invoice', $data);
    }
}
