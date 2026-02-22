<?php

namespace App\Http\Controllers;

use App\Helpers\ReniecHelper;
use App\Models\Companies;
use App\Models\Coupons;
use App\Models\Coupons\Bowling;
use App\Models\Promotions;
use App\Models\Templates;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Log;

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
        $template = $request->template;
        $promotions = Promotions::getPromotions($company_id, $template);
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
            $coupon->status = 4;
            $coupon->user_create = session('user')['idusuario'];

            if ($request->hasFile('formFile')) {
                $filename = time() . '.' . $request->formFile->getClientOriginalExtension();
                $request->formFile->storeAs('public/coupons', $filename);
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
                'Este cupón ya ha sido utilizado el ' . optional(Carbon::parse($coupon->used_date))->format('d/m/Y H:i:s')
            ),
            $coupon->status == 3 => $this->response(
                'warning',
                'Este cupón está inhabilitado'
            ),
            $coupon->status == 4 => $this->response(
                'warning',
                'Este cupón está pendiente de validación'
            ),
            $coupon->expired_date < now()->toDateString() => $this->response(
                'warning',
                'Este cupón expiró el ' . \Carbon\Carbon::parse($coupon->expired_date)->format('d/m/Y')
            ),
            default => $this->response('success', 'Cupón encontrado', 200, $coupon),
        };
    }

    public function validateCoupon(Request $request)
    {

        $coupon = Coupons::where('code', $request->code)->first();

        $coupon->status = 1;
        $coupon->user_validate = session('user')['idusuario'];

        $coupon->used_date = now();

        $coupon->save();

        return response()->json(['icon' => 'success', 'message' => 'Cupón validado correctamente']);
    }

    public function searchCB($code)
    {

        $coupon = Bowling::where('int_retoque', $code)->first();

        if (!$coupon) {
            return $this->response('error', 'Cupón no encontrado', 404);
        }

        return match (true) {
            $coupon->int_stado == 1 => $this->response(
                'warning',
                'Este cupón ya ha sido utilizado el ' . optional(Carbon::parse($coupon->txt_foto))->format('d/m/Y H:i:s')
            ),
            default => $this->response('success', 'Cupón encontrado', 200, $coupon),
        };
    }

    public function validateCB(Request $request)
    {
        $coupon = Bowling::where('int_retoque', $request->code)->first();

        Log::info('Validating coupon with code: ', [$coupon]);

        if (!$coupon) {
            return $this->response(['icon' => 'error', 'message' => 'Cupón no encontrado'], 404);
        }

        // ✅ Validar si ya está usado
        if ($coupon->int_stado == 1) {
            return response()->json([
                'icon' => 'warning',
                'message' => 'Este cupón ya fue validado el ' . optional(Carbon::parse($coupon->txt_foto))->format('d/m/Y H:i:s')
            ], 400);
        }

        // ✅ Marcar como validado
        $coupon->int_stado = 1;
        $coupon->txt_foto = now();
        $coupon->save();

        return response()->json(['icon' => 'success', 'message' => 'Cupón validado correctamente']);
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

        if (!$client) {
            abort(404, "Cupón no encontrado.");
        }

        $template = Templates::where('company_id', $client->company_id)
            ->where('promotion_id', $client->promotion_id)
            ->first();

        $content = $template->content ?? '';

        // Ruta absoluta del archivo en el servidor
        $imagePath = public_path('storage/' . $client->img);

        // Verificar si la imagen existe
        if (!file_exists($imagePath)) {
            abort(404, "Imagen no encontrada.");
        }

        // Generar la imagen del código de barras
        $barcodeBase64 = $this->getBarcodeImage($client->code);

        // Generar el PDF con la vista
        $pdf = Pdf::loadView('pdf.coupon', compact('client', 'imagePath', 'barcodeBase64', 'content'));

        // Mostrar el PDF en el navegador sin descargarlo
        return $pdf->stream($client->code . '.pdf');
    }

    private function getBarcodeImage($code)
    {
        $barcodeUrl = "http://generator.barcodetools.com/barcode.png?gen=0&data=" . urlencode($code) .
            "&bcolor=FFFFFF&fcolor=000000&tcolor=000000&fh=14&bred=0&w2n=2.5&xdim=2&w=70px&h=220px&debug=1&btype=7&angle=90&quiet=1&balign=2&talign=0&guarg=1&text=1&tdown=1&stst=1&schk=0&cchk=1&ntxt=1&c128=0";
        // ☝️ Cambié angle=90 a angle=0

        // Intentar con cURL
        if (function_exists('curl_init')) {
            try {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $barcodeUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ]);

                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);

                if ($result && $httpCode == 200) {
                    // Rotar la imagen 90 grados
                    return 'data:image/png;base64,' . base64_encode($result);
                }

                Log::error("Error cURL generando barcode", [
                    'code' => $code,
                    'http_code' => $httpCode,
                    'error' => $error
                ]);
            } catch (\Exception $e) {
                Log::error("Excepción cURL barcode: " . $e->getMessage());
            }
        }

        // Fallback con file_get_contents
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0',
                    'ignore_errors' => true
                ]
            ]);

            $result = @file_get_contents($barcodeUrl, false, $context);

            if ($result !== false) {
                $rotatedImage = $this->rotateImage($result, 90);
                return 'data:image/png;base64,' . base64_encode($rotatedImage);
            }
        } catch (\Exception $e) {
            Log::error("Excepción file_get_contents barcode: " . $e->getMessage());
        }

        return $this->getFallbackBarcode($code);
    }


    /**
     * Genera un código de barras de respaldo si el servicio externo falla
     *
     * @param string $code
     * @return string Base64 data URI
     */
    private function getFallbackBarcode($code)
    {
        // Opción 1: Imagen placeholder simple
        // return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        // Opción 2: Generar código de barras con librería PHP local (si la tienes instalada)
        // Ejemplo con Picqer/php-barcode-generator:
        // use Picqer\Barcode\BarcodeGeneratorPNG;
        // $generator = new BarcodeGeneratorPNG();
        // $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
        // return 'data:image/png;base64,' . base64_encode($barcode);

        // Opción 3: Usar otro servicio de código de barras
        try {
            $alternativeUrl = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode($code) .
                "&code=Code128&translate-esc=on&unit=Fit&dpi=96&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff";

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $alternativeUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($result && $httpCode == 200) {
                return 'data:image/png;base64,' . base64_encode($result);
            }
        } catch (\Exception $e) {
            Log::error("Fallback barcode también falló: " . $e->getMessage());
        }

        // Última opción: retornar imagen transparente
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
    }

    /**
     * Rota una imagen PNG
     *
     * @param string $imageData Datos binarios de la imagen
     * @param int $angle Ángulo de rotación (90, 180, 270)
     * @return string Datos binarios de la imagen rotada
     */
    private function rotateImage($imageData, $angle)
    {
        try {
            // Crear imagen desde string
            $image = imagecreatefromstring($imageData);

            if ($image === false) {
                Log::error("No se pudo crear imagen desde string");
                return $imageData; // Retornar original si falla
            }

            // Rotar la imagen
            $rotated = imagerotate($image, -$angle, 0); // Negativo porque GD rota en sentido contrario

            if ($rotated === false) {
                imagedestroy($image);
                return $imageData;
            }

            // Guardar en buffer
            ob_start();
            imagepng($rotated);
            $rotatedData = ob_get_clean();

            // Liberar memoria
            imagedestroy($image);
            imagedestroy($rotated);

            return $rotatedData;
        } catch (\Exception $e) {
            Log::error("Error rotando imagen: " . $e->getMessage());
            return $imageData; // Retornar original si hay error
        }
    }

    public function validatePdf($code)
    {

        // Buscar el cupón en la base de datos
        $coupon = Coupons::with('promotion')->where('code', $code)->first();

        $html = view('pdf.validate', compact('coupon'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper([0, 0, 200, 426]);

        $dompdf->render();

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $code . '.pdf"');
    }

    public function validatePdfCB($code)
    {

        // Buscar el cupón en la base de datos
        $coupon = Bowling::where('int_retoque', $code)->first();

        $html = view('pdf.validateCB', compact('coupon'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper([0, 0, 200, 426]);

        $dompdf->render();

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $code . '.pdf"');
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
