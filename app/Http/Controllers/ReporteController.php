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
        $datos=DB::select(DB::raw('SELECT insumo.*,IF(kar.stock is Null,0,kar.stock) stock ,kar.total '.
            'FROM insumo LEFT JOIN ('.'
                SELECT kardex.* '.
                    'FROM kardex WHERE kardex.id=('.
                        "SELECT k.id FROM kardex k WHERE k.producto_id=kardex.producto_id AND k.fecha<='".$fecha."' ORDER BY k.id DESC , k.fecha DESC LIMIT 1".
                    ')'.
            ') kar ON insumo.id=kar.producto_id '."WHERE insumo.nombre_insumo like '%$buscar%'"));
        return response()->json($datos);
    }
    
    public function reorden(Request $request){
        $fecha=($request->has('fecha')&&$request->fecha!="null"&&$request->fecha!=null) ? $request->fecha : Carbon::now()->format('Y-m-d');
        $buscar=$request->buscar;
        $datos=DB::select(DB::raw('SELECT insumo.*,IF(kar.stock is Null,0,kar.stock) stock '.
            'FROM insumo LEFT JOIN ('.'
                SELECT kardex.producto_id, kardex.stock '.
                    'FROM kardex WHERE kardex.id=('.
                        "SELECT k.id FROM kardex k WHERE k.producto_id=kardex.producto_id AND k.fecha<='".$fecha."' ORDER BY k.id DESC , k.fecha DESC LIMIT 1".
                    ')'.
            ') kar ON insumo.id=kar.producto_id '."WHERE insumo.nombre_insumo like '%$buscar%' AND insumo.punto_reorden >=stock"));
        return response()->json($datos);
    }
    
    /**
     * Parametros mes-año (:fecha) y codigo producto(:codigo) 
     */
    public function kardex_unitario(Request $request){
        // dd($request->all());
        $fecha=$request->fecha;
        $codigo=$request->codigo;
        $datos=DB::select(DB::raw("(
            SELECT 	'Inicial' fecha,'Inicial' tipo ,K.stock cantidad,0.00 precio,K.stock,K.total,'' documento 
                    FROM 		kardex K INNER JOIN insumo on insumo.id=K.producto_id
                    WHERE 	insumo.codigo='0001' AND K.fecha<CONCAT(:fecha,'-01')
                    ORDER BY K.id DESC,K.fecha DESC LIMIT 1
                )
                UNION
                (
                    SELECT 	K.fecha,K.tipo,k.cantidad,K.precio,K.stock,K.total,CONCAT(M.tipo_movimiento,' ',M.documento) documento 
                    FROM 	kardex K INNER JOIN movimiento M on M.id=K.documento_id
                            INNER JOIN insumo on insumo.id=K.producto_id 
                    WHERE 	insumo.codigo=:codigo 
                    AND DATE_FORMAT(K.fecha,'%Y-%m')= :fecha2 )
                "),[
                    "codigo" =>  $codigo,
                    "fecha" =>  $fecha,
                    "fecha2" =>  $fecha,
                ]);
        return response()->json($datos);
        
    }
}
