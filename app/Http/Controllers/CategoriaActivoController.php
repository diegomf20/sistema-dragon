<?php

namespace App\Http\Controllers;

use App\Model\CategoriaActivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\CategoriaActivoValidate;
use Illuminate\Support\Facades\DB;

class CategoriaActivoController extends Controller
{
    /**
     * Visualiza todos los CategoriaActivoes
     */
    public function index(Request $request)
    {

        if ($request->all==true) {
            $CategoriaActivoes=CategoriaActivo::all();
        }else{
            $CategoriaActivoes=CategoriaActivo::paginate(10);
        }
        return response()->json($CategoriaActivoes);
    }

    /**
     * Registra un nuevo CategoriaActivo
     */
    public function store(CategoriaActivoValidate $request)
    {
        $CategoriaActivo=new CategoriaActivo();
        $CategoriaActivo->nombre_categoria=$request->nombre_categoria;
        $CategoriaActivo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Categoria Activo Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo CategoriaActivo
     */
    public function show($id)
    {
        $CategoriaActivo=CategoriaActivo::where('id',$id)->first();
        return response()->json($CategoriaActivo);
    }
        
    public function update(CategoriaActivoValidate $request, $id)
    {
        $CategoriaActivo=CategoriaActivo::where('id',$id)->first();
        $CategoriaActivo->nombre_categoria=$request->nombre_categoria;
        $CategoriaActivo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Categoria Activo Actualizado."
        ]);
    }
}
