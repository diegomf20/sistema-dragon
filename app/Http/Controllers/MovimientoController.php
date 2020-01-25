<?php

namespace App\Http\Controllers;

use App\Model\Movimiento;
use App\Model\Kardex;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    /**
     * GET
     */
    public function getConsumo(Request $request){
        $documento=$request->codigo;
        // dd($documento);
        $movimiento=Movimiento::with('detalles')
            ->join('obra','obra.id','=','movimiento.obra_id')
            ->leftJoin('retorno','movimiento.id','=','retorno.movimiento_id')
            ->select('movimiento.*','retorno.retorno_id','obra.titulo')
            ->where('documento','like',"%$documento")
            ->where('tipo_movimiento','SXC')
            ->whereNull('retorno_id')
            ->first();
        if ($movimiento==null) {
            $movimiento="vacio";
        }
        return response()->json($movimiento);
    }
}
