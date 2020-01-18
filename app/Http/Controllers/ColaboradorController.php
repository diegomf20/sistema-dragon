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
        $colaborador=new Colaborador();
        $colaborador->documento=$request->documento;
        $colaborador->nombre_colaborador=$request->nombre_colaborador;
        $colaborador->apellido_colaborador=$request->apellido_colaborador;
        $colaborador->telefono=$request->telefono;
        $colaborador->mail=$request->mail;
        $colaborador->save();
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
        $colaborador=Colaborador::where('id',$id)->first();
        return response()->json($colaborador);
    }
        
    public function update(ColaboradorValidate $request, $id)
    {
        $colaborador=Colaborador::where('id',$id)->first();
        $colaborador->documento=$request->documento;
        $colaborador->nombre_colaborador=$request->nombre_colaborador;
        $colaborador->apellido_colaborador=$request->apellido_colaborador;
        $colaborador->telefono=$request->telefono;
        $colaborador->mail=$request->mail;
        $colaborador->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Colaborador Actualizado."
        ]);
    }
}
