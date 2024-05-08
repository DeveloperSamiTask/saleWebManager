<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetCart;
use App\Models\Ticket;
use App\Models\Box;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;

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
            return response()->json(['error' => 'Ticket no encontrado'], 404);
        }
    }

    public function print(Request $request)
    {
        $boxValue = session('box');
        $idUsuario = session('user')['idusuario'];

        // Obtener los datos del formulario
        $ids = $request->input('ids');
        $ticket = $request->input('ticket');

        // Convertir los IDs a un array
        $ticketCodesArray = explode(',', $ids);

        // Inicializar un array para almacenar los datos
        $data = [];

        $ticketModel = Ticket::where('code', $ticket)->first();
        if ($ticketModel) {
            $ticketModel->status_coupon = 1;
            $ticketModel->date_used = now();
            $ticketModel->save();
        }

        foreach ($ticketCodesArray as $id) {
            $detCartModel = DetCart::find($id);
            if ($detCartModel) {
                $detCartModel->ticketstatus = 1;
                $detCartModel->ticketdateuse = now(); // O la fecha y hora actual
                $detCartModel->cashier = $idUsuario;
                $detCartModel->box = $boxValue;
                $detCartModel->save();
            }
        }
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

        // Iterar sobre cada ID de entrada y obtener los datos de DetCart
        foreach ($ticketCodesArray as $id) {
            $cartDetRecord = DetCart::find($id);

            // Verificar si se encontró el registro
            if ($cartDetRecord) {


                // Obtener los valores de shift y device del registro actual
                $shiftValue = $cartDetRecord->shiftCart;
                $deviceValue = $cartDetRecord->deviceCart;

                // Formatear los valores según las definiciones
                $shift = isset($shiftOptions[$shiftValue]) ? strtoupper($shiftOptions[$shiftValue]) : "SIN TURNO";
                $device = isset($deviceOptions[$deviceValue]) ? strtoupper($deviceOptions[$deviceValue]) : "SIN DISPOSITIVO";

                // Concatenar device y shift en producto
                $producto = $shift . " " . $device;

                // Agregar los datos formateados al array de datos
                $data[] = [
                    'id' => $cartDetRecord->intCartdetId,
                    'producto' => $producto,
                    'precio' => $cartDetRecord->decCartdetStotal,
                ];
            }
        }

        // Generar HTML para el PDF
        $html = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>PDF</title>
                    <style>
                    @page { margin-left: 23px; }
                    *	  		{ font-family: "century gothic"; }
                    table 		{ margin: 5px; font-size:12px; border-collapse: collapse; }
                    thead tr td { background-color: #00BCD4; text-align: center; padding: 5px; color: white; }
                    thead 		{ border-bottom: 1px solid #000; border-style: dotted; }
                    tbody tr td	{ padding: 1em; }
                    </style>
                </head>
                <body>
                    <h4>Validación de Ventas Web</h4>
                    <p>Fecha: ' . date('Y-m-d H:i:s') . '</p>
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

        // Agregar filas de datos al HTML
        $total = array_sum(array_column($data, 'precio'));
        $contador = 0;
        foreach ($data as $row) {
            $contador++;
            $html .= '<tr>
                    <td>' . $contador . '</td>
                    <td>' . $row['id'] . '</td>
                    <td>' . $row['producto'] . '</td>
                    <td>' . $row['precio'] . '</td>
                </tr>';
        }

        $html .= '<tr>
        <th colspan="3" class="grand total" style="text-align: right !important;">TOTAL </th>
        <th class="grand total"> S/. ' . $total . '</th>
        </tr>';

        // Cerrar el cuerpo y la tabla HTML
        $html .= '</tbody></table></body></html>';

        // Configurar opciones de Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);

        // Establecer el tamaño del papel

        // Crear una instancia de Dompdf
        $dompdf = new Dompdf($options);
        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 200, 426]);

        // Renderizar el PDF
        $dompdf->render();

        // Obtener el contenido del PDF como una cadena
        $pdfContent = $dompdf->output();


        // Guardar el PDF temporalmente en el servidor
        $pdfPath = '/home/ep3s6easy863/web.lagranjavilla.com/validate/' . $ticket . '.pdf';
        file_put_contents($pdfPath, $pdfContent);

        // Devolver la URL del PDF como respuesta a la solicitud AJAX
        return response()->json(['pdfUrl' => asset('validate/' . $ticket . '.pdf')]);
    }

    public function viewLogsDNI(){
        $data['title'] = "Rectificación de DNI";
        return view('tickets.dni', $data);
    }
}
