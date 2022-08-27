<?php

namespace App\Http\Controllers;

use App\Model\Movimiento;
use App\Model\Kardex;
use App\Model\Insumo;
use App\Model\Lote;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ConsumoValidate;


class CuadreController extends Controller
{
    /**
     * 
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
      */
    public function store(Request $request)
    {
        if ($request->has('fecha')) {
            $fecha=$request->fecha;
        }else {
            $fecha=Carbon::now()->format('Y-m-d');
        }
        
        DB::beginTransaction();
        try {
            $movimiento_count=(Movimiento::select(DB::raw('count(id) contar'))
                ->where('tipo_movimiento',$request->tipo)
                ->first()->contar)+1;
            $movimiento=new Movimiento();
            $movimiento->documento=str_pad($movimiento_count, 8, "0", STR_PAD_LEFT);
            $movimiento->tipo_movimiento=$request->tipo;
            $movimiento->fecha_ingreso=$fecha;
            $movimiento->save();
            
            if (count($request->items)==0) {
                return response()->json([
                    "status"=> "WARNING",
                    "data"  => "No existen Items."
                ]);
            }
            switch ($request->tipo) {
                case 'IC':
                    foreach ($request->items as $key => $item) {
                        $insumo_id=$item['insumo_id'];
                        $cantidad=$item['cantidad'];
                        $precio=isset($item['precio']) ? $item['precio'] : 0;
                        // comprobacion de que precio tomar
                        if ($precio==0) {
                            $precio=Lote::where('insumo_id',$insumo_id)
                            ->where('stock','>',0)
                            ->select(DB::raw('SUM(stock*precio)/SUM(stock) as precio_promedio'))
                            ->groupBy('insumo_id')
                            ->first()->precio_promedio;   
                        }
                        /**
                         * Registro de Lote
                         */
                        $lote=new Lote();
                        $lote->insumo_id=$insumo_id;
                        $lote->precio=$precio;                    
                        $lote->stock=$cantidad;
                        $lote->cantidad=$cantidad;
                        $lote->movimiento_id=$movimiento->id;
                        $lote->fecha_ingreso=$fecha;
                        dd($lote);
                        $lote->save();
                        $anterior=Kardex::where('producto_id',$insumo_id)
                            ->where('fecha','<=',$fecha)
                            ->orderBy('fecha','DESC')
                            ->orderBy('id','DESC')
                            ->first();
                        $anterior_stock=0;
                        $anterior_total=0;   
                        if($anterior!=null){
                            $anterior_stock=$anterior->stock;
                            $anterior_total=$anterior->total;
                        }
                        $kardex=new Kardex();
                        $kardex->fecha=$fecha;
                        $kardex->tipo="Ingreso";
                        $kardex->producto_id=$insumo_id;
                        $kardex->cantidad=$cantidad;
                        $kardex->stock=$anterior_stock+$cantidad;
                        $kardex->precio=$precio;
                        $kardex->total=$anterior_total+($cantidad*$precio);
                        $kardex->documento_id=$movimiento->id;
                        $kardex->lote_id=$lote->id;
                        $kardex->save();
                        
                        DB::select('UPDATE KARDEX SET stock = stock + ? , total = total + ?  where producto_id = ? AND fecha > ?', 
                            [$cantidad, $cantidad*$precio, $insumo_id, $fecha]
                        );
                    }
                    DB::commit();
                    return response()->json([
                        "status"=> "OK",
                        "data"  => "INGRESO x CUADRE Registrado (".$movimiento->documento.")"
                    ]);
                    break;
                case 'SC':
                    foreach ($request->items as $key => $item) {
                        if (!isset($item['cantidad'])||$item['cantidad']==0) {
                            return response()->json([
                                "status"=> "WARNING",
                                "data"  => "No se acepta stock 0."
                            ]);
                        }
                        $insumo_id=$item['insumo_id'];
                        $cantidad=$item['cantidad'];
                        while ($cantidad > 0) {
                            $cantidad_registrar=0;
                            $lote=Lote::where('insumo_id',$item['insumo_id'])
                                ->where('stock','>',0)
                                ->orderBy('fecha_ingreso','ASC')
                                ->first();
                            if($lote==null){
                                $insumo=Insumo::where('id',$item['insumo_id'])->first();
                                return response()->json([
                                    "status"=> "WARNING",
                                    "data"  => "Stock Insuficiente en ".$insumo->nombre_insumo
                                ]);
                            }
                            
                            if($lote==null) return response()->json(["status"=>"ERROR","data"=>"No cuenta con Stock en el insumo."]);
                            if ($lote->stock>=$cantidad) {
                                $cantidad_registrar=$cantidad;    
                            }else{
                                $cantidad_registrar=$lote->stock;    
                            }
                            $lote->stock=$lote->stock-$cantidad_registrar;
                            $lote->save();
                            
                            $anterior=Kardex::where('producto_id',$insumo_id)
                                ->where('fecha','<=',$fecha)
                                ->orderBy('fecha','DESC')
                                ->orderBy('id','DESC')
                                ->first();   
                            $stock=$anterior->stock;
                            $total=$anterior->total;
                            $kardex=new Kardex();
                            $kardex->fecha=$fecha;
                            $kardex->tipo="Salida";
                            $kardex->producto_id=$insumo_id;
                            $kardex->cantidad=$cantidad_registrar;
                            $kardex->stock=$stock-$cantidad_registrar;
                            $kardex->precio=$lote->precio;
                            $kardex->total=($anterior->total)-($cantidad_registrar*$lote->precio);
                            $kardex->documento_id=$movimiento->id;
                            $kardex->lote_id=$lote->id;
                            $kardex->save();
                            DB::select('UPDATE KARDEX SET stock = stock - ? , total = total - ?  where producto_id = ? AND fecha > ?', 
                                [$cantidad_registrar, ($cantidad_registrar*$lote->precio), $insumo_id, $fecha]
                            );
                            $cantidad=$cantidad-$cantidad_registrar;
                        }
                    }
                    DB::commit();
                    return response()->json([
                        "status"=> "OK",
                        "data"  => "Salida x Cuadre Registrado (".$movimiento->documento.")"
                    ]);
                    break;
            }             
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                "status"    =>  "ERROR",
                "data"      =>  $e->getMessage()
            ]); 
        }
    }

    public function refresh(){
        $datos=DB::select(
            DB::raw("SELECT KA_S.producto_id FROM kardex as KA_S
            INNER JOIN 
            (
                SELECT MAX(KA.id) kardex_id,producto_id FROM kardex  as KA
                WHERE KA.fecha=(SELECT MAX(fecha) FROM kardex where producto_id=KA.producto_id )
                GROUP BY KA.producto_id
            ) KA_TOP ON KA_TOP.kardex_id= KA_S.id
            INNER JOIN lote ON KA_S.producto_id = lote.insumo_id
            GROUP BY KA_S.producto_id,KA_S.stock
            HAVING SUM(lote.stock)<>KA_S.stock")
        );

        foreach ($datos as $key => $dato) {
            $kardexs=DB::select(
                DB::raw("SELECT kardex.*,movimiento.tipo_movimiento FROM kardex
                INNER JOIN movimiento ON movimiento.id=kardex.documento_id
                
                WHERE producto_id=?
                ORDER BY fecha ASC, id ASC")
            ,[
                $dato->producto_id
            ]);
            $stock=0;
            $total_anterior=0;
            for ($i=0; $i < count($kardexs); $i++) { 
                $kardex=$kardexs[$i];
                if ($i==0) {
                    $stock=$kardex->stock;
                    $total_anterior=$kardex->total;
                }else{
                    if($kardex->tipo=='Ingreso'){
                        $kardexs[$i]->stock=$stock+$kardex->cantidad;
                        $kardexs[$i]->total=$total_anterior+($kardex->cantidad*$kardex->precio);
                    }else{
                        $kardexs[$i]->stock=$stock-$kardex->cantidad;
                        $kardexs[$i]->total=$total_anterior-($kardex->cantidad*$kardex->precio);
                    }

                    $stock=$kardexs[$i]->stock;
                    $total_anterior=$kardexs[$i]->total;
                }
            }
            dd($kardexs);
        }
        dd($data);        
    }
}
