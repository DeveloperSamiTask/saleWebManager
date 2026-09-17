<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetCart;
use App\Models\Ticket;
use App\Models\Box;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValidateWebs extends Controller
{
    public function index()
    {
        $data['title'] = "Boleteria";
        return view('tickets.validate', $data);
    }

    public function viewBoxes()
    {
        $boxes = Box::all();
        return response()->json(['boxes' => $boxes]);
    }

    public function getList(Request $request)
    {
        // Obtener el código del ticket desde la solicitud
        $code = $request->input('ticket');

        // Consultar la tabla Ticket para obtener el valor del atributo 'tickets'
        $ticket = Ticket::where('code', $code)->first();

        if (!$ticket) {
            return response()->json([
                'message' => 'QR no encontrado',
                'icon' => 'error'
            ], 404);
        }

        if ($ticket->status_coupon == 1) {
            return response()->json([
                'message' => 'Este QR ya ha sido validado',
                'icon' => 'warning'
            ], 400);
        }

        // Verificar si se encontró un ticket con el código proporcionado
        if ($ticket) {
            // Obtener el valor del atributo 'tickets'
            $ticketCodes = $ticket->tickets;

            // Dividir los códigos de los tickets en un array
            $ticketCodesArray = explode(',', $ticketCodes);

            // Inicializar un array para almacenar los registros de cartDet
            $cartDetRecords = [];

            // Iterar sobre cada código de ticket y realizar una consulta para obtener los registros correspondientes en cartDet
            foreach ($ticketCodesArray as $ticketCode) {
                // Consultar cartDet para obtener los registros correspondientes al código de ticket actual
                $cartDetRecordsForCode = DetCart::where('intCartdetId', $ticketCode)->get();

                // Iterar sobre los registros obtenidos y darles formato
                foreach ($cartDetRecordsForCode as $cartDet) {
                    // Formatear los datos según el formato requerido
                    $formattedData = [
                        'id' => $cartDet->intCartdetId,
                        'document' => $cartDet->charCartdetDni,
                        'name' => $cartDet->varCartdetNombres . ' ' . $cartDet->varCartdetApepat . ' ' . $cartDet->varCartdetApemat,
                        'price' => $cartDet->decCartdetStotal,
                        'shift' => $cartDet->shiftCart,
                        'device' => $cartDet->deviceCart,
                        'purchase' => optional($cartDet->cart)->dateCartFreg,
                        'income' => $cartDet->dateCartdetFreg,
                        'sure' => $cartDet->varCartdetseguro,
                        'status' => $cartDet->ticketstatus,
                        'used' => $cartDet->ticketdateuse,
                    ];

                    // Agregar los datos formateados al array de registros de cartDet
                    $cartDetRecords[] = $formattedData;
                }
            }

            // Retornar los registros obtenidos
            return response()->json($cartDetRecords);
        } else {
            // Retornar una respuesta indicando que el ticket no fue encontrado
            return response()->json(['message' => 'Ticket no encontrado', 'icon' => 'error'], 404);
        }
    }

    public function print(Request $request)
    {
        @set_time_limit(60);

        $request->validate([
            'ids' => 'required|string',
            'ticket' => 'required|string',
        ]);

        $boxValue = session('box');
        $idUsuario = session('user')['idusuario'] ?? null;

        $ids = $request->input('ids');
        $ticket = trim($request->input('ticket'));

        $ticketCodesArray = collect(explode(',', $ids))
            ->map(fn($id) => trim($id))
            ->filter()
            ->unique()
            ->values();

        if ($ticketCodesArray->isEmpty()) {
            return response()->json([
                'icon' => 'error',
                'message' => 'No se recibieron entradas para validar.',
            ], 422);
        }

        try {
            $ticketModel = Ticket::where('code', $ticket)->first();

            if (!$ticketModel) {
                return response()->json([
                    'icon' => 'error',
                    'message' => 'QR no encontrado.',
                ], 404);
            }

            $cartItems = DetCart::whereIn('intCartdetId', $ticketCodesArray)->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'icon' => 'error',
                    'message' => 'No se encontraron entradas para validar.',
                ], 404);
            }

            $shiftOptions = [
                1 => 'TURNO COMPLETO',
                2 => 'AFTER SCHOOL',
            ];

            $deviceOptions = [
                'Seleccione' => 'SIN DISPOSITIVO',
                'Tarjeta' => 'TARJETA',
                'Portatarjeta' => 'TARJETA + LANGER',
                'Pulserasilicona' => 'PULSERA SILICONA',
                'Pulserafashion' => 'PULSERA SILICONA AJUSTABLE',
            ];

            $data = $cartItems->map(function ($cartDetRecord) use ($shiftOptions, $deviceOptions) {
                $shift = $shiftOptions[$cartDetRecord->shiftCart] ?? 'SIN TURNO';
                $device = $deviceOptions[$cartDetRecord->deviceCart] ?? 'SIN DISPOSITIVO';

                return [
                    'id' => $cartDetRecord->intCartdetId,
                    'producto' => strtoupper($shift . ' ' . $device),
                    'precio' => (float) $cartDetRecord->decCartdetStotal,
                ];
            })->values()->toArray();

            $total = array_sum(array_column($data, 'precio'));

            $html = '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Validación de Ventas Web</title>
                <style>
                    @page { margin-left: 23px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px; }
                    * { font-family: "century gothic", sans-serif; }
                    table { margin: 5px; font-size: 12px; border-collapse: collapse; width: 100%; }
                    thead tr th { background-color: #00BCD4; text-align: center; padding: 5px; color: white; }
                    tbody tr td { padding: 6px 4px; }
                    .total { text-align: right; font-weight: bold; }
                </style>
            </head>
            <body>
                <h4>Validación de Ventas Web</h4>
                <p>Fecha: ' . now()->format('Y-m-d H:i:s') . '</p>
                <table border="1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data as $index => $row) {
                $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . e($row['id']) . '</td>
                <td>' . e($row['producto']) . '</td>
                <td>' . number_format($row['precio'], 2, '.', '') . '</td>
            </tr>';
            }

            $html .= '<tr>
                <th colspan="3" class="total">TOTAL</th>
                <th>S/. ' . number_format($total, 2, '.', '') . '</th>
            </tr>';

            $html .= '</tbody></table></body></html>';

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);

            $paperHeight = max(426, 190 + (count($data) * 32));
            $dompdf->setPaper([0, 0, 220, $paperHeight]);

            $dompdf->render();

            $pdfContent = $dompdf->output();

            $pdfDir = '/home/ep3s6easy863/web.lagranjavilla.com/validate';

            // $pdfDir = 'Z:/ruta/que/no/existe/validate';

            if (!is_dir($pdfDir)) {
                mkdir($pdfDir, 0755, true);
            }

            if (!is_writable($pdfDir)) {
                return response()->json([
                    'icon' => 'error',
                    'message' => 'La carpeta de boletas no tiene permisos de escritura.',
                ], 500);
            }

            $pdfPath = $pdfDir . '/' . $ticket . '.pdf';
            $written = file_put_contents($pdfPath, $pdfContent);

            if ($written === false || !file_exists($pdfPath)) {
                return response()->json([
                    'icon' => 'error',
                    'message' => 'No se pudo generar la boleta.',
                ], 500);
            }

            DB::transaction(function () use ($ticketModel, $ticketCodesArray, $idUsuario, $boxValue) {
                $ticketModel->status_coupon = 1;
                $ticketModel->date_used = now();
                $ticketModel->save();

                DetCart::whereIn('intCartdetId', $ticketCodesArray)->update([
                    'ticketstatus' => 1,
                    'ticketdateuse' => now(),
                    'cashier' => $idUsuario,
                    'box' => $boxValue,
                ]);
            });

            return response()->json([
                'icon' => 'success',
                'message' => 'Entradas validadas correctamente.',
                'pdfUrl' => asset('validate/' . $ticket . '.pdf'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error validando entradas web', [
                'ticket' => $ticket,
                'ids' => $ids,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'icon' => 'error',
                'message' => 'Error al validar las entradas o generar la boleta.',
            ], 500);
        }
    }
}
