<?php

namespace App\Http\Controllers;

use App\Model\Movimiento;
use App\Model\Proveedor;
use App\Model\Colaborador;
use App\Model\Kardex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index(Request $request){
        $Movimiento=Movimiento::orderBy('id','DESC')->paginate(10);
        return response()->json($Movimiento);
    }

    public function show(Request $request,$id){
        $movimiento=Movimiento::where('id',$id)->first();
        $movimiento->detalles=Kardex::join('insumo','producto_id','=','insumo.id')
                                ->leftjoin('lote','lote.id','=','kardex.lote_id')
                                ->where('documento_id',$movimiento->id)->get();
        switch ($movimiento->tipo_movimiento) {
            case 'IXC':
                $movimiento->entidad=Proveedor::where('id',$movimiento->entidad_id)->first();
                break;
            case 'SXC':
                $movimiento->entidad=Colaborador::where('id',$movimiento->entidad_id)->first();
                break;
            default:
                break;
        }
        return response()->json($movimiento);
    }
    /**
     * GET
     */
    public function getConsumo(Request $request){
        $documento=$request->codigo;
        // dd($documento);
        $movimiento=Movimiento::where('documento','like',"%$documento")
            ->where('tipo_movimiento','SXC')
            ->first();
            $movimiento_id=$movimiento->id;
        $detalles=DB::select(
                        DB::raw("SELECT  
                                    kardex.lote_id,
                                    kardex.id as kardex_id,
                                    kardex.cantidad - SUM(IFNULL(k_r.cantidad,0)) cantidad,
                                    SUM(k_r.cantidad) cantidad_retorno,
                                    kardex.precio,
                                    insumo.nombre_insumo
                        FROM kardex 
                        INNER JOIN INSUMO on insumo.id=kardex.producto_id
                        LEFT JOIN retorno R ON R.movimiento_id=kardex.documento_id
                        LEFT JOIN kardex k_r ON k_r.documento_id=R.retorno_id AND k_r.producto_id=kardex.producto_id
                        WHERE kardex.documento_id=$movimiento_id
                        GROUP BY kardex.id,kardex.lote_id,kardex.precio,insumo.nombre_insumo
                        ")
                    );

        $movimiento->detalles=$detalles;
        if ($movimiento==null) {
            $movimiento="vacio";
        }
        return response()->json($movimiento);
    }
}
