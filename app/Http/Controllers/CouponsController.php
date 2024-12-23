<?php

namespace App\Http\Controllers;

use App\Models\InternalCoupons;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $data['title'] = "Cupones Internos";
        return view('coupons.index', $data);
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

        $data = InternalCoupons::getTableLogs($startDate, $endDate);
        return response()->json(['data' => $data]);
    }

    public function insert(Request $request)
    {
        try {
            $coupon = new InternalCoupons();
            $coupon->description = $request->input('description');
            $coupon->names = $request->input('names');
            $coupon->document = $request->input('document');
            $coupon->user_send =  session('user')['idusuario'];
            $coupon->status = 1;
            $coupon->date_use = $request->input('date_use');
            $coupon->save();

            return response()->json(['icon' => 'success', 'message' => 'Cupón insertado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['icon' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function upload()
    {
        try {
            $file = request()->file('file');
            $path = '/home/ep3s6easy863/web.lagranjavilla.com/files/';

            // Verifica que el directorio exista, si no, lo crea
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // Generar un nuevo nombre único para la imagen
            $newFileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Mover el archivo al directorio y renombrarlo
            $file->move($path, $newFileName);

            // Buscar el cupón en la base de datos
            $coupon = InternalCoupons::find(request()->input('id'));
            if ($coupon) {
                // Eliminar la imagen anterior si existe
                if ($coupon->file && file_exists($path . $coupon->file)) {
                    unlink($path . $coupon->file);
                }

                // Guardar el nuevo nombre del archivo en el cupón
                $coupon->file = $newFileName;
                $coupon->status = 2;
                $coupon->save();

                return response()->json(['icon' => 'success', 'message' => 'Archivo subido correctamente']);
            } else {
                // Eliminar el archivo subido si el cupón no existe
                unlink($path . $newFileName);
                return response()->json(['icon' => 'error', 'message' => 'Cupón no encontrado']);
            }
        } catch (\Exception $e) {
            return response()->json(['icon' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
