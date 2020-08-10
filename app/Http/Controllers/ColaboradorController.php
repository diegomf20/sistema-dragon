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
        $texto_busqueda=explode(" ",$request->search);
        $colaboradores=Colaborador::where(DB::raw("CONCAT(nombre_colaborador,' ',apellido_colaborador,' ',documento)"),"like","%".$texto_busqueda[0]."%");
        
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $colaboradores=$colaboradores->where(DB::raw("CONCAT(nombre_colaborador,' ',apellido_colaborador,' ',documento)"),"like","%".$texto_busqueda[$i]."%");
        }
        if ($request->all==true) {
            $colaboradores=$colaboradores->get();
        }else{
            $colaboradores=$colaboradores->paginate(8);
        }
        return response()->json($colaboradores);
    }

    /**
     * Registra un nuevo Colaborador
     */
    public function store(ColaboradorValidate $request)
    {
        $colaboradorBuscar=Colaborador::where('documento',$request->documento)->first();
        if ($colaboradorBuscar!=null) {
            return response()->json([
                "status"=>"ERROR",
                "data"=>"El colaborador ya fue registrado."
            ]);
        }
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
