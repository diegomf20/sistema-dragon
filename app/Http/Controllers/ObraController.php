<?php

namespace App\Http\Controllers;

use App\Model\Obra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use App\Http\Requests\ObraValidate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class ObraController extends Controller
{
    /**
     * Visualiza todos los Obraes
     */
    public function index(Request $request)
    {
        if ($request->has('pdf')) {
            $obras=Obra::select('obra.*','razon_social')
                            ->leftJoin('cliente','cliente.id','=','obra.cliente_id')
                            ->get();
            $pdf = PDF::loadView('pdf.obra',compact('obras'));
            return $pdf->download('obras.pdf');
        }

        switch ($request->estado) {
            case 'A':
                $obras=Obra::orderBy('fecha_inicio','DESC')->where('estado','A');
                break;
            case 'I':
                $obras=Obra::orderBy('fecha_inicio','DESC')->where('estado','I');
                break;
            default:
                $obras=Obra::orderBy('fecha_inicio','DESC');
                break;
        }

        if ($request->all==true) {
            $obras=$obras->get();
        }else{
            $search=$request->search;
            $obras=$obras->where('titulo','like',"%$search%")->paginate(10);
        }
        return response()->json($obras);
    }

    /**
     * Registra un nuevo Obra
     */
    public function store(ObraValidate $request)
    {
        $obra=new Obra();
        $obra->titulo=$request->titulo;
        $obra->descripcion=$request->descripcion;
        $obra->fecha_inicio=$request->fecha_inicio;
        $obra->direccion=$request->direccion;
        $obra->cliente_id=$request->cliente_id;
        $obra->total=$request->total;
        $obra->estado = 'A';
        $obra->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Obra Registrado."
        ]);
    }

    public function pdf($id,Request $request){
        if($request->hasFile("file")){
            $file=$request->file("file");
            
            if($file->guessExtension()=="pdf"){
                
                
                $nombre = "obra-".$id.".".$file->guessExtension();
                $ruta = public_path("pdf/".$nombre);
                copy($file, $ruta);
                $obra=Obra::where('id',$id)->first();
                $obra->pdf=$nombre;
                $obra->save();
            }else{
                dd("NO ES UN PDF");
            }
        }
    }
    
    /**
     * Visualiza datos de un solo Obra
     */
    public function show($id)
    {
        $obra=Obra::where('id',$id)->first();
        return response()->json($obra);
    }
        
    public function update(ObraValidate $request, $id)
    {
        $obra=Obra::where('id',$id)->first();
        $obra->titulo=$request->titulo;
        $obra->descripcion=$request->descripcion;
        $obra->fecha_inicio=$request->fecha_inicio;
        $obra->direccion=$request->direccion;
        $obra->total=$request->total;
        $obra->cliente_id=$request->cliente_id;
        $obra->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Obra Actualizado."
        ]);
    }

    public function finalizar(Request $request,$id){
        $obra=Obra::where('id',$id)->first();
        $obra->estado="I";
        $obra->fecha_fin=$request->fecha_fin ? $request->fecha_fin : Carbon::now();
        $obra->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Obra Finalizada."
        ]);        
    }
}
