<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use App\Models\DetCart;
use App\Models\Ticket;


class SaleWebs extends Controller
{
    public function index()
    {
        $data['title'] = "Boleteria";
        return view('sale_web.ticket', $data);
    }

    public function getTicket(Request $request)
    {
        $ticket = DetCart::findTicketById($request->input('ticket'));
        if ($ticket) {
            return response()->json(['success' => true, 'ticket' => $ticket]);
        } else {
            return response()->json(['success' => false, 'message' => 'No existe entrada']);
        }
    }
    public function whatsapp(Request $request)
    {
        $tickets = $request->input('ids');
        $unique = false;
        $code = null;
        do {
            $code = Str::upper(Str::random(10));

            // Generar un hash del código
            $hashed_code = hash('sha256', $code);

            // Verificar si el hash ya existe en la base de datos
            $existingTicket = Ticket::where('code_coupon', $hashed_code)->first();

            if (!$existingTicket) {
                $unique = true;
            }
        } while (!$unique);

        $row = new Ticket();
        $row->code_coupon = $hashed_code; // Almacenar el hash en lugar del código
        $row->tickets = $tickets;
        $row->code = $code; // También almacenar el código original
        $row->method = $request->input('method');
        $row->method_data = $request->input('phone');
        $row->save();
        return response()->json(['token' => $hashed_code]);
    }

    public function generateQr(Request $request, $token)
    {
        // La desencriptación no es necesaria para hashing
        // Solo necesitas verificar si el token existe en la base de datos

        // Verificar si el hash existe en la base de datos
        $existingTicket = Ticket::where('code_coupon', $token)->first();

        if (!$existingTicket) {
            return response()->json(['error' => 'El token no se encontró en la base de datos.', 'token' => $token], 400);
        }
        // Si el token existe, puedes obtener el código original almacenado
        $original_code = $existingTicket->code;

        return view('sale_web.token', ['QRCode' => $original_code, 'logoPath' => asset('img/logo.png')]);
    }


    public function print(Request $request)
    {
        $tickets = $request->input('ids');
        $unique = false;
        $code = null;
        do {
            $code = Str::upper(Str::random(10));

            // Verificar si el hash ya existe en la base de datos
            $existingTicket = Ticket::where('code', $code)->first();

            if (!$existingTicket) {
                $unique = true;
            }
        } while (!$unique);

        $row = new Ticket();
        $row->code_coupon = $code;
        $row->tickets = $tickets;
        $row->code = $code;
        $row->method = $request->input('method');
        $row->save();

        $qrcode = "data:image/png;base64," . $this->generateQrCode($code);


        // Crear una instancia de Dompdf
        $dompdf = new Dompdf();
        $dompdf->setPaper('b7', 'portrait');

        // Renderizar la vista del PDF con los datos del ticket
        $viewData = [
            'code' => $code,
            'image' => $qrcode,
        ];
        $html = view('sale_web/print', $viewData)->render();

        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);

        // Renderizar el PDF
        $dompdf->render();

        // Obtener el contenido del PDF como una cadena
        $pdfContent = $dompdf->output();

        // Guardar el PDF temporalmente en el servidor
        $pdfPath = public_path('temp/' . $code . '.pdf');
        file_put_contents($pdfPath, $pdfContent);

        // Devolver la URL del PDF como respuesta a la solicitud AJAX
        return response()->json(['pdfUrl' => asset('temp/' . $code . '.pdf')]);
    }

    public function generateQRCode($data)
    {

        // Generar el QR en el servidor
        $qrCode = QrCode::size(220)->generate($data);

        // Convertir el QR a datos de imagen base64
        $qrCodeBase64 = base64_encode($qrCode);

        // Enviar los datos de imagen base64 al cliente como parte de la respuesta
        return $qrCodeBase64;
    }
}
