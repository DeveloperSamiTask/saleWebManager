<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\Templates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = "Plantillas";
        $userCompanyIds = session('companies_array', []);
        $data['userCompanies'] = Companies::whereIn('id', $userCompanyIds)->get();
        $data['companies'] = Companies::all();
        return view('template.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $template = new Templates();
            $template->template_company = $request->template_company;
            $template->company_id = $request->company;
            $template->promotion_id = $request->promotion;
            $template->content = $request->content;
            $template->save();

            return response()->json([
                'status' => true,
                'icon' => 'success',
                'message' => 'Plantilla guardada correctamente',
            ]);
        } catch (\Throwable $th) {
            // Registrar el error en los logs de Laravel
            Log::error('Error al guardar la plantilla: ' . $th->getMessage());

            return response()->json([
                'status' => false,
                'icon' => 'error',
                'message' => 'Ocurrió un error al guardar la plantilla',
            ], 500);
        }
    }


    public function show()
    {
        $templates = Templates::getTemplates();
        return response()->json(['data' => $templates]);
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
    public function update(Request $request)
    {
        $template = Templates::find($request->template_id);
        $template->company_id = $request->company;
        $template->promotion_id = $request->promotion;
        $template->content = $request->content;
        $template->save();
        return response()->json([
            'status' => true,
            'icon' => 'success',
            'message' => 'Plantilla actualizada correctamente',
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
