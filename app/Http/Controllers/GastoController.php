<?php

namespace App\Http\Controllers;

use App\Model\Gasto;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function store(Request $request){
        $obra_id=$request->obra_id;
        $gasto=new Gasto();
        $gasto->descripcion=$request->descripcion;
        $gasto->monto=$request->monto;
        $gasto->obra_id=$obra_id;
        $gasto->fecha=$request->fecha;
        $gasto->save();
        return response()->json([
            "status"=> "OK",
            "data" => "Gasto registrado."
        ]);
    }
}
