<?php

namespace App\Http\Controllers;

use App\Models\Combos\PurchaseCombo;
use App\Models\Combos\PurchaseComboMember;
use App\Models\Combos\PurchaseComboValidation;
use App\Models\Combos\PurchaseLink;
use App\Models\PromotionsLink;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use Dompdf\Dompdf;
use Dompdf\Options;

use Illuminate\Support\Facades\Response;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

use ZipArchive;


class PaymentLinkController extends Controller
{
    public function list()
    {
        $data['title'] = "Lista Pago Link";
        return view('payment_link.index', $data);
    }

    public function listPayments(Request $request)
    {
        $isChecked = $request->input('isChecked', '0');

        if ($isChecked === '1') {
            // Filtrar por date_issue (DATE sin hora)
            $startDate = Carbon::parse($request->get('startDate', '2025-07-01'))->toDateString(); // Y-m-d
            $endDate = Carbon::parse($request->get('endDate', '2025-07-31'))->toDateString();     // Y-m-d
        } else {
            // Filtrar por date_purchase (DATETIME con hora)
            $startDate = Carbon::parse($request->get('startDate', '2025-07-01'))->startOfDay()->toDateTimeString();
            $endDate = Carbon::parse($request->get('endDate', '2025-07-31'))->endOfDay()->toDateTimeString();
        }

        $coupons = PurchaseLink::getList($startDate, $endDate, $isChecked);

        return response()->json([
            'data' => $coupons,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function AuthorizePayment(Request $request)
    {
        try {
            $purchase = PurchaseLink::findOrFail($request->id);
            $purchase->user_auth = session('user')['idusuario'];
            $purchase->code_auth = $request->code;
            $purchase->date_auth = now();
            $purchase->save();

            return response()->json([
                'success' => true,
                'message' => 'Pago Link autorizado correctamente.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al autorizar el Pago Link: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        $data['title'] = "Agregar Pago Link";
        $data['promotions'] = PromotionsLink::where('status', 1)->get();
        return view('payment_link.create', $data);
    }

    public function storePayment(Request $request)
    {

        try {

            if (PurchaseLink::where('code', $request->code)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código ya está registrado. Por favor, intenta con otro.',
                ], 409);
            }

            $purchase = PurchaseLink::create([
                'code' => $request->code,
                'lastname' => $request->lastname,
                'names' => $request->names,
                'document_type' => $request->document,
                'document_number' => $request->number_doc,
                'phone' => $request->phone,
                'date_purchase' => $request->date_purchase,
                'date_issue' => $request->date_issue,
                'status' => 'unused',
                'user_id' => session('user')['idusuario'],
                'observation' => $request->observation,
            ]);

            $combos = json_decode($request->input('combos'), true);

            foreach ($combos as $combo) {
                $comboRecord = PurchaseCombo::create([
                    'purchase_link_id' => $purchase->id,
                    'combo_id'    => $combo['combo_id'],
                    'quantity'    => $combo['quantity'],
                ]);

                if (isset($combo['miembros']) && is_array($combo['miembros'])) {
                    foreach ($combo['miembros'] as $member) {
                        PurchaseComboMember::create([
                            'purchase_combo_id' => $comboRecord->id,
                            'name'              => $member['name'],
                            'dni'               => $member['dni'],
                            'status_entrie'            => 'unused',
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'icon' => 'success',
                'message' => 'Enlace de pago registrado correctamente. Espere la autorización.',
                'download_url' => route('qr.download', ['code' => $purchase->code]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el enlace de pago.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function downloadQrCode($code)
    {
        $purchase = PurchaseLink::where('code', $code)->first();

        if (!$purchase) {
            return response()->json(['icon' => 'error', 'message' => 'Compra no encontrada.'], 404);
        }

        // Contenido QR base
        $qrContent = $purchase->code;

        // === QR 1: ENTRADA (con logo) ===
        $builderEntrada = new Builder(
            writer: new PngWriter(),
            data: $qrContent,
            size: 300,
            margin: 10,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            foregroundColor: new Color(30, 30, 30),
            backgroundColor: new Color(255, 255, 255),
            logoPath: public_path('img/logo.png'),
            labelText: $qrContent . ' ENTRADA',
            labelFont: new OpenSans(16),
            labelAlignment: LabelAlignment::Center
        );
        $resultEntrada = $builderEntrada->build();

        // === QR 2: COMIDA (sin logo) ===
        $builderComida = new Builder(
            writer: new PngWriter(),
            data: $qrContent,
            size: 300,
            margin: 10,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            foregroundColor: new Color(30, 30, 30),
            backgroundColor: new Color(255, 255, 255),
            labelText: $qrContent . ' COMIDA',
            labelFont: new OpenSans(16),
            labelAlignment: LabelAlignment::Center
        );
        $resultComida = $builderComida->build();

        // Crear un ZIP temporal
        $zip = new ZipArchive();
        $zipFileName = 'qrs_' . $purchase->code . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFromString('qr_entrada.png', $resultEntrada->getString());
            $zip->addFromString('qr_comida.png', $resultComida->getString());
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    //Info: Promociones

    public function promotions()
    {
        $data['title'] = "Promociones";
        return view('payment_link.promotions', $data);
    }

    public function  showPromotions(Request $request)
    {
        $startDate = $request->get('startDate', '2025-01-01');
        $endDate = $request->get('endDate', '2025-01-31');

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $promotions = PromotionsLink::show($startDate->toDateTimeString(), $endDate->toDateTimeString());

        return response()->json([
            'data' => $promotions,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    public function storePromotion(Request $request)
    {
        try {

            $promotion = new PromotionsLink();
            $promotion->name = $request->promo;
            $promotion->price = $request->price;
            $promotion->description = $request->description;
            $promotion->members = $request->members;
            $promotion->status = session('user')['idusuario'];
            $promotion->save();

            return response()->json(['icon' => 'success', 'message' => 'Promoción guardada correctamente']);
        } catch (\Exception $e) {

            return response()->json([
                'icon' => 'error',
                'message' => 'Error al guardar la promoción' . $e->getMessage(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function status(Request $request)
    {

        try {
            $promotion = PromotionsLink::findOrFail($request->id);
            $promotion->status = $request->status;
            $promotion->save();

            return response()->json(['icon' => 'success', 'message' => 'Estado actualizado correctamente', 'success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function validateForm()
    {
        session()->forget('validated_members');
        $purchaseComboMembers = PurchaseComboMember::whereHas('purchaseCombo', function ($query) {
            $query->whereHas('purchaseLink', function ($query) {
                $query->whereDate('date_issue', now());
            });
        });

        $data['title'] = "Validar Pago Link";
        $data['paymentLink_total'] = $purchaseComboMembers->count();
        $data['paymentLink_validados'] = $purchaseComboMembers->where('status_entrie', 'used')->count();
        return view('payment_link.validate', ['data' => $data]);
    }

    public function validateFormFood()
    {
        session()->forget('validated_combos');
        $purchaseComboMembers = PurchaseComboMember::whereHas('purchaseCombo', function ($query) {
            $query->whereHas('purchaseLink', function ($query) {
                $query->whereDate('date_issue', now());
            });
        });

        $data['title'] = "Validar Alimentos Pago Link";
        $data['paymentLink_total'] = $purchaseComboMembers->count();
        $data['paymentLink_validados'] = $purchaseComboMembers->where('status_entrie', 'used')->count();
        return view('payment_link.validate_food', ['data' => $data]);
    }


    public function getQrDetails($code, Request $request)
    {
        session()->forget('validated_members');
        try {
            $link = $this->findPurchaseLinkByCode($code);

            $isValidation = $request->query('validate') === '1';

            if ($isValidation && !$this->isValidIssueDate($link->date_issue)) {
                return response()->json([
                    'message' => 'Este código solo es válido para el día: ' . Carbon::parse($link->date_issue)->format('d/m/Y'),
                    'status' => 'invalid_date'
                ], 403);
            }


            $data = $this->formatMembersData($link);

            return response()->json([
                'data' => $data,
                'link' => $this->formatLinkData($link),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No se encontró el código QR ingresado.',
                'status' => 'not_found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error inesperado.',
                'status' => 'error'
            ], 500);
        }
    }

    private function findPurchaseLinkByCode($code)
    {
        return PurchaseLink::with(['combos.combo', 'combos.members'])
            ->where('code', $code)
            ->firstOrFail();
    }

    private function isValidIssueDate($dateIssue)
    {
        return Carbon::parse($dateIssue)->isSameDay(Carbon::today());
    }

    private function formatMembersData($link)
    {
        $data = [];

        foreach ($link->combos as $combo) {
            foreach ($combo->members as $member) {
                $data[] = [
                    'id'     => $member->id,
                    'names'  => $member->name,
                    'combo'  => $combo->combo->name . ' - ' . ($combo->combo->description ?? 'Sin descripción') . ' (' . $combo->quantity . ')',
                    'document'    => $member->dni,
                    'user'    => $member->user,
                    'activate'    => $member->issue_entrie ? Carbon::parse($member->issue_entrie)->format('d/m/Y H:i:s') : 'No activado',
                    'status' => $member->status_entrie ?? null,
                    'button' => (session('user')['idusuario'] ?? null) == 1
                        ? '<button class="btn btn-warning btn-sm edit-btn" data-id="' . $member->id . '">
                            <i class="mdi mdi-pencil-outline"></i>
                        </button>'
                        : '',
                ];
            }
        }

        return $data;
    }

    private function formatLinkData($link)
    {
        $total = $this->calcularTotal($link);

        return [
            'id'     => $link->id,
            'code'     => $link->code,
            'names'    => trim($link->names . ' ' . $link->lastname),
            'document' => $link->document_type . ': ' . $link->document_number,
            'date' => Carbon::parse($link->date_purchase)->format('d/m/Y h:i A'),
            'date_issue' => $link->activate_date
                ? Carbon::parse($link->activate_date)->format('d/m/Y h:i A') // si ya está activado
                : Carbon::parse($link->date_issue)->format('d/m/Y'),
            'status'   => $link->status,
            'total'  => number_format($total, 2, '.', ''),
        ];
    }

    private function calcularTotal($purchase)
    {
        $total = 0;

        foreach ($purchase->combos as $combo) {
            $cantidad = $combo->quantity;
            $precioUnitario = $combo->combo->price ?? 0;

            $total += $cantidad * $precioUnitario;
        }

        return $total;
    }

    public function getQrDetailsByCombo($code, Request $request)
    {
        try {
            $link = $this->findPurchaseLinkByCodeCombos($code);

            $isValidation = $request->query('validate') === '1';

            if ($isValidation && !$this->isValidIssueDate($link->date_issue)) {
                return response()->json([
                    'message' => 'Este código solo es válido para el día: ' . Carbon::parse($link->date_issue)->format('d/m/Y'),
                    'status' => 'invalid_date'
                ], 403);
            }

            $data = $this->formatCombosData($link);

            return response()->json([
                'data' => $data,
                'link' => $this->formatLinkData($link),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No se encontró el código QR ingresado.',
                'status' => 'not_found'
            ], 404);
        } catch (\Exception $e) {
            Log::error("Error en getQrDetailsByCombo: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Ocurrió un error inesperado.',
                'status' => 'error'
            ], 500);
        }
    }

    private function findPurchaseLinkByCodeCombos($code)
    {
        return PurchaseLink::with([
            'combos.combo',
            'combos.members',
            'combos.validations', // 👈 añadimos las validaciones
        ])
            ->where('code', $code)
            ->firstOrFail();
    }

    private function formatCombosData($link)
    {
        $data = [];

        foreach ($link->combos as $combo) {
            // Total validado sumando todas las validaciones
            $validatedQty = $combo->validations->sum('validated_qty');

            // Determinar estado
            if ($validatedQty == 0) {
                $status = 'unused';
            } elseif ($validatedQty < $combo->quantity) {
                $status = 'partial';
            } else {
                $status = 'used';
            }

            // Tomamos la última validación para mostrar hora/usuario
            $lastValidation = $combo->validations->last();

            $data[] = [
                'id'            => $combo->id,
                'combo'         => $combo->combo->name . ' - ' . ($combo->combo->description ?? 'Sin descripción'),
                'quantity'      => $combo->quantity,
                'validated_qty' => $validatedQty,
                'status'        => $status,
                'validated_at'  => $lastValidation ? $lastValidation->validated_at : null,
                'validated_by'  => $lastValidation ? $lastValidation->validated_by : null,
            ];
        }

        return $data;
    }

    public function validateCombo(Request $request)
    {
        $request->validate([
            'record_id' => 'required',
            'quantity'  => 'required|integer|min:1',
        ]);
        

        $combo = PurchaseCombo::with('combo')->findOrFail($request->record_id);

        // 🟢 Calcular total validado hasta ahora
        $totalValidated = PurchaseComboValidation::where('purchase_link_combo_id', $combo->id)
            ->sum('validated_qty');

        $remaining = $combo->quantity - $totalValidated;

        // 🚨 Si ya no quedan unidades, bloquear
        if ($remaining <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ya se validaron todas las unidades de este combo',
            ], 400);
        }

        // 🚨 Si el usuario intenta validar más de lo que queda
        if ($request->quantity > $remaining) {
            return response()->json([
                'success' => false,
                'message' => "Solo quedan {$remaining} unidades por validar",
            ], 400);
        }

        // 1️⃣ Guardar validación en la BD
        $validation = PurchaseComboValidation::create([
            'purchase_link_combo_id' => $combo->id,
            'validated_qty'          => $request->quantity,
            'validated_at'           => now(),
            'validated_by'           => auth()->id(),
        ]);

        // 2️⃣ Guardar también en la sesión
        $validatedCombos = session('validated_combos', []);

        $validatedCombos[] = [
            'id'          => $combo->id,
            'name'        => $combo->combo->name ?? 'Sin nombre',
            'description' => $combo->combo->description ?? 'Sin descripción',
            'quantity'    => $request->quantity,
            'hora'        => now()->format('H:i:s'),
        ];

        session(['validated_combos' => $validatedCombos]);

        // 3️⃣ Calcular estado
        $newTotalValidated = $totalValidated + $request->quantity;
        $status = $newTotalValidated < $combo->quantity ? 'partial' : 'used';

        $data = [
            'id'            => $combo->id,
            'combo'         => $combo->combo->name . ' - ' . ($combo->combo->description ?? 'Sin descripción'),
            'quantity'      => $combo->quantity,
            'validated_qty' => $validation->validated_qty,
            'status'        => $status,
            'validated_at'  => $validation->validated_at,
            'validated_by'  => $validation->validated_by,
        ];

        return response()->json([
            'success' => true,
            'message' => $combo->combo->name . ' Combo validado correctamente',
            'data'    => $data,
        ]);
    }

    public function printFood(Request $request)
    {
        try {
            $id = $request->input('id');

            // 1️⃣ Traemos la compra con sus combos
            $purchase = PurchaseLink::with('combos.combo')->findOrFail($id);

            // 2️⃣ Actualizamos estado general de la compra (usada / sin usar)
            $unusedCombos = $purchase->combos()->whereDoesntHave('validations')->count();

            $purchase->status = $unusedCombos > 0 ? 'unused' : 'used';
            $purchase->user_active = session('user')['idusuario'] ?? 'Desconocido';
            $purchase->activate_date = now();
            $purchase->save();

            // 3️⃣ Recuperamos lo validado en la sesión
            $validated = session('validated_combos', []);

            // Agrupar lo validado por combo_id
            $agrupadosPorCombo = collect($validated)->groupBy('id');

            $data = [];

            foreach ($agrupadosPorCombo as $comboId => $validaciones) {
                $combo = $purchase->combos->firstWhere('id', $comboId);

                if (!$combo) continue; // por seguridad

                $cantidad       = $combo->quantity;
                $precioUnitario = $combo->combo->price ?? 0;
                $descripcion    = $combo->combo->description ?? '';
                $nombre         = $combo->combo->name ?? '';

                $validadosPrevios = PurchaseComboValidation::where('purchase_link_combo_id', $comboId)
                    ->sum('validated_qty');

                Log::info("Combo ID: {$comboId}, Validados Previos: {$validadosPrevios}");

                $validados = $validaciones->sum('quantity');

                $pendientes = max(0, $cantidad - $validadosPrevios);

                $data[] = [
                    'combo'       => strtoupper($nombre),
                    'descripcion' => strtoupper($descripcion),
                    'cantidad'    => $cantidad,
                    'validados'   => $validados,
                    'pendientes'  => $pendientes,
                    'subtotal'    => $cantidad * $precioUnitario,
                ];
            }

            $total = array_sum(array_column($data, 'subtotal'));

            // 4️⃣ Renderizamos la vista del ticket
            $html = view('payment_link.printFood', [
                'data'             => $data,
                'purchase'         => $purchase,
                'total'            => $total,
                'user'             => session('user')['usuario'] ?? 'Desconocido',
                'agrupadosPorCombo' => $agrupadosPorCombo,
                'validated'        => $validated, // 👈 Enviamos también por si en la vista quieres ver hora exacta
            ])->render();

            // 5️⃣ Generamos el PDF
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A5', 'portrait');
            $dompdf->render();

            return Response::make($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="comprobante.pdf"',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Excepción: ' . $e->getMessage()
            ], 500);
        }
    }


    public function dniValidate(Request $request)
    {
        $id = trim($request->input('id'));
        $dni = trim($request->input('dni'));

        $member = PurchaseComboMember::with('purchaseCombo.combo')
            ->where('id', $id)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el registro.',
            ], 404);
        }

        if ($member->dni != $dni) {
            return response()->json([
                'success' => false,
                'message' => 'DNI incorrecto.',
            ], 404);
        }

        if ($member->status_entrie === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'El ingreso ya fue registrado.',
            ], 409);
        }

        // Marcar como usado
        $member->status_entrie = 'used';
        $member->user = session('user')['usuario'];
        $member->issue_entrie = now();
        $member->save();

        $combo = $member->purchaseCombo->combo;

        // Guardar en la sesión
        $validatedGroup = session('validated_members', []);

        // Evitar duplicados por ID
        if (!collect($validatedGroup)->contains('id', $member->id)) {
            $validatedGroup[] = [
                'id'          => $member->id,
                'name'        => $member->name,
                'combo_id'    => $combo->id, // 🔸 agregar combo_id
                'combo'       => $combo->name,
                'descripcion' => $combo->description ?? 'Sin descripción',
                'dni'         => $member->dni,
                'hora'        => now()->format('H:i:s'),
            ];
            session(['validated_members' => $validatedGroup]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Ingreso validado correctamente.',
            'data' => [
                'id'       => $member->id,
                'names'    => $member->name,
                'combo'    => $combo->name . ' - ' . ($combo->description ?? 'Sin descripción'),
                'document' => $member->dni,
                'status'   => $member->status_entrie,
            ],
        ]);
    }

    public function print(Request $request)
    {
        try {
            $id = $request->input('id');

            $purchase = PurchaseLink::with('combos.combo')->findOrFail($id);

            $purchaseComboMembersUnused = PurchaseComboMember::whereHas('purchaseCombo', function ($query) use ($id) {
                $query->whereHas('purchaseLink', function ($query) use ($id) {
                    $query->where('id', $id);
                });
            })->where('status_entrie', 'unused')->count();

            $purchase->status = $purchaseComboMembersUnused > 0 ? 'unused' : 'used';
            $purchase->user_active = session('user')['idusuario'] ?? 'Desconocido';
            $purchase->activate_date = now();
            $purchase->save();

            $data = [];

            $validated = session('validated_members', []);
            $agrupadosPorCombo = collect($validated)->groupBy('combo');

            foreach ($purchase->combos as $combo) {
                $cantidad = $combo->quantity;
                $precioUnitario = $combo->combo->price ?? 0;
                $descripcion = $combo->combo->description ?? '';
                $nombre = $combo->combo->name ?? '';
                $validados = $agrupadosPorCombo->has($nombre)
                    ? $agrupadosPorCombo[$nombre]->count()
                    : 0;


                $data[] = [
                    'combo' => strtoupper($nombre),
                    'descripcion' => strtoupper($descripcion),
                    'cantidad' => $cantidad,
                    'validados' => $validados, // 👈 Agregado aquí
                    'subtotal' => $cantidad * $precioUnitario,
                ];
            }

            $total = array_sum(array_column($data, 'subtotal'));

            $html = view('payment_link.print', [
                'data' => $data,
                'purchase' => $purchase,
                'total' => $total,
                'user' => session('user')['usuario'] ?? 'Desconocido',
                'agrupadosPorCombo' => $agrupadosPorCombo,

            ])->render();

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A5', 'portrait');
            $dompdf->render();



            return Response::make($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="comprobante.pdf"',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Excepción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function invoice($code)
    {
        return view('payment_link.show', [
            'code' => $code
        ]);
    }

    public function member($id)
    {
        $member = PurchaseComboMember::with('purchaseCombo.combo')
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $member->id,
            'name' => $member->name,
            'dni' => $member->dni,
            'combo' => $member->purchaseCombo->combo->name,
            'description' => $member->purchaseCombo->combo->description ?? 'Sin descripción',
            'is_active' => $member->status_entrie,
            'issue_entrie' => $member->issue_entrie ? Carbon::parse($member->issue_entrie)->format('d/m/Y H:i:s') : null,
        ]);
    }

    public function updateMember(Request $request, $id)
    {
        $member = PurchaseComboMember::findOrFail($id);

        $member->name = $request->input('name');
        $member->dni = $request->input('document');
        $member->status_entrie = $request->input('is_active'); // 'used' o 'unused'

        $member->save();

        return response()->json(['message' => 'Actualizado correctamente']);
    }

    public function indexCombo()
    {
        $data['title'] = "Reportes Pago Link - Combos";
        return view('reports.payment_link.combos', $data);
    }

    public function reportCombos(Request $request)
    {
        $startDate = Carbon::parse($request->get('startDate', '2025-07-01'))->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->get('endDate', '2025-07-31'))->endOfDay()->toDateTimeString();

        $combos = PurchaseComboValidation::getList($startDate, $endDate);

        return response()->json([
            'data' => $combos,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
