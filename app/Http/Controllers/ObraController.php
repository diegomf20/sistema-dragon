<?php

namespace App\Http\Controllers;

use App\Model\Obra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\ObraValidate;
use Illuminate\Support\Facades\DB;

class ObraController extends Controller
{
    /**
     * Visualiza todos los Obraes
     */
    public function index(Request $request)
    {

        if ($request->all==true) {
            $obras=Obra::orderBy('id','DESC')->get();
        }else{
            $obras=Obra::orderBy('id','DESC')->paginate(10);
        }
        return response()->json($obras);
    }

    /**
     * Registra un nuevo Obra
     */
    public function store(ObraValidate $request)
    {
        $obra=new Obra();
        $obra->descripcion=$request->descripcion;
        $obra->fecha_inicio=$request->fecha_inicio;
        $obra->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Obra Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo Obra
     */
    public function show($id)
    {
        $obra=Obra::where('id',$id)->first();
        return response()->json($obra);
    }
        
    public function update(ObraValidate $request, $id)
    {
        $obra=Obra::where('id',$id)->first();
        $obra->descripcion=$request->descripcion;
        $obra->fecha_inicio=$request->fecha_inicio;
        $obra->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Obra Actualizado."
        ]);
    }
}
