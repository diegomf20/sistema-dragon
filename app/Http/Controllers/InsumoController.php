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
        // dd($request->all());
        $insumos=Insumo::select('insumo.*','nombre_unidad')
                    ->leftJoin('unidad','unidad.id','=','insumo.unidad_id')
                    ->where('nombre_insumo','like','%'.$request->search.'%');

        if ($request->all==true) {
            $insumos=$insumos->get();
        }else{
            $insumos=$insumos->paginate(10);
        }
        return response()->json($insumos);
    }

    /**
     * Registra un nuevo Insumo
     */
    public function store(InsumoValidate $request)
    {
        // dd(strtoupper($request->nombre_insumo));
        $insumo_contar=(Insumo::select(DB::raw('count(id) contar'))->first()->contar)+1;
        $insumo=new Insumo();
        $insumo->codigo=str_pad($insumo_contar, 4, "0", STR_PAD_LEFT);
        $insumo->nombre_insumo=strtoupper($request->nombre_insumo);
        $insumo->punto_reorden=$request->punto_reorden;
        $insumo->unidad_id=$request->unidad_id;
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
        // dd(strtoupper($request->nombre_insumo));

        $insumo=Insumo::where('id',$id)->first();
        $insumo->nombre_insumo=strtoupper($request->nombre_insumo);
        $insumo->punto_reorden=$request->punto_reorden;
        $insumo->unidad_id=$request->unidad_id;
        $insumo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Insumo Actualizado."
        ]);
    }
}
