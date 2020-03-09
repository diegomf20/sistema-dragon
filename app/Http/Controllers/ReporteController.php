<?php

namespace App\Http\Controllers;

use App\Model\Obra;
use App\Model\Gasto;
use App\Model\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class ReporteController extends Controller
{
    public function stock(Request $request)
    {
        $fecha=($request->has('fecha')&&$request->fecha!="null"&&$request->fecha!=null) ? $request->fecha : Carbon::now()->format('Y-m-d');
        $buscar=$request->buscar;
        $query='SELECT insumo.*,IF(kar.stock is Null,0,kar.stock) stock ,kar.total '.
            'FROM insumo LEFT JOIN ('.'
                SELECT kardex.* '.
                    'FROM kardex WHERE kardex.id=('.
                        "SELECT k.id FROM kardex k WHERE k.producto_id=kardex.producto_id AND k.fecha<='".$fecha."' ORDER BY k.fecha DESC , k.id  DESC LIMIT 1".
                    ')'.
            ') kar ON insumo.id=kar.producto_id '."WHERE CONCAT(insumo.codigo,insumo.nombre_insumo) like '%$buscar%'";

        if ($request->has('paginate')) {
            $datos=$this->paginate($query,[],10,$request->page);
        }else{
            $datos=DB::select(DB::raw($query));
        }

        
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
        $fecha=$request->fecha;
        $codigo=$request->codigo;
        $datos=DB::select(DB::raw("(
            SELECT 	'0' fecha, K.id id,'Inicial' tipo ,K.stock cantidad,0.00 precio,K.stock,K.total,'' documento 
                    FROM 		kardex K INNER JOIN insumo on insumo.id=K.producto_id
                    WHERE 	insumo.codigo=:codigo AND K.fecha<CONCAT(:fecha,'-01')
                    ORDER BY K.fecha DESC, K.id DESC LIMIT 1
                )
                UNION
                (
                    SELECT 	K.fecha,K.id, K.tipo,k.cantidad,K.precio,K.stock,K.total,CONCAT(M.tipo_movimiento,' ',M.documento) documento 
                    FROM 	kardex K INNER JOIN movimiento M on M.id=K.documento_id
                            INNER JOIN insumo on insumo.id=K.producto_id 
                    WHERE 	insumo.codigo=:codigo2 
                    AND DATE_FORMAT(K.fecha,'%Y-%m')= :fecha2 ) ORDER BY fecha ASC , id ASC
                "),[
                    "codigo" =>  $codigo,
                    "codigo2" =>  $codigo,
                    "fecha" =>  $fecha,
                    "fecha2" =>  $fecha,
                ]);
        return response()->json($datos);
        
    }

    public function resumen_obra(Request $request){
        $obra_id=$request->obra_id;
        $obra=Obra::where('id',$obra_id)->first();
        $insumos=DB::select(
            DB::raw("SELECT insumo.id,
                            insumo.nombre_insumo, 
                            GROUP_CONCAT(movimiento.documento) documentos,
                            SUM( IF('IXR'=tipo_movimiento,-1,1)*cantidad ) cantidad,
                            SUM( IF('IXR'=tipo_movimiento,-1,1)*cantidad*precio) total
                    FROM kardex 
                    INNER JOIN movimiento ON movimiento.id=kardex.documento_id
                    INNER JOIN insumo ON insumo.id= kardex.producto_id
                    WHERE obra_id =  :id
                    GROUP BY insumo.id,nombre_insumo"),
        [
            "id"    => $obra_id
        ]);
        $gastos=Gasto::where('obra_id',$obra_id)->get();
        // dd($gastos);
        if ($request->has('pdf')) {
            // return view('pdf.resumen_obra',compact('obra','insumos'));
            $pdf = PDF::loadView('pdf.resumen_obra',compact('obra','insumos','gastos'));
            return $pdf->download('reporte-obra-'.$obra_id.'.pdf');
        }
        return response()->json([
                "obra" => $obra,
                "insumos" => $insumos,
                "gastos" => $gastos
            ]);
    }

    public function paginate($query,$param,$per_page = 10,$page = 1){
        if($page==null){ $page=1; }

        $total=DB::select(DB::raw("SELECT count(*) conteo FROM ($query) AL"),$param)[0]->conteo;
        $last_page=(int)ceil($total/$per_page);
        $offset=($page-1)*$per_page;
        
        $raw_query=DB::select(DB::raw("$query limit $per_page offset $offset"),$param);
        return [
                "current_page"  =>  $page,
                "data"          =>  $raw_query,
                "total"         =>  $total,
                "last_page"     =>  $last_page
            ];
    }

}
