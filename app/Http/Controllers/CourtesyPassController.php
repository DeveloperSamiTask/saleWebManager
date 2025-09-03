<?php

namespace App\Http\Controllers;

use App\Models\Courtesy\Combo;
use App\Models\Courtesy\ComboMember;
use App\Models\Courtesy\Link;
use App\Models\Courtesy\Promotions;
use App\Models\CourtesyPass;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class CourtesyPassController extends Controller
{


    public function promotions()
    {
        $data['title'] = "Pases de Cortesía";
        return view('courtesy.promotions', $data);
    }

    public function  showPromotions(Request $request)
    {
        $startDate = $request->get('startDate', '2025-01-01');
        $endDate = $request->get('endDate', '2025-01-31');

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $promotions = Promotions::show($startDate->toDateTimeString(), $endDate->toDateTimeString());

        return response()->json([
            'data' => $promotions,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    public function storePromotion(Request $request)
    {
        try {

            $promotion = new Promotions();
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
            $promotion = Promotions::findOrFail($request->id);
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

    public function create()
    {
        $data['title'] = "Crear Pase de Cortesía";
        $data['promotions']  = Promotions::where('status', 1)->get();
        return view('courtesy.create', $data);
    }

    public function store(Request $request)
    {
        try {

            if (Link::where('code', $request->code)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código ya está registrado. Por favor, intenta con otro.',
                ], 409);
            }

            $purchase = Link::create([
                'code' => $request->code,
                'lastname' => $request->lastname,
                'names' => $request->names,
                'document_type' => $request->document,
                'document_number' => $request->number_doc,
                'phone' => $request->phone,
                'date_issue' => $request->date_issue,
                'status' => 'unused',
                'user_id' => session('user')['idusuario'],
                'observation' => $request->observation,
            ]);

            $combos = json_decode($request->input('combos'), true);

            foreach ($combos as $combo) {
                $comboRecord = Combo::create([
                    'purchase_link_id' => $purchase->id,
                    'combo_id'    => $combo['combo_id'],
                    'quantity'    => $combo['quantity'],
                ]);

                if (isset($combo['miembros']) && is_array($combo['miembros'])) {
                    foreach ($combo['miembros'] as $member) {
                        ComboMember::create([
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

    public function list()
    {
        $data['title'] = "Lista Pases de Cortesía";
        return view('courtesy.index', $data);
    }

    public function listCourtesyPass(Request $request)
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

        $coupons = Link::getList($startDate, $endDate, $isChecked);

        return response()->json([
            'data' => $coupons,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function AuthorizeCourtesy(Request $request)
    {
        try {
            $purchase = Link::findOrFail($request->id);
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

    public function validateForm()
    {
        session()->forget('courtesy_members');
        $purchaseComboMember = ComboMember::whereHas(
            'purchaseCombo',
            function ($query) {
                $query->whereHas('purchaseLink', function ($query) {
                    $query->whereDate('date_issue', now());
                });
            }
        );

        $data['title'] = "Validar Pago Link";
        $data['paymentLink_total'] = $purchaseComboMember->count();
        $data['paymentLink_validados'] = $purchaseComboMember->where('status_entrie', 'used')->count();

        return view('courtesy.validate', $data);
    }

    public function invoice($code)
    {
        return view('courtesy.show', [
            'code' => $code
        ]);
    }

    public function member($id)
    {
        $member = ComboMember::with('purchaseCombo.combo')
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
        $member = ComboMember::findOrFail($id);

        $member->name = $request->input('name');
        $member->dni = $request->input('document');
        $member->status_entrie = $request->input('is_active'); // 'used' o 'unused'

        $member->save();

        return response()->json(['message' => 'Actualizado correctamente']);
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
        return Link::with(['combos.combo', 'combos.members'])
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
}
