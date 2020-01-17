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
            $obras=Obra::all();
        }else{
            $obras=Obra::paginate(8);
        }
        return response()->json($obras);
    }

    /**
     * Registra un nuevo Obra
     */
    public function store(ObraValidate $request)
    {
        $Obra=new Obra();
        $Obra->documento=$request->documento;
        $Obra->razon_social=$request->razon_social;
        $Obra->mail=$request->mail;
        $Obra->telefono=$request->telefono;
        $Obra->save();
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
        $Obra=Obra::where('id',$id)->first();
        return response()->json($Obra);
    }
        
    public function update(ObraValidate $request, $id)
    {
        $Obra=Obra::where('id',$id)->first();
        $Obra->documento=$request->documento;
        $Obra->razon_social=$request->razon_social;
        $Obra->mail=$request->mail;
        $Obra->telefono=$request->telefono;
        $Obra->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Obra Actualizado."
        ]);
    }
}
