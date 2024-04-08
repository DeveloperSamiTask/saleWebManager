<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DetCart;
use DateTime;

class TableEntries extends Controller
{
    public function index()
    {
        $data['title'] = "Reporte Entradas Webs";
        return view('reports.entries_validate', $data);
    }

    public function tableEntries(Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date') && $request->input('isChecked') == '0') {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $column = "shop";
        } else if ($request->filled('start_date') && $request->filled('end_date') && $request->input('isChecked') == '1') {
            $startDateFormatted  = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDateFormatted  = Carbon::parse($request->input('end_date'))->endOfDay();
            
            $startDate  = $startDateFormatted->toDateString();
            $endDate  = $endDateFormatted ->toDateString();
            $column = "entrance";
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
            $column = "shop";
        }

        $data = DetCart::getTableEntries($startDate, $endDate, $column);
        return response()->json(['data' => $data]);
    }
}
