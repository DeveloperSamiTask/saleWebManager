<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function tickets()
    {
        $tickets = DetCart::countTicketsForToday();
        return response()->json([$tickets]);
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


    public function printQR(Request $request)
    {
        $ids = $request->input('ids'); // String con IDs separados por coma
        $method = $request->input('method');
        $boxValue = session('box');
        $idUsuario = session('user')['idusuario'];

        // 1. Generar código único
        do {
            $code = Str::upper(Str::random(10));
        } while (Ticket::where('code', $code)->exists());

        // 2. Guardar Ticket
        $ticket = new Ticket();
        $ticket->code_coupon = $code;
        $ticket->tickets = $ids;
        $ticket->code = $code;
        $ticket->method = $method;
        $ticket->status_coupon = 1;
        $ticket->date_used = now();
        $ticket->save();

        // 3. Convertir IDs y actualizar DetCart
        $ticketCodesArray = explode(',', $ids);
        $data = [];
        $shiftOptions = [
            1 => "TURNO COMPLETO",
            2 => "AFTER SCHOOL",
        ];
        $deviceOptions = [
            "Seleccione" => "SIN DISPOSITIVO",
            "Tarjeta" => "TARJETA",
            "Portatarjeta" => "TARJETA + LANGER",
            "Pulserasilicona" => "PULSERA SILICONA",
            "Pulserafashion" => "PULSERA SILICONA AJUSTABLE",
        ];

        foreach ($ticketCodesArray as $id) {
            $cart = DetCart::find($id);
            if ($cart) {
                $cart->ticketstatus = 1;
                $cart->ticketdateuse = now();
                $cart->cashier = $idUsuario;
                $cart->box = $boxValue;
                $cart->save();

                $shift = $shiftOptions[$cart->shiftCart] ?? 'SIN TURNO';
                $device = $deviceOptions[$cart->deviceCart] ?? 'SIN DISPOSITIVO';

                $data[] = [
                    'id' => $cart->intCartdetId,
                    'producto' => strtoupper($shift . ' - ' . $device),
                    'precio' => $cart->decCartdetStotal,
                ];
            }
        }

        // 5. Generar HTML PDF
        $total = array_sum(array_column($data, 'precio'));
        $html = view('sale_web/print', [
            'code' => $code,
            'data' => $data,
            'total' => $total,
        ])->render();

        // 6. PDF con Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 200, 426]);
        $dompdf->render();

        // 7. Guardar PDF temporal
        $pdfPath = '/home/ep3s6easy863/web.lagranjavilla.com/validate/' . $code . '.pdf';
        file_put_contents($pdfPath, $dompdf->output());

        // 8. Responder con URL
        return response()->json(['pdfUrl' => asset('validate/' . $code . '.pdf')]);
    }



}
