<?php

namespace App\Http\Controllers;

use App\Model\Obra;
use App\Model\Gasto;
use App\Model\Movimiento;
use App\Exports\ReporteStockExports;
use App\Exports\DataExports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;


class ReporteController extends Controller
{
    // public function stock(Request $request)
    // {
    //     $fecha=($request->has('fecha')&&$request->fecha!="null"&&$request->fecha!=null) ? $request->fecha : Carbon::now()->format('Y-m-d');
    //     $buscar=$request->buscar;
    //     $query='SELECT insumo.*,IF(kar.stock is Null,0,kar.stock) stock ,kar.total '.
    //         'FROM insumo LEFT JOIN ('.'
    //             SELECT kardex.* '.
    //                 'FROM kardex WHERE kardex.id=('.
    //                     "SELECT k.id FROM kardex k WHERE k.producto_id=kardex.producto_id AND k.fecha<='".$fecha."' ORDER BY k.fecha DESC , k.id  DESC LIMIT 1".
    //                 ')'.
    //         ') kar ON insumo.id=kar.producto_id '."WHERE CONCAT(insumo.codigo,insumo.nombre_insumo) like '%$buscar%'";

    //     if ($request->has('paginate')) {
    //         $datos=$this->paginate($query,[],10,$request->page);
    //     }else{
    //         $datos=DB::select(DB::raw($query));
    //         if ($request->has('excel')) {
    //             return Excel::download(new ReporteStockExports($datos), "stock.xlsx");
    //         }
    //     }

        
    //     return response()->json($datos);
    // }
    public function stock(Request $request)
    {
        $fecha=($request->has('fecha')&&$request->fecha!="null"&&$request->fecha!=null) ? $request->fecha : Carbon::now()->format('Y-m-d');
        $buscar=$request->buscar;
        $query="SELECT  insumo.*,
                        unidad.nombre_unidad unidad,
                        SUM(IFNULL(lote.stock,0)) stock, 
                        nombre_categoria categoria,
                        IFNULL(ROUND(SUM(IFNULL(lote.stock,0)*IFNULL(lote.precio,0))/SUM(IFNULL(lote.stock,0)),3),0) precio_promedio
        FROM insumo
        LEFT JOIN unidad ON unidad.id=insumo.unidad_id
        LEFT JOIN lote ON insumo.id=lote.insumo_id
        LEFT JOIN categoria_insumo ON categoria_insumo.id=insumo.categoria_id
        WHERE CONCAT(insumo.codigo,insumo.nombre_insumo) like '%$buscar%'
        GROUP BY insumo.id
        ORDER BY insumo.id";

        if ($request->has('paginate')) {
            $datos=$this->paginate($query,[],10,$request->page);
        }else{
            $datos=DB::select(DB::raw($query));
            if ($request->has('excel')) {
                return Excel::download(new ReporteStockExports($datos), "Stock $fecha.xlsx");
            }
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

    public function flujo_diario(Request $request){
        $fecha=$request->fecha;
        $data=DB::select(
                DB::raw("(SELECT CONCAT(GA.descripcion,' - ',CG.nombre_categoria) descripcion,
                                    GA.monto 
                        FROM gasto GA
                        INNER JOIN categoria_gasto CG ON CG.id=GA.categoria_id
                        WHERE GA.fecha=?)
                        UNION
                        (SELECT CONCAT(MO.documento,' ',PR.razon_social) descripcion, 
                                        SUM(KA.cantidad*ROUND(KA.precio,3)) monto 
                        FROM movimiento MO
                        INNER JOIN kardex KA ON KA.documento_id=MO.id
                        INNER JOIN proveedor PR ON PR.id=MO.entidad_id
                        WHERE MO.fecha_ingreso=?
                        AND MO.tipo_movimiento='IXC'
                        GROUP BY MO.id)"),
                [
                    $fecha,
                    $fecha
                ]);
        if ($request->has('excel')) {
            return Excel::download(new DataExports($data,'exports.flujo-diario'), "Flujo Diario $fecha.xlsx");
        }
        return response()->json($data);
    }
    public function ingreso_insumos(Request $request){
        $fecha_inicio=$request->fecha_inicio;
        $fecha_fin=$request->fecha_fin;
        $data=DB::select(
                DB::raw("SELECT producto_id,codigo,nombre_insumo,tipo,SUM(cantidad) cantidad_compra, SUM(precio*cantidad) total_compra
                        FROM kardex 
                        INNER JOIN insumo ON insumo.id=kardex.producto_id
                        WHERE (fecha BETWEEN ? AND ?)
                        AND tipo='Ingreso'
                        GROUP BY producto_id,tipo,nombre_insumo,codigo
                        ORDER BY cantidad_compra DESC"),
                [
                    $fecha_inicio,
                    $fecha_fin
                ]);
        if ($request->has('excel')) {
            return Excel::download(new DataExports($data,'exports.ingreso-insumos'), "ingreso-insumos.xlsx");
        }
        return response()->json($data);
    }
    public function salida_insumos(Request $request){
        $fecha_inicio=$request->fecha_inicio;
        $fecha_fin=$request->fecha_fin;
        $data=DB::select(
                DB::raw("SELECT producto_id,codigo,nombre_insumo,tipo,SUM(cantidad) cantidad_consumo, SUM(precio*cantidad) total_consumo 
                        FROM kardex 
                        INNER JOIN insumo ON insumo.id=kardex.producto_id
                        WHERE (fecha BETWEEN ? AND ?)
                        AND tipo='Salida'
                        GROUP BY producto_id,tipo,nombre_insumo,codigo
                        ORDER BY cantidad_consumo DESC"),
                [
                    $fecha_inicio,
                    $fecha_fin
                ]);
        if ($request->has('excel')) {
            return Excel::download(new DataExports($data,'exports.salida-insumos'), "salida-insumos.xlsx");
        }
        return response()->json($data);
    }

    public function resumen_obra(Request $request){
        $obra_id=$request->obra_id;
        $obra=Obra::where('id',$obra_id)->first();
        if ($request->has('resumido')) {
            $insumos=DB::select(
                            DB::raw("SELECT 	'' fecha,
                                                GROUP_CONCAT(CONCAT(movimiento.tipo_movimiento,'-',movimiento.documento)) documento,
                                                insumo.nombre_insumo insumo,
                                                unidad.nombre_unidad unidad,
                                                nombre_categoria categoria,
                                                '' colaborador,
                                                SUM(kardex.cantidad - IFNULL(k_r.cantidad,0)) cantidad,
                                                SUM(ROUND(IF('Ingreso'=kardex.tipo,-1,1)*kardex.precio*(kardex.cantidad-IFNULL(k_r.cantidad,0)),3)) total
                                FROM movimiento 
                                INNER JOIN kardex ON kardex.documento_id=movimiento.id 
                                INNER JOIN insumo ON insumo.id=kardex.producto_id
                                LEFT JOIN unidad ON unidad.id=insumo.unidad_id
                                LEFT JOIN colaborador ON colaborador.id=movimiento.entidad_id
                                LEFT JOIN categoria_insumo ON categoria_insumo.id=insumo.categoria_id
                                LEFT JOIN retorno R ON R.movimiento_id=movimiento.id
                                LEFT JOIN kardex k_r ON k_r.documento_id=R.retorno_id AND k_r.producto_id=kardex.producto_id 
                                WHERE movimiento.obra_id= :id AND movimiento.tipo_movimiento='SXC' 
                                AND (kardex.cantidad!=k_r.cantidad OR k_r.cantidad is NULL)
                                GROUP BY insumo.nombre_insumo,unidad.nombre_unidad,nombre_categoria
                                ORDER BY insumo.id ASC,kardex.fecha ASC
                                "),
                    [
                        "id"    => $obra_id
                    ]);
                    // $gastos=DB::select(
                    //                 DB::raw("SELECT 	'' fecha,
                    //                                     SUM(gasto.monto) monto,
                    //                                     '' descripcion,
                    //                                     categoria_gasto.nombre_categoria categoria
                    //                     FROM gasto 
                    //                     LEFT JOIN categoria_gasto ON categoria_gasto.id=gasto.categoria_id 
                    //                     WHERE gasto.obra_id= :id
                    //                     GROUP BY nombre_categoria"),
                    //         [
                    //             "id"    => $obra_id
                    //         ]);
        }else{
            
            $insumos=DB::select(
                    DB::raw("SELECT 	kardex.fecha,
                                        movimiento.tipo_movimiento,
                                        concat(movimiento.tipo_movimiento,'-',movimiento.documento) documento,
                                        insumo.nombre_insumo insumo,
                                        unidad.nombre_unidad unidad,
                                        nombre_categoria categoria,
                                        CONCAT(colaborador.nombre_colaborador,' ',colaborador.apellido_colaborador) colaborador,
                                        kardex.cantidad - SUM(IFNULL(k_r.cantidad,0)) cantidad,
                                        ROUND(IF('Ingreso'=kardex.tipo,-1,1)*kardex.precio*(kardex.cantidad-SUM(IFNULL(k_r.cantidad,0))),3) total
                        FROM movimiento 
                        INNER JOIN kardex ON kardex.documento_id=movimiento.id 
                        INNER JOIN insumo ON insumo.id=kardex.producto_id
                        LEFT JOIN unidad ON unidad.id=insumo.unidad_id
                        LEFT JOIN colaborador ON colaborador.id=movimiento.entidad_id
                        LEFT JOIN categoria_insumo ON categoria_insumo.id=insumo.categoria_id
                        LEFT JOIN retorno R ON R.movimiento_id=movimiento.id
                        LEFT JOIN kardex k_r ON k_r.documento_id=R.retorno_id AND k_r.producto_id=kardex.producto_id 
                        WHERE movimiento.obra_id= :id AND movimiento.tipo_movimiento='SXC' 
                        AND (kardex.cantidad!=k_r.cantidad OR k_r.cantidad is NULL)
                        ORDER BY insumo.id ASC,kardex.fecha ASC
                        GROUP BY kardex.fecha,
                                movimiento.tipo_movimiento,
                                insumo.nombre_insumo insumo,
                                unidad.nombre_unidad unidad,
                                nombre_categoria categoria,
                                kardex.id
                        "),
            [
                "id"    => $obra_id
            ]);
        }
        $gastos=DB::select(
                        DB::raw("SELECT 	gasto.*,
                                            categoria_gasto.nombre_categoria categoria
                            FROM gasto 
                            LEFT JOIN categoria_gasto ON categoria_gasto.id=gasto.categoria_id 
                            WHERE gasto.obra_id= :id"),
                [
                    "id"    => $obra_id
                ]);
        if ($request->has('pdf')) {
            $resumido=$request->has('resumido') ? true : false;
            $pdf = PDF::loadView('pdf.resumen_obra',compact('obra','insumos','gastos','resumido'));
            return $pdf->download('Rpt Obra - '.$obra->descripcion.'.pdf');
        }
        if ($request->has('excel')) {
            return Excel::download(new DataExports(
                ['obra'=>$obra,'insumos'=>$insumos,'gastos'=>$gastos],'exports.resumen_obra'), 
                'Rpt Obra - '.$obra->descripcion.".xlsx");
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
