<?php

namespace App\Http\Controllers;

use App\Model\Activo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\ActivoValidate;
use Illuminate\Support\Facades\DB;

class ActivoController extends Controller
{
    /**
     * Visualiza todos los Colaboradores
     */
    public function index(Request $request)
    {
        $texto_busqueda=explode(" ",$request->search);
        $activos=Activo::select('activo.*','obra.titulo')->leftJoin('obra','obra.id','=','activo.obra_id')
        ->where("nombre_activo","like","%".$texto_busqueda[0]."%");
        
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $activos=$activos->where("nombre_activo","like","%".$texto_busqueda[$i]."%");
        }
        if ($request->all==true) {
            $activos=$activos->get();
        }else{
            $activos=$activos->paginate(8);
        }
        return response()->json($activos);
    }

    /**
     * Registra un nuevo Colaborador
     */
    public function store(ActivoValidate $request)
    {

        $activo_contar=(Activo::select(DB::raw('count(id) contar'))->first()->contar)+1;
        $activo=new Activo();
        $activo->codigo=str_pad($activo_contar, 4, "0", STR_PAD_LEFT);
        $activo->nombre_activo=$request->nombre_activo;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        // $activo->obra_id=$request->obra_id;
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo Colaborador
     */
    public function show($id)
    {
        $activo=Activo::where('id',$id)->first();
        return response()->json($activo);
    }
        
    public function update(ActivoValidate $request, $id)
    {
        $activo=Activo::where('id',$id)->first();
        $activo->nombre_activo=$request->nombre_activo;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo Actualizado."
        ]);
    }
    
    public function asignarObra(Request $request, $id){
        $activo=Activo::where('id',$id)->first();
        $activo->obra_id=$request->obra_id;
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo Actualizado."
        ]);
    }
}
