<?php

namespace App\Http\Controllers;

use App\Models\Combos\PurchaseCombo;
use App\Models\Combos\PurchaseComboMember;
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
                'message' => 'Enlace de pago registrado correctamente.',
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

        $qrContent = $purchase->code;

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $qrContent,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(30, 30, 30),
            backgroundColor: new Color(255, 255, 255),
            logoPath: public_path('img/logo.png'), // sin logo
            labelText: $qrContent,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        $result = $builder->build();

        $filename = 'qr_' . $purchase->code . '.png';

        return response($result->getString())
            ->header('Content-Type', $result->getMimeType())
            ->header('Content-Disposition', "attachment; filename={$filename}");
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
            $promotion->status = 1;
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

    public function getQrDetails($code)
    {
        try {
            $link = $this->findPurchaseLinkByCode($code);

            if (!$this->isValidIssueDate($link->date_issue)) {
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
                    'combo'  => $combo->combo->name . ' - ' . ($combo->combo->description ?? 'Sin descripción'),
                    'document'    => $member->dni,
                    'status' => $member->status_entrie ?? null,
                ];
            }
        }

        return $data;
    }

    private function formatLinkData($link)
    {
        return [
            'id'     => $link->id,
            'code'     => $link->code,
            'names'    => trim($link->names . ' ' . $link->lastname),
            'document' => $link->document_type . ': ' . $link->document_number,
            'date'     => $link->date_purchase,
            'status'   => $link->status,
        ];
    }

    public function dniValidate(Request $request)
    {
        $dni = trim($request->input('dni'));
        $member = PurchaseComboMember::with('purchaseCombo.combo')
            ->where('dni', $dni)
            ->first();

        if (!$member) {
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

            foreach ($purchase->combos as $combo) {
                $cantidad = $combo->quantity;
                $precioUnitario = $combo->combo->price ?? 0;
                $descripcion = $combo->combo->description ?? '';
                $nombre = $combo->combo->name ?? '';

                $data[] = [
                    'combo' => strtoupper($nombre),
                    'descripcion' => strtoupper($descripcion),
                    'cantidad' => $cantidad,
                    'subtotal' => $cantidad * $precioUnitario,
                ];
            }

            $total = array_sum(array_column($data, 'subtotal'));

            $html = view('payment_link.print', [
                'data' => $data,
                'total' => $total,
                'user' => session('user')['usuario'] ?? 'Desconocido'
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
}
