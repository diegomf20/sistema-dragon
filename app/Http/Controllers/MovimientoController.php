<?php

namespace App\Http\Controllers;

use App\Model\Movimiento;
use App\Model\Proveedor;
use App\Model\Colaborador;
use App\Model\Kardex;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index(Request $request){
        $Movimiento=Movimiento::orderBy('id','DESC')->paginate(10);
        return response()->json($Movimiento);
    }

    public function show(Request $request,$id){
        $movimiento=Movimiento::where('id',$id)->first();
        $movimiento->detalles=Kardex::join('insumo','producto_id','=','insumo.id')->where('documento_id',$movimiento->id)->get();
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
