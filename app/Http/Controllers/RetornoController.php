<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\Movimiento;
use App\Model\Kardex;
use App\Model\Retorno;
use App\Model\Lote;
use Carbon\Carbon;

class RetornoController extends Controller
{
    public function store(Request $request){
        $movimiento_id=$request->movimiento_id;
        $items=$request->items;
        DB::beginTransaction();
        $movimiento_count=(Movimiento::select(DB::raw('count(id) contar'))
                                            ->where('tipo_movimiento','IXR')
                                            ->first()->contar)+1;
        $movimiento_referencia=Movimiento::where('id',$movimiento_id)->first();
        
        try {
            $movimiento=new Movimiento();
            $movimiento->documento=str_pad($movimiento_count, 8, "0", STR_PAD_LEFT);
            $movimiento->tipo_movimiento="IXR";
            $movimiento->obra_id=$movimiento_referencia->obra_id;
            $movimiento->fecha_ingreso=Carbon::now();
            $movimiento->save();

            $retorno=new Retorno();
            $retorno->movimiento_id=$movimiento_id;
            $retorno->retorno_id=$movimiento->id;
            $retorno->save();

            $comprobar_retornos=0;
            foreach ($request->items as $key => $item) {
                if (isset($item['retorno'])) {
                    /**
                     * sumar si ingreso
                     */
                    $comprobar_retornos++;

                    $kardex_id=$item['kardex_id'];
                    $retorno=$item['retorno'];
                    $kardex_retorno=Kardex::where('id',$kardex_id)->first();
                    if ($item['retorno']>$kardex_retorno->cantidad) {
                        return response()->json([
                            "status"=> "WARNING",
                            "data"  => "Cantidad de retorno supera al consumo."
                        ]);    
                    }
                    /**
                     * Actualizar Lote
                     */
                    $lote=Lote::where('id',$kardex_retorno->lote_id)->first();
                    $lote->stock+=(int)$retorno;                    
                    $lote->save();
                    
                    $anterior=Kardex::where('producto_id',$kardex_retorno->producto_id)
                        ->orderBy('id','DESC')
                        ->orderBy('fecha','DESC')
                        ->first();

                    $kardex=new Kardex();
                    $kardex->fecha=Carbon::now();
                    $kardex->tipo="Ingreso";
                    $kardex->producto_id=$kardex_retorno->producto_id;
                    $kardex->cantidad=$retorno;
                    $kardex->stock=$anterior->stock+$retorno;
                    $kardex->precio=$lote->precio;
                    $kardex->total=$anterior->total+($retorno*$lote->precio);
                    $kardex->documento_id=$movimiento->id;
                    $kardex->lote_id=$lote->id;
                    $kardex->save();
                }
            }

            if (count($request->items)==0||$comprobar_retornos==0) {
                return response()->json([
                    "status"=> "WARNING",
                    "data"  => "Sin Retornos Ingresados"
                ]);
            }

            DB::commit();
            return response()->json([
                "status"=> "OK",
                "data"  => "Ingreso x Retorno Registrado (".$movimiento->tipo_movimiento."-".$movimiento->documento.")"
            ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                "status"    =>  "ERROR",
                "data"      =>  $e->getMessage()
            ]); 
        }

    }
}
