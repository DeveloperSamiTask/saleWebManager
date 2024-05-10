<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use DateTime;

use App\Models\DetCart;
use App\Models\LogDNI;
use App\Models\Notification;


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

    public function changeDNI(Request $request)
    {
        try {
            $user = session('user')['idusuario'];
            $detcart = $request->input("appID");
            $names = $request->input("appINames");
            $dniBefore = $request->input("appDNIBefore");
            $dniAfter = $request->input("appDNI");

            $logDNI = new LogDNI();
            $logDNI->detcart_id = $detcart;
            $logDNI->names_ticket = $names;
            $logDNI->user_send = $user;
            $logDNI->dni_before = $dniBefore;
            $logDNI->dni_after = $dniAfter;
            $logDNI->status_change = 0;
            $logDNI->user_acepted = null;

            $logDNI->save();

            Notification::newNotify($logDNI->id_logdni, 'Nueva Solicitud 🪪', 'Cambiar Doc. Entrada: ' . $detcart, '1');


            return response()->json(['message' => 'Solicitud de cambio de DNI enviado', 'icon' => 'success']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error al enviar la solicitud de cambio de DNI', 'icon' => 'error']);
        }
    }

    public function update(Request $request)
    {
        $log = LogDNI::find($request->input('id'));
        $log->status_change = 1;
        $log->user_acepted = session('user')['idusuario'];
        $log->save();

        $entrie = DetCart::find($request->input('detcart'));
        $entrie->charCartdetDni = $request->input('document');
        $entrie->save();


        Notification::newNotify($log->id_logdni, 'Solicitud Aceptada 🪪', 'Aceptado cambio de Doc. ' . $request->input('detcart'), '2');

        return response()->json(['message' => 'Se cambio DNI', 'icon' => 'success']);
    }
}
