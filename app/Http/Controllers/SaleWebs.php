<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Dompdf\Dompdf;
use App\Models\DetCart;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleWebs extends Controller
{
    public function index()
    {
        $data['title'] = "Boleteria";
        return view('sale_web.ticket', $data);
    }

    public function fdt()
    {
        $data['title'] = "Boleteria";
        return view('sale_web.fdt_ticket', $data);
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

    public function tickets_fdt()
    {
        $tickets = DetCart::countTicketsForTodayFDT();
        return response()->json([$tickets]);
    }

    public function receiptByClient(Request $request, int $clientCode)
    {
        $date = $request->query('fecha', $request->query('date'));
        $baseQuery = DetCart::where('cliente_cClieCode', $clientCode)
            ->where('ticketstatus', 1);

        if (!$date) {
            $date = (clone $baseQuery)->max('dateCartdetFreg');
        }

        $entries = (clone $baseQuery)
            ->when($date, fn($query) => $query->whereDate('dateCartdetFreg', $date))
            ->orderBy('intCartdetId')
            ->get();

        abort_if($entries->isEmpty(), 404, 'No hay entradas validadas para ese cliente' . ($date ? ' en la fecha ' . $date : '') . '.');

        return $this->receiptResponse(
            $entries,
            'CLIENTE-' . $clientCode,
            $date ?? now(),
            'boleta_cliente_' . $clientCode . ($date ? '_' . $date : '') . '.pdf'
        );
    }
    private function receiptResponse(Collection $entries, string $code, $fecha, string $filename)
    {
        $data = $this->receiptData($entries);
        $total = array_sum(array_column($data, 'precio'));
        $html = view('sale_web/print', compact('code', 'data', 'total', 'fecha'))->render();
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($this->receiptPaper(count($data)));
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, max-age=0',
        ]);
    }

    private function receiptData(Collection $carts): array
    {
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

        return $carts->map(function ($cart) use ($shiftOptions, $deviceOptions) {
            $shift = $shiftOptions[$cart->shiftCart] ?? 'SIN TURNO';
            $device = $deviceOptions[$cart->deviceCart] ?? 'SIN DISPOSITIVO';

            return [
                'id' => $cart->intCartdetId,
                'producto' => strtoupper($shift . ' - ' . $device),
                'precio' => $cart->decCartdetStotal,
            ];
        })->all();
    }

    private function receiptPaper(int $rowCount): array
    {
        return [0, 0, 200, max(426, 190 + ($rowCount * 40))];
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
        @set_time_limit(120);

        $ids = collect(explode(',', (string) $request->input('ids')))
            ->map(fn($id) => trim($id))
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se recibieron entradas para validar.',
            ], 422);
        }

        $method = $request->input('method');
        $boxValue = session('box');
        $idUsuario = session('user')['idusuario'] ?? auth()->id();

        try {
            do {
                $code = Str::upper(Str::random(10));
            } while (Ticket::where('code', $code)->exists());

            $carts = DetCart::whereIn('intCartdetId', $ids)->get()->keyBy('intCartdetId');
            $missing = $ids->reject(fn($id) => $carts->has($id))->values();

            if ($missing->isNotEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Algunas entradas no existen o no fueron encontradas.',
                    'missing' => $missing,
                ], 422);
            }

            $entries = $ids->map(fn($id) => $carts->get((int) $id))->filter()->values();
            $data = $this->receiptData($entries);
            $total = array_sum(array_column($data, 'precio'));
            $html = view('sale_web/print', compact('code', 'data', 'total'))->render();

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper($this->receiptPaper(count($data)));
            $dompdf->render();

            $validateDir = '/home/ep3s6easy863/web.lagranjavilla.com/validate';
            if (!is_dir($validateDir)) {
                $validateDir = public_path('validate');
            }
            if (!is_dir($validateDir) && !mkdir($validateDir, 0775, true)) {
                throw new \RuntimeException('No se pudo crear la carpeta para guardar la boleta.');
            }

            $pdfPath = $validateDir . DIRECTORY_SEPARATOR . $code . '.pdf';
            if (@file_put_contents($pdfPath, $dompdf->output()) === false) {
                throw new \RuntimeException('No se pudo guardar el PDF de validación.');
            }

            $updated = DB::transaction(function () use ($ids, $method, $boxValue, $idUsuario, $code) {
                $locked = DetCart::whereIn('intCartdetId', $ids)->lockForUpdate()->get();

                if ($locked->count() !== $ids->count()) {
                    throw new \RuntimeException('La cantidad de entradas cambió durante la validación.');
                }

                $alreadyUsed = $locked->where('ticketstatus', 1)->pluck('intCartdetId')->values();
                if ($alreadyUsed->isNotEmpty()) {
                    throw new \RuntimeException('Algunas entradas ya estaban validadas: ' . $alreadyUsed->implode(', '));
                }

                $ticket = new Ticket();
                $ticket->code_coupon = $code;
                $ticket->tickets = $ids->implode(',');
                $ticket->code = $code;
                $ticket->method = $method;
                $ticket->status_coupon = 1;
                $ticket->date_used = now();
                $ticket->save();

                return DetCart::whereIn('intCartdetId', $ids)->update([
                    'ticketstatus' => 1,
                    'ticketdateuse' => now(),
                    'cashier' => $idUsuario,
                    'box' => $boxValue,
                ]);
            });

            return response()->json([
                'success' => true,
                'pdfUrl' => asset('validate/' . $code . '.pdf'),
                'received' => $ids->count(),
                'updated' => $updated,
                'code' => $code,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error validating ticketera web entries', [
                'ids' => $ids->all(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo completar la validación: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function printfdtQr(Request $request)
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

        $code_entrie = "";

        // 3. Convertir IDs y obtener datos de DetCart
        $ticketCodesArray = explode(',', $ids);
        $data = [];

        foreach ($ticketCodesArray as $id) {
            $cart = DetCart::find($id);
            if ($cart) {
                // Actualizar estado del ticket
                $cart->ticketstatus = 1;
                $code_entrie = $cart->intCartId;
                $cart->ticketdateuse = now();
                $cart->cashier = $idUsuario;
                $cart->box = $boxValue;
                $cart->save();

                // Determinar el tipo de producto según intBoletoId
                $ticketType = match (intval($cart->intBoletoId)) {
                    11 => 'ENTRADA GENERAL TERROR',
                    17 => 'ENTRADA LIGHT TERROR',
                    default => 'ENTRADA DESCONOCIDA'
                };

                // Agregar datos al array
                $data[] = [
                    'id' => $cart->intCartdetId,
                    'producto' => strtoupper($ticketType),
                    'precio' => $cart->decCartdetStotal,
                ];
            }
        }

        // 4. Calcular total
        $total = array_sum(array_column($data, 'precio'));

        // 5. Generar HTML PDF
        $html = view('sale_web/print_fdt', [
            'code' => $code_entrie,
            'data' => $data,
            'total' => $total,
            'fecha' => now()->format('d/m/Y H:i:s'),
        ])->render();

        // 6. PDF con Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 200, 426]); // Ajusta el tamaño según necesites
        $dompdf->render();

        // 7. Guardar PDF temporal
        $pdfPath = '/home/ep3s6easy863/web.lagranjavilla.com/validate/fdt_' . $code . '.pdf';
        file_put_contents($pdfPath, $dompdf->output());

        // 8. Responder con URL
        return response()->json([
            'success' => true,
            'pdfUrl' => asset('validate/fdt_' . $code . '.pdf')
        ]);
    }

    public function getTicketsFdt(Request $request)
    {
        $tickets = DetCart::findTicketFdtById($request->input('ticket'));

        if ($tickets) {
            return response()->json(['success' => true, 'tickets' => $tickets]);
        } else {
            return response()->json(['success' => false, 'message' => 'No existe entrada']);
        }
    }
}
