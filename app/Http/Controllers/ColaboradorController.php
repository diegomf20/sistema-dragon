<?php

namespace App\Http\Controllers;

use App\Model\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\ColaboradorValidate;
use Illuminate\Support\Facades\DB;

class ColaboradorController extends Controller
{
    /**
     * Visualiza todos los Colaboradores
     */
    public function index(Request $request)
    {
        if ($request->all==true) {
            $colaboradores=Colaborador::all();
        }else{
            $colaboradores=Colaborador::paginate(8);
        }
        return response()->json($colaboradores);
    }

    /**
     * Registra un nuevo Colaborador
     */
    public function store(ColaboradorValidate $request)
    {
        $Colaborador=new Colaborador();
        $Colaborador->documento=$request->documento;
        $Colaborador->nombre_colaborador=$request->nombre_colaborador;
        $Colaborador->apellido_colaborador=$request->apellido_colaborador;
        $Colaborador->telefono=$request->telefono;
        $Colaborador->mail=$request->mail;
        $Colaborador->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Colaborador Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo Colaborador
     */
    public function show($id)
    {
        $Colaborador=Colaborador::where('id',$id)->first();
        return response()->json($Colaborador);
    }
        
    public function update(ColaboradorValidate $request, $id)
    {
        $Colaborador=Colaborador::where('id',$id)->first();
        $Colaborador->documento=$request->documento;
        $Colaborador->nombre_colaborador=$request->nombre_colaborador;
        $Colaborador->apellido_colaborador=$request->apellido_colaborador;
        $Colaborador->telefono=$request->telefono;
        $Colaborador->mail=$request->mail;
        $Colaborador->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Colaborador Actualizado."
        ]);
    }
}
