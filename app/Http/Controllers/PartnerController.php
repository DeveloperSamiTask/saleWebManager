<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Partner;
use App\Models\Proxy;
use App\Models\V_Birthday;
use App\Models\V_NMagic;
use App\Models\LogPartnet;
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

        // Buscar cliente con su socio asociado
        $client = Client::with('partner', 'proxy')->where($select, $search)->first();

        if (!$client) {
            return response()->json([
                'icon' => 'warning',
                'message' => 'No se encontraron resultados.',
            ]);
        }

        return response()->json($client);
    }


    public function insert(Request $request)
    {
        $dNacmDate = $this->convertDate($request->birthdate);
        $dEmisDate = $this->convertDate($request->initdate);
        $dCaduDate = $this->convertDate($request->enddate);

        $existingClient = Client::where('charClienteDni', $request->doc)->first();

        if ($existingClient) {
            // Verificar si ya es socio en Partner
            $existingPartner = Partner::where('cClieCode', $existingClient->cClieCode)->first();

            if ($existingPartner) {
                return response()->json([
                    'icon' => 'warning',
                    'message' => 'El cliente ya es socio.',
                    'doc' => $request->doc,
                ]);
            }

            // Si el cliente existe pero no es socio, actualizamos sus datos
            $dNacmDate = Carbon::hasFormat($request->birthdate, 'd-m-Y')
                ? Carbon::createFromFormat('d-m-Y', $request->birthdate)->format('Y-m-d')
                : null;

            $existingClient->update([
                'sClieApel'   => $request->pattername . ' ' . $request->mattername,
                'sClieApepat' => $request->pattername,
                'sClieApemat' => $request->mattername,
                'sClieName'   => $request->names,
                'sClieAddr'   => $request->address,
                'sClieTelf'   => $request->phone,
                'sClieMail'   => $request->mail,
                'dNacmDate'   => $dNacmDate,
                'iTipo'       => 1,
                'IdLocal'     => 1,
            ]);

            // Después de actualizar, lo agregamos como socio
            return $this->createPartner($existingClient->cClieCode, $request, $dEmisDate, $dCaduDate);
        }

        // Obtener el último código de cliente
        $count = Partner::whereRaw('LENGTH(cClieCode) = 6')
            ->orderBy('id', 'desc')
            ->value('cClieCode') ?? 0;
        $newClientCode = $count + 1;

        try {
            // Guardar cliente
            $client = new Client();
            $client->cClieCode = $newClientCode;
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
            return $this->createPartner($newClientCode, $request, $dEmisDate, $dCaduDate);
        } catch (\Throwable $th) {
            return response()->json([
                'icon' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Crea un socio en la tabla Partner.
     */
    private function createPartner($clientCode, Request $request, $dEmisDate = null, $dCaduDate = null)
    {
        try {
            $partner = new Partner();
            $partner->cClieCode = $clientCode;
            $partner->nTarjNumb = str_pad($clientCode, 8, '0', STR_PAD_LEFT);
            $partner->cTarjActi = 1;
            $partner->dEmisDate = $dEmisDate;
            $partner->dCaduDate = $dCaduDate;
            $partner->affiliation = $request->affiliation;
            $partner->IdLocal = 1;
            $partner->estado = "";
            $partner->status_magic = 0;
            $partner->type_partner = 0;
            $partner->user_new = auth()->user()->usuario ?? 'Sistema';
            $partner->save();

            // Guardar apoderado si se ingresó información
            if (!empty($request->proxyPatter) || !empty($request->proxyMatter) || !empty($request->proxyNames) || !empty($request->proxyDoc)) {
                $proxy = new Proxy();
                $proxy->proxy_client = $clientCode;
                $proxy->proxy_pattername = $request->proxyPatter;
                $proxy->proxy_mattername = $request->proxyMatter;
                $proxy->proxy_names = $request->proxyNames;
                $proxy->proxy_doc = $request->proxyDoc;
                $proxy->save();
            }

            LogPartnet::registerLog($clientCode, 0, $request->affiliation);

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
     * Convierte una fecha de 'd-m-Y' a 'Y-m-d', retorna null si el formato es inválido.
     */
    private function convertDate($date)
    {
        return Carbon::hasFormat($date, 'd-m-Y') ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null;
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

        // Verificar si el socio existe
        $partner = Partner::where('cClieCode', $request->hiddenCode)->first();

        if (!$partner) {
            return response()->json([
                'icon' => 'error',
                'message' => 'El socio no existe.',
            ], 404);
        }

        // Convertir fechas con seguridad
        $dEmisDate = $this->convertDate($request->renewInitdate);
        $dCaduDate = $this->convertDate($request->renewEnddate);

        if (!$dEmisDate || !$dCaduDate) {
            return response()->json([
                'icon' => 'error',
                'message' => 'Formato de fecha inválido. Use el formato dd-mm-yyyy.',
            ], 400);
        }

        // Actualizar datos del socio
        $partner->update([
            'dEmisDate'     => $dEmisDate,
            'dCaduDate'     => $dCaduDate,
            'affiliation'   => $request->renewAffiliation,
            'status_magic'  => 0,
            'estado'        => '',
            'type_partner'  => 1,
            'user_renew'    => auth()->user()->usuario ?? 'Sistema',
        ]);

        // Agregar el log en la tabla log_partnets
        LogPartnet::registerLog($request->hiddenCode, 1, $request->renewAffiliation);

        return response()->json([
            'icon' => 'success',
            'message' => 'Socio renovado correctamente.',
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

        // Buscar proxy solo si existe
        $proxy = Proxy::where('proxy_client', $request->editCodeHidden)->first();
        if ($proxy) {
            $proxy->proxy_pattername = $request->editproxyPatter;
            $proxy->proxy_mattername = $request->editproxyMatter;
            $proxy->proxy_names = $request->editproxyNames;
            $proxy->proxy_doc = $request->editproxyDoc;
            $proxy->save();
        }

        return response()->json([
            'icon' => 'success',
            'message' => 'Se editaron los datos correctamente'
        ]);
    }


    public function report_view()
    {
        $data['title'] = "Reporte de Validación de Cupones Socios";
        return view('coupons.report', $data);
    }

    public function showValidate(Request $request)
    {
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

        $v_birthday = V_Birthday::show($startDate, $endDate);
        $v_magic = V_NMagic::show($startDate, $endDate);

        return response()->json([
            'data' => array_merge($v_birthday, $v_magic),
            'count' => [
                'birthday' => count($v_birthday),
                'magic' => count($v_magic),
                'total' => count($v_birthday) + count($v_magic)
            ]
        ]);
    }
}
