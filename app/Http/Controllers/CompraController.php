<?php

namespace App\Http\Controllers;

use App\Model\Movimiento;
use App\Model\Kardex;
use App\Model\Lote;
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
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $movimiento=new Movimiento();
            $movimiento->documento=$request->documento;
            $movimiento->tipo_movimiento="IXC";
            $movimiento->entidad_id=$request->proveedor_id;
            $movimiento->obra_id=null;
            $movimiento->fecha_ingreso=Carbon::now();
            $movimiento->save();
            foreach ($request->items as $key => $item) {
                $insumo_id=$item['insumo_id'];
                $cantidad=$item['cantidad'];
                $precio=$item['precio'];
                /**
                 * Registro de Lote
                 */
                $lote=new Lote();
                $lote->insumo_id=$insumo_id;
                $lote->precio=$precio;                    
                $lote->stock=$cantidad;                    
                $lote->cantidad=$cantidad;
                $lote->save();
                    
                $anterior=Kardex::where('producto_id',$insumo_id)
                    ->orderBy('id','DESC')
                    ->orderBy('fecha','DESC')
                    ->first();   

                $kardex=new Kardex();
                $kardex->fecha=Carbon::now();
                $kardex->tipo="Ingreso";
                $kardex->producto_id=$insumo_id;
                $kardex->cantidad=$cantidad;
                $kardex->stock=$anterior->stock+$cantidad;
                $kardex->precio=$precio;
                $kardex->total=$anterior->total+($cantidad*$precio);
                $kardex->documento_id=$movimiento->id;
                $kardex->save();
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
