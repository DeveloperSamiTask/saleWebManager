<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Box;
use DateTime;

class Dashboard extends Controller
{
    public function index()
    {
        $data['title'] = "Dashboard";
        return view('dashboard', $data);
    }

    public function chartEntries(Request $request)
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            // Fecha de hoy
            $endDate = new DateTime();
            $endDate->setTime(23, 59, 59);
            $endDateString = $endDate->format('Y-m-d H:i:s');

            $startDate = new DateTime();
            $startDate->modify('-7 days');
            $startDate->setTime(0, 0, 0);
            $startDateString = $startDate->format('Y-m-d H:i:s');

            $startDate = $startDateString;
            $endDate = $endDateString;
        }

        $data = Cart::chartEntries($startDate, $endDate);
        $total = $data->sum('total_quantity');
        return response()->json(['data' => $data, 'total' => $total]);
    }
    public function sessionBox(Request $request)
    {
        $idUsuario = session('user')['idusuario'];
        $box = $request->input('box');
        $request->session()->put('box', $box);
        Box::where('name_box', $box)->update(['cashier_box' => $idUsuario]);
        return response()->json($request->session()->all());
    }
}
