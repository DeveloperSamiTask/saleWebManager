<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = "Socios";
        return view('partners.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request)
    {
        // Verificar si el DNI ya existe
        $existingClient = Client::where('charClienteDni', $request->doc)->first();

        if ($existingClient) {
            return response()->json([
                'icon' => 'warning',
                'message' => 'El DNI ya está registrado.',
            ], 404);
        }

        // Obtener el último código de cliente
        $count = Partner::orderBy('id', 'desc')->value('cClieCode') ?? 0;

        try {
            // Convertir fechas con validación previa
            $dNacmDate = Carbon::hasFormat($request->birthdate, 'd-m-Y')
                ? Carbon::createFromFormat('d-m-Y', $request->birthdate)->format('Y-m-d')
                : null;

            $dEmisDate = Carbon::hasFormat($request->initdate, 'd-m-Y')
                ? Carbon::createFromFormat('d-m-Y', $request->initdate)->format('Y-m-d')
                : null;

            $dCaduDate = Carbon::hasFormat($request->enddate, 'd-m-Y')
                ? Carbon::createFromFormat('d-m-Y', $request->enddate)->format('Y-m-d')
                : null;

            // Guardar cliente
            $client = new Client();
            $client->cClieCode = $count + 1;
            $client->sClieApel = $request->pattername . ' ' . $request->mattername;
            $client->sClieApepat = $request->pattername;
            $client->sClieApemat = $request->mattername;
            $client->sClieName = $request->names;
            $client->sClieAddr = $request->address;
            $client->sClieTelf = $request->phone;
            $client->sClieMail = $request->mail;
            $client->dNacmDate = $dNacmDate;
            $client->iTipo = 1;
            $client->IdLocal  = 1;
            $client->charClienteDni = $request->doc;
            $client->save();

            // Guardar socio
            $partner = new Partner();
            $partner->cClieCode = $count + 1;
            $partner->nTarjNumb = '00' . ($count + 1);
            $partner->cTarjActi = 1;
            $partner->dEmisDate = $dEmisDate;
            $partner->dCaduDate = $dCaduDate;
            $partner->affiliation = $request->affiliation;
            $partner->IdLocal = 1;
            $partner->estado = "";
            $partner->status_magic = 0;
            $partner->save();

            return response()->json([
                'icon' => 'success',
                'message' => 'Socio agregado correctamente',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $startDate = $request->get('startDate', '2025-01-01');
        $endDate = $request->get('endDate', '2025-01-15');

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $partners = Partner::getAllPartners($startDate->toDateTimeString(), $endDate->toDateTimeString());
        return response()->json([
            'data' => $partners,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
