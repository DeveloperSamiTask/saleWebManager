<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;

use App\Models\LogDNI;


class DniController extends Controller
{
    public function index()
    {
        $data['title'] = "Rectificación de DNI";
        return view('tickets.dni', $data);
    }
    public function logsTable(Request $request)
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

        $data = LogDNI::getTableLogs($startDate, $endDate);
        return response()->json(['data' => $data]);
    }
}
