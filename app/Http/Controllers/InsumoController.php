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
            $insumos=Insumo::paginate(10);
        }
        return response()->json($insumos);
    }

    /**
     * Registra un nuevo Insumo
     */
    public function store(InsumoValidate $request)
    {
        $insumo_contar=(Insumo::select(DB::raw('count(id) contar'))->first()->contar)+1;
        $insumo=new Insumo();
        $insumo->codigo=str_pad($insumo_contar, 4, "0", STR_PAD_LEFT);
        $insumo->nombre_insumo=$request->nombre_insumo;
        $insumo->punto_reorden=$request->punto_reorden;
        $insumo->save();
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
        $insumo=Insumo::where('id',$id)->first();
        return response()->json($insumo);
    }
        
    public function update(InsumoValidate $request, $id)
    {
        $insumo=Insumo::where('id',$id)->first();
        $insumo->nombre_insumo=$request->nombre_insumo;
        $insumo->punto_reorden=$request->punto_reorden;
        $insumo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Insumo Actualizado."
        ]);
    }
}
