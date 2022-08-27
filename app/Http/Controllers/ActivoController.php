<?php

namespace App\Http\Controllers;

use App\Model\Activo;
use App\Model\CategoriaActivo;
use App\Model\MovActivo;
use App\Model\DetMovActivo;
use App\Model\MovimientoActivo;
use App\Exports\ActivoExports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\ActivoValidate;
use App\Http\Requests\ActivoUpdateValidate;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class ActivoController extends Controller
{
    /**
     * Visualiza todos los Colaboradores
     */
    public function index(Request $request)
    {
        
        /**
         * Variables
         */
        $estado = $request->estado | "A" ;
        
        /**
         * Reportes
         */
        if ($request->has('pdf')) {
            $activos=Activo::leftJoin('obra','obra.id','=','activo.obra_id')
                            ->leftJoin('categoria_activo','categoria_activo.id','=','activo.categoria_id')
                            ->select('activo.*','obra.titulo as obra','categoria_activo.nombre_categoria as categoria')
                            ->where('activo.estado',$estado)
                            ->get();
            $pdf = PDF::loadView('pdf.activo',compact('activos'));
            return $pdf->download('activos.pdf');
        }
        
        if ($request->has('excel')) {
            $activos=Activo::leftJoin('obra','obra.id','=','activo.obra_id')
                            ->leftJoin('categoria_activo','categoria_activo.id','=','activo.categoria_id')
                            ->select('activo.*','obra.titulo as obra','categoria_activo.nombre_categoria as categoria')
                            ->where('activo.estado',$estado)
                            ->get();
            $tituloEstado=($estado=='A') ? 'En Uso': 'De Baja';
            return Excel::download(new ActivoExports($activos), "Activos $tituloEstado.xlsx");
        }

        /**
         * Listar JSON
         */
        $texto_busqueda=explode(" ",$request->search);
        $activos=Activo::select('activo.*','obra.titulo','categoria_activo.nombre_categoria')
            ->leftJoin('obra','obra.id','=','activo.obra_id')
            ->leftJoin('categoria_activo','categoria_activo.id','=','activo.categoria_id')
            ->where(DB::raw("CONCAT(nombre_activo,' ',nombre_categoria)"),"like","%".$texto_busqueda[0]."%");
        
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $activos=$activos->where("nombre_activo","like","%".$texto_busqueda[$i]."%");
        }
        if ($request->all==true) {
            $activos=$activos->where('activo.estado',$estado)->get();
        }else{
            $activos=$activos->where('activo.estado',$estado)->paginate(8);
        }
        return response()->json($activos);
    }

    /**
     * Registra un nuevo Colaborador
     */
    public function store(ActivoValidate $request)
    {
        $categoriaActivo=CategoriaActivo::where('id',$request->categoria_id)->first();
        $cantidad=Activo::where('categoria_id',$request->categoria_id)->count();
        // dd($cantidad);
        $activo=new Activo();
        $activo->codigo=$categoriaActivo->codigo.'-'.str_pad($cantidad+1, 3, "0", STR_PAD_LEFT);
        $activo->nombre_activo=$request->nombre_activo;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        $activo->categoria_id=$request->categoria_id;
        $activo->precio_compra=$request->precio_compra;
        $activo->fecha_compra=$request->fecha_compra;
        $activo->contable=$request->contable;
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo Registrado."
        ]);
    }
    
    /**
     * Visualiza datos de un solo Colaborador
     */
    public function show($id)
    {
        $activo=Activo::with('movimientos')->where('id',$id)->first();
        return response()->json($activo);
    }
        
    public function update(ActivoUpdateValidate $request, $id)
    {
        $activoBuscar=Activo::where('codigo',$request->codigo)
                            ->where('id','<>',$id)    
                            ->first();
        if ($activoBuscar!=null) {
            return response()->json([
                "status"=>"ERROR",
                "data"=>"Código usado en otro activo."
            ]);
        }
        $activo=Activo::where('id',$id)->first();
        $activo->codigo=$request->codigo;
        $activo->nombre_activo=$request->nombre_activo;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        $activo->categoria_id=$request->categoria_id;
        $activo->precio_compra=$request->precio_compra;
        $activo->fecha_compra=$request->fecha_compra;
        $activo->contable=$request->contable;
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo Actualizado."
        ]);
    }
    
    public function destroy($id){
        $activo=Activo::where('id',$id)->first();
        $activo->estado='I';
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo de Baja."
        ]);
    }
    
    public function regresar(Request $request, $id){
        $activo=Activo::where('id',$id)->first();
        $activo->obra_id=null;
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo en Almacen."
        ]);
    }

    public function listarMovimiento(Request $request){
        $movs=MovActivo::join('obra','obra.id','=','mov_activo.obra_id')
                            ->join('colaborador','colaborador.id','=','mov_activo.colaborador_id')
                            ->select('created_at as fecha','mov_activo.id','obra.titulo as obra',DB::raw("CONCAT(colaborador.nombre_colaborador,' ',colaborador.apellido_colaborador) as colaborador"))
                            ->orderBy('mov_activo.id','DESC')
                            ->paginate(10);
        return response()->json($movs);
    }
    
    public function listarDetMovimiento($id){
        $movs=DetMovActivo::join('activo','activo.id','=','det_mov_activo.activo_id')
                            ->select('activo.codigo','activo.nombre_activo as activo')
                            ->where('mov_activo_id',$id)
                            ->get();
        return response()->json($movs);    
    }

    public function movimiento(Request $request){
        if (count($request->items)==0) {
            return response()->json([
                "status"=> "ERROR",
                "data"  => "No cuenta con items."    
            ]);
        }
        $movActivo=new MovActivo();
        $movActivo->obra_id=$request->obra_id;
        $movActivo->colaborador_id=$request->colaborador_id;
        $movActivo->save();
        

        for ($i=0; $i < count($request->items); $i++) { 
            $item=$request->items[$i];
            
            $detMovActivo=new DetMovActivo();
            $detMovActivo->activo_id=$item["id"];
            $detMovActivo->mov_activo_id=$movActivo->id;
            $detMovActivo->save();
            $activo=Activo::find($item["id"]);
            $activo->obra_id=$request->obra_id;
            $activo->save();
        }


        return response()->json([
            "status"=> "OK",
            "data"  => "Movimiento de Activo Registrado."
        ]);
    }
}
