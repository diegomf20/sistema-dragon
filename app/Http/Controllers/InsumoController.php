<?php

namespace App\Http\Controllers;

use App\Model\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\InsumoValidate;
use Illuminate\Support\Facades\DB;

class InsumoController extends Controller
{
    /**
     * Visualiza todos los Insumoes
     */
    public function index(Request $request)
    {

        if ($request->all==true) {
            $insumos=Insumo::all();
        }else{
            $insumos=Insumo::paginate(8);
        }
        return response()->json($insumos);
    }

    /**
     * Registra un nuevo Insumo
     */
    public function store(InsumoValidate $request)
    {
        $Insumo=new Insumo();
        $Insumo->documento=$request->documento;
        $Insumo->razon_social=$request->razon_social;
        $Insumo->mail=$request->mail;
        $Insumo->telefono=$request->telefono;
        $Insumo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Insumo Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo Insumo
     */
    public function show($id)
    {
        $Insumo=Insumo::where('id',$id)->first();
        return response()->json($Insumo);
    }
        
    public function update(InsumoValidate $request, $id)
    {
        $Insumo=Insumo::where('id',$id)->first();
        $Insumo->documento=$request->documento;
        $Insumo->razon_social=$request->razon_social;
        $Insumo->mail=$request->mail;
        $Insumo->telefono=$request->telefono;
        $Insumo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Insumo Actualizado."
        ]);
    }
}
