<?php

namespace App\Http\Controllers;

use App\Model\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GastoValidate;
use Maatwebsite\Excel\Facades\Excel;

class GastoController extends Controller
{
    public function index(Request $request){
        $fecha_inicio=$request->fecha_inicio;
        $fecha_fin=$request->fecha_fin;
        $data=DB::select(
                DB::raw("SELECT 	gasto.fecha,
                                    gasto.descripcion, 
                                    gasto.monto, 
                                    obra.titulo obra,
                                    categoria_gasto.nombre_categoria categoria
                        FROM gasto  
                        INNER JOIN obra ON obra.id=gasto.obra_id
                        LEFT JOIN categoria_gasto ON categoria_gasto.id=gasto.categoria_id
                        WHERE gasto.fecha BETWEEN ? AND ?
                        ORDER BY gasto.id DESC"),
                [
                    $fecha_inicio,
                    $fecha_fin
                ]);
        if ($request->has('excel')) {
            return Excel::download(new DataExports($data,'exports.ingreso-insumos'), "ingreso-insumos.xlsx");
        }
        return response()->json($data);
    }

    public function store(GastoValidate $request){
        $gasto=new Gasto();
        $gasto->descripcion=$request->descripcion;
        $gasto->monto=$request->monto;
        $gasto->obra_id=$request->obra_id;
        $gasto->categoria_id=$request->categoria_id;
        $gasto->fecha=$request->fecha;
        $gasto->save();
        return response()->json([
            "status"=> "OK",
            "data" => "Gasto registrado."
        ]);
    }
}
