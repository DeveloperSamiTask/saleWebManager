<?php

namespace App\Http\Controllers;

use App\Models\Combos\PurchaseCombo;
use App\Models\Combos\PurchaseComboMember;
use App\Models\Combos\PurchaseLink;
use App\Models\PromotionsLink;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class PaymentLinkController extends Controller
{
    public function list()
    {
        $data['title'] = "Lista Pago Link";
        return view('payment_link.index', $data);
    }

    public function listPayments(Request $request)
    {
        $startDate = $request->get('startDate', '2025-07-01');
        $endDate = $request->get('endDate', '2025-07-31');

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $coupons = PurchaseLink::getList($startDate->toDateTimeString(), $endDate->toDateTimeString());

        return response()->json([
            'data' => $coupons,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
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
            return response()->json(['message' => 'Compra no encontrada.'], 404);
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
}
