<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;
use DB;
use Fpdf;
use Carbon\Carbon;



class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Entrada::with("vehiculo")->where('estado','Activo')->get();
    }
    public function index2()
    {
        return view('entrada');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entrada = new Entrada;
        $entrada->nombre=$request->get('nombre');
        $entrada->celular=$request->get('celular');
        $mytime = Carbon::now('America/Guatemala');
        $entrada->fecha_hora=$mytime->toDateTimeString();
        $entrada->idvehiculo=$request->get('idvehiculo');
        $entrada->idusuario= auth()->id();
        $entrada->estado='Activo';
        $entrada->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Entrada::with("vehiculo","vehiculo.tipo")->where('idVehiculo',$id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrada $entrada)
    {
       $entrada->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy($identrada)
    {
        $entrada=Entrada::findOrFail($identrada);
        $entrada->estado='Inactivo';
        $entrada->update();
    }

    public function reporte(){
        //dd("entro al reporte");
         //Obtenemos los registros
         $registros=DB::table('entrada as e')
            ->join('vehiculos as v','v.idVehiculo','=','e.idVehiculo')
             ->select('e.nombre','e.celular','v.descripcion as vehiculo','v.numeroPlaca','e.fecha_hora')
             ->where('e.estado','=','Activo')
            ->orderBy('e.idEntrada','asc')
            ->get();

         $pdf = new Fpdf();
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Registros de Entrada"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(40,8,utf8_decode("Nombre"),1,"","L",true);
         $pdf::cell(20,8,utf8_decode("Celular"),1,"","L",true);
         $pdf::cell(70,8,utf8_decode("Vehiculo"),1,"","L",true);
         $pdf::cell(25,8,utf8_decode("Numero Placa"),1,"","L",true);
         $pdf::cell(35,8,utf8_decode("fecha_hora"),1,"","L",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(40,6,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(20,6,utf8_decode($reg->celular),1,"","L",true);
            $pdf::cell(70,6,utf8_decode($reg->vehiculo),1,"","L",true);
            $pdf::cell(25,6,utf8_decode($reg->numeroPlaca),1,"","L",true);
            $pdf::cell(35,6,utf8_decode($reg->fecha_hora),1,"","L",true);

            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }
}
