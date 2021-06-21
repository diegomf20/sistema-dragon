<?php

namespace App\Http\Controllers;

use App\Model\Activo;
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

        if ($request->has('pdf')) {
            $activos=Activo::all();
            $pdf = PDF::loadView('pdf.activo',compact('activos'));
            return $pdf->download('activos.pdf');
        }
        
        if ($request->has('excel')) {
            $activos=Activo::all();
            return Excel::download(new ActivoExports($activos), "activos.xlsx");
        }
        $texto_busqueda=explode(" ",$request->search);
        $activos=Activo::select('activo.*','obra.titulo')->leftJoin('obra','obra.id','=','activo.obra_id')
        ->where("nombre_activo","like","%".$texto_busqueda[0]."%");
        
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $activos=$activos->where("nombre_activo","like","%".$texto_busqueda[$i]."%");
        }
        if ($request->all==true) {
            $activos=$activos->where('activo.estado',"0")->get();
        }else{
            $activos=$activos->where('activo.estado',"0")->paginate(8);
        }
        return response()->json($activos);
    }

    /**
     * Registra un nuevo Colaborador
     */
    public function store(ActivoValidate $request)
    {
        $activoBuscar=Activo::where('codigo',$request->codigo)->first();
        if ($activoBuscar!=null) {
            return response()->json([
                "status"=>"ERROR",
                "data"=>"El activo ya fue registrado."
            ]);
        }
        $activo=new Activo();
        $activo->codigo=$request->codigo;
        $activo->nombre_activo=$request->nombre_activo;
        $activo->marca=$request->marca;
        $activo->serie=$request->serie;
        $activo->categoria_id=$request->categoria_id;
        $activo->precio_compra=$request->precio_compra;
        $activo->fecha_compra=$request->fecha_compra;
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
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo Actualizado."
        ]);
    }
    
    public function destroy($id){
        $activo=Activo::where('id',$id)->first();
        $activo->estado='1';
        $activo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo de Baja."
        ]);
    }
    
    public function asignarObra(Request $request, $id){
        $activo=Activo::where('id',$id)->first();
        $activo->obra_id=$request->obra_id;
        $activo->save();
        $movimientoActivo=new MovimientoActivo();
        $movimientoActivo->activo_id=$id;
        $movimientoActivo->obra_id=$request->obra_id;
        $movimientoActivo->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Activo Actualizado."
        ]);
    }

    public function movimiento($id){
        $movimientosActivo=MovimientoActivo::where('activo_id',$id)
                            ->orderBy('id','DESC')
                            ->get();
        return response()->json($movimientosActivo);
    }
}
