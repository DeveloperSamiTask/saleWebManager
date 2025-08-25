<?php

namespace App\Http\Controllers;

use App\Models\Courtesy\Promotions;
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
}
