<?php

namespace App\Http\Controllers;

use App\Model\CategoriaGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\CategoriaGastoValidate;
use Illuminate\Support\Facades\DB;

class CategoriaGastoController extends Controller
{
    /**
     * Visualiza todos los CategoriaGastoes
     */
    public function index(Request $request)
    {

        if ($request->all==true) {
            $categoriaGastos=CategoriaGasto::all();
        }else{
            $categoriaGastos=CategoriaGasto::paginate(10);
        }
        return response()->json($categoriaGastos);
    }

    /**
     * Registra un nuevo CategoriaGasto
     */
    public function store(CategoriaGastoValidate $request)
    {
        $categoriaGasto=new CategoriaGasto();
        $categoriaGasto->nombre_categoria=$request->nombre_categoria;
        $categoriaGasto->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Categoria Gasto Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo CategoriaGasto
     */
    public function show($id)
    {
        $categoriaGasto=CategoriaGasto::where('id',$id)->first();
        return response()->json($categoriaGasto);
    }
        
    public function update(CategoriaGastoValidate $request, $id)
    {
        $categoriaGasto=CategoriaGasto::where('id',$id)->first();
        $categoriaGasto->nombre_categoria=$request->nombre_categoria;
        $categoriaGasto->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Categoria Gasto Actualizada."
        ]);
    }
}
