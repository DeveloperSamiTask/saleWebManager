<?php

namespace App\Http\Controllers;

use App\Helpers\ReniecHelper;
use App\Models\Companies;
use App\Models\Coupons;
use App\Models\Promotions;
use App\Models\Templates;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Psr\Http\Message\RequestInterface;
use Carbon\Carbon;

class CouponManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = "Cupones";
        $data['companies'] = Companies::all();
        return view('coupon.index', $data);
    }

    public function getPromotions(Request $request)
    {
        $company_id = $request
            ->company_id;
        $promotions = Promotions::getPromotions($company_id);
        return response()->json($promotions);
    }

    public function show(Request $request)
    {
        $startDate = $request->get('startDate', '2025-03-01');
        $endDate = $request->get('endDate', '2025-03-31');

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $coupons = Coupons::getCoupons($startDate->toDateTimeString(), $endDate->toDateTimeString());

        return response()->json([
            'data' => $coupons,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $dateCode = Carbon::now()->format('dmY');

            $countToday = Coupons::whereDate('created_at', Carbon::today())->count();

            $nextNumber = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

            $couponCode = "CI-{$dateCode}-{$nextNumber}";

            $coupon = new Coupons();
            $coupon->code = $couponCode;
            $coupon->company_id = $request->company;
            $coupon->promotion_id = $request->promotion;
            $coupon->expired_date = Carbon::createFromFormat('d-m-Y', $request->expired_date)->format('Y-m-d');
            $coupon->type_doc = $request->type_doc;
            $coupon->number_doc = $request->doc;
            $coupon->father_surname = $request->pattername;
            $coupon->mother_surname = $request->mattername;
            $coupon->names = $request->names;
            $coupon->phone = $request->phone;
            $coupon->email = $request->mail;
            $coupon->status = 0;

            if ($request->hasFile('formFile')) {
                // Define una ruta personalizada para guardar la imagen
                $imagePath = '/home/ep3s6easy863/web.lagranjavilla.com/coupons';

                // Genera el nombre del archivo de la imagen
                $filename = time() . '.' . $request->formFile->getClientOriginalExtension();

                // Guarda la imagen en la ruta personalizada
                $request->formFile->move($imagePath, $filename);

                // Actualiza el campo de la imagen en el modelo con la ruta relativa a la ubicación del servidor
                $coupon->img = 'coupons/' . $filename;
            }

            $coupon->save();

            return response()->json(['icon' => 'success', 'message' => 'Cupón guardado correctamente', 'code' => $couponCode]);
        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'message' => 'Error al guardar el cupón',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function viewValidate()
    {
        $data['title'] = "Validar Cupón";
        return view('coupon.validate', $data);
    }

    public function search($code)
    {
        $coupon = Coupons::with('promotion')->where('code', $code)->first();

        if (!$coupon) {
            return $this->response('error', 'Cupón no encontrado', 404);
        }

        return match (true) {
            $coupon->status == 1 => $this->response(
                'warning',
                'Este cupón ya ha sido utilizado el ' . optional($coupon->used_date)->format('d/m/Y H:i:s')
            ),
            $coupon->status == 3 => $this->response(
                'warning',
                'Este cupón está inhabilitado'
            ),
            $coupon->expired_date < now()->toDateString() => $this->response(
                'warning',
                'Este cupón expiró el ' . \Carbon\Carbon::parse($coupon->expired_date)->format('d/m/Y')
            ),
            default => $this->response('success', 'Cupón encontrado', 200, $coupon),
        };
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $coupon = Coupons::find($request->id);
        if ($coupon) {
            $coupon->status = $request->status;
            $coupon->save();
            return response()->json(['icon' => 'success', 'message' => 'Estado actualizado correctamente']);
        } else {
            return response()->json(['icon' => 'error', 'message' => 'Cupón no encontrado'], 404);
        }
    }


    public function generatePdf($code)
    {
        // Buscar el cupón en la base de datos
        $client = Coupons::where('code', $code)->first();

        $template = Templates::where('company_id', $client->company_id)
            ->where('promotion_id', $client->promotion_id)
            ->first();

        $content = $template->content;

        // Ruta absoluta del archivo en el servidor
        $imagePath = url('public/'.$client->img);

        // Verificar si la imagen existe
        if (!file_exists($imagePath)) {
            abort(404, "Imagen no encontrada.");
        }
        // Generar la imagen del código de barras y convertirla a Base64
        $barcodeUrl = "http://generator.barcodetools.com/barcode.png?gen=0&data=" . $client->code .
            "&bcolor=FFFFFF&fcolor=000000&tcolor=000000&fh=14&bred=0&w2n=2.5&xdim=2&w=70px&h=220px&debug=1&btype=7&angle=90&quiet=1&balign=2&talign=0&guarg=1&text=1&tdown=1&stst=1&schk=0&cchk=1&ntxt=1&c128=0";

        // Obtener la imagen del código de barras y convertirla a Base64
        $barcodeData = base64_encode(file_get_contents($barcodeUrl));
        $barcodeBase64 = 'data:image/png;base64,' . $barcodeData;

        // Generar el PDF con la vista
        $pdf = Pdf::loadView('pdf.coupon', compact('client', 'imagePath', 'barcodeBase64', 'content'));

        // Mostrar el PDF en el navegador sin descargarlo
        return $pdf->stream($client->code . '.pdf');
    }

    public function searchDNI(Request $request)
    {
        $dni = $request->dni;
        $response = ReniecHelper::consultarDni($dni);
        return response()->json($response);
    }

    public function generateCode($number)
    {
        $ci = "CI"; // Prefijo fijo
        $date = Carbon::now()->format('dmY'); // Formato "ddmmaa"
        $formattedNumber = str_pad($number, 3, '0', STR_PAD_LEFT); // Asegura que el número tenga al menos 3 dígitos

        return "{$ci}-{$date}-{$formattedNumber}";
    }
    /**
     * Genera una respuesta JSON estructurada.
     */
    private function response($icon, $message, $status = 400, $data = null)
    {
        return response()->json([
            'icon' => $icon,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
