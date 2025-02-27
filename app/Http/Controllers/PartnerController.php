<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Partner;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\Return_;

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


    public function search(Request $request)
    {
        $search = $request->search;
        $select = $request->select;

        $query = Client::with(['partners', 'proxy'])->where($select, $search)->first();

        if (!$query) {
            return response()->json([
                'icon' => 'warning',
                'message' => 'No se encontraron resultados',
            ]);
        }

        return response()->json($query);
    }

    public function insert(Request $request)
    {
        $existingClient = Client::where('charClienteDni', $request->doc)->first();

        if ($existingClient) {
            return response()->json([
                'icon' => 'warning',
                'message' => 'El DNI ya está registrado.',
            ]);
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
            $partner->type_partner = 0;
            $partner->save();

            if (!empty($request->proxyPatter) || !empty($request->proxyMatter) || !empty($request->proxyNames) || !empty($request->proxyDoc)) {
                $proxy = new Proxy();
                $proxy->proxy_client = $count + 1;
                $proxy->proxy_pattername = $request->proxyPatter;
                $proxy->proxy_mattername = $request->proxyMatter;
                $proxy->proxy_names = $request->proxyNames;
                $proxy->proxy_doc = $request->proxyDoc;
                $proxy->save();
            }


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

    public function renew(Request $request)
    {
        // Verificar si el código llega correctamente
        if (!$request->has('hiddenCode')) {
            return response()->json([
                'icon' => 'error',
                'message' => 'No se recibió el código del socio.',
            ], 400);
        }

        $partner = Partner::where('cClieCode', $request->hiddenCode)->first();
        $partner->dEmisDate = Carbon::createFromFormat('d-m-Y', $request->renewInitdate)->format('Y-m-d');
        $partner->dCaduDate = Carbon::createFromFormat('d-m-Y', $request->renewEnddate)->format('Y-m-d');
        $partner->affiliation = $request->renewAffiliation;
        $partner->status_magic = 0;
        $partner->estado = "";
        $partner->type_partner = 1;
        $partner->save();

        return response()->json([
            'icon' => 'success',
            'message' => 'Socio renovado correctamente',
        ]);
    }

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

    public function update(Request $request)
    {

        $dNacmDate = Carbon::hasFormat($request->editbirthdate, 'd-m-Y')
            ? Carbon::createFromFormat('d-m-Y', $request->editbirthdate)->format('Y-m-d')
            : null;

        $client = Client::find($request->editCodeHidden);
        $client->sClieApel = $request->editpattername . ' ' . $request->editmattername;
        $client->sClieApepat = $request->editpattername;
        $client->sClieApemat = $request->editmattername;
        $client->sClieName = $request->editnames;
        $client->sClieAddr = $request->editaddress;
        $client->sClieTelf = $request->editphone;
        $client->sClieMail = $request->editmail;
        $client->dNacmDate = $dNacmDate;
        $client->charClienteDni = $request->editdoc;
        $client->save();

        $proxy = Proxy::where('proxy_client', $request->editCodeHidden)->first();
        $proxy->proxy_pattername = $request->editproxyPatter;
        $proxy->proxy_mattername = $request->editproxyMatter;
        $proxy->proxy_names = $request->editproxyNames;
        $proxy->proxy_doc = $request->editproxyDoc;
        $proxy->save();

        return response()->json([
            'icon' => 'success',
            'message' => 'Se editaron los datos'
        ]);
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
