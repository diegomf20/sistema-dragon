<?php

namespace App\Http\Controllers;

use App\Model\CategoriaInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\CategoriaInsumoValidate;
use Illuminate\Support\Facades\DB;

class CategoriaInsumoController extends Controller
{
    /**
     * Visualiza todos los CategoriaInsumoes
     */
    public function index(Request $request)
    {

        if ($request->all==true) {
            $categoriaInsumos=CategoriaInsumo::all();
        }else{
            $categoriaInsumos=CategoriaInsumo::paginate(10);
        }
        return response()->json($categoriaInsumos);
    }

    /**
     * Registra un nuevo CategoriaInsumo
     */
    public function store(CategoriaInsumoValidate $request)
    {
        $categoriaInsumo=new CategoriaInsumo();
        $categoriaInsumo->nombre_categoria=$request->nombre_categoria;
        $categoriaInsumo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Categoria Insumo Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo CategoriaInsumo
     */
    public function show($id)
    {
        $categoriaInsumo=CategoriaInsumo::where('id',$id)->first();
        return response()->json($categoriaInsumo);
    }
        
    public function update(CategoriaInsumoValidate $request, $id)
    {
        $categoriaInsumo=CategoriaInsumo::where('id',$id)->first();
        $categoriaInsumo->nombre_categoria=$request->nombre_categoria;
        $categoriaInsumo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Categoria Insumo Actualizada."
        ]);
    }
}
