<?php

namespace App\Http\Controllers;

use App\Model\Movimiento;
use App\Model\Kardex;
use App\Model\Lote;
use App\Http\Requests\CompraValidate;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
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
    public function store(CompraValidate $request)
    {
        if ($request->has('fecha')) {
            $fecha=$request->fecha;
        }else {
            $fecha=Carbon::now()->format('Y-m-d');
        }

        DB::beginTransaction();
        try {
            $movSearch=Movimiento::where('documento',$request->documento)
                        ->where('tipo_movimiento',"IXC")
                        ->where('entidad_id',$request->proveedor_id)
                        ->first();
            if ($movSearch!=null) {
                return response()->json([
                    "status"    =>  "ERROR",
                    "data"      =>  "Documento ya registrado"
                ]); 
            }
            $movimiento=new Movimiento();
            $movimiento->documento=$request->documento;
            $movimiento->tipo_movimiento="IXC";
            $movimiento->entidad_id=$request->proveedor_id;
            $movimiento->obra_id=null;
            $movimiento->fecha_ingreso=$fecha;
            $movimiento->save();
            if (count($request->items)==0) {
                return response()->json([
                    "status"=> "WARNING",
                    "data"  => "No existen Items."
                ]);
            }
            foreach ($request->items as $key => $item) {
                $insumo_id=$item['insumo_id'];
                $cantidad=$item['cantidad'];
                $precio=$item['precio'];
                $detalle=$item['detalle'];
                /**
                 * Registro de Lote
                 */
                $lote=new Lote();
                $lote->insumo_id=$insumo_id;
                $lote->precio=$precio;                    
                $lote->stock=$cantidad;                    
                $lote->cantidad=$cantidad;
                $lote->detalle=$detalle;
                $lote->movimiento_id=$movimiento->id;
                $lote->fecha_ingreso=$fecha;
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
                "data"  => "Ingreso x Compra Registrado (".$movimiento->documento.")"
            ]);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                "status"    =>  "ERROR",
                "data"      =>  $e->getMessage()
            ]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
