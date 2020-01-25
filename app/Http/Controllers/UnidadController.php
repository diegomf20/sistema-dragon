<?php

namespace App\Http\Controllers;

use App\Model\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\UnidadValidate;
use Illuminate\Support\Facades\DB;

class UnidadController extends Controller
{
    /**
     * Visualiza todos los unidades
     */
    public function index(Request $request)
    {

        if ($request->all==true) {
            $unidades=Unidad::all();
        }else{
            $unidades=Unidad::paginate(10);
        }
        return response()->json($unidades);
    }

    /**
     * Registra un nuevo unidad
     */
    public function store(unidadValidate $request)
    {
        $unidad=new Unidad();
        $unidad->nombre_unidad=$request->nombre_unidad;
        $unidad->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Unidad Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo unidad
     */
    public function show($id)
    {
        $unidad=Unidad::where('id',$id)->first();
        return response()->json($unidad);
    }
        
    public function update(UnidadValidate $request, $id)
    {
        $unidad=Unidad::where('id',$id)->first();
        $unidad->nombre_unidad=$request->nombre_unidad;
        $unidad->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Unidad Actualizado."
        ]);
    }
}
