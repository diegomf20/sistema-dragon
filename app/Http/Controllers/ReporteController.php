<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function stock(Request $request)
    {
        $fecha=($request->has('fecha')&&$request->fecha!="null"&&$request->fecha!=null) ? $request->fecha : Carbon::now()->format('Y-m-d');
        $buscar=$request->buscar;
        $datos=DB::select(DB::raw('SELECT insumo.*,IF(kar.stock is Null,0,kar.stock) stock '.
            'FROM insumo LEFT JOIN ('.'
                SELECT kardex.producto_id, kardex.stock '.
                    'FROM kardex WHERE kardex.id=('.
                        "SELECT k.id FROM kardex k WHERE k.producto_id=kardex.producto_id AND k.fecha<'".$fecha."' ORDER BY k.id DESC , k.fecha DESC LIMIT 1".
                    ')'.
            ') kar ON insumo.id=kar.producto_id '."WHERE insumo.nombre_insumo like '%$buscar%'"));
        return response()->json($datos);
    }
}
