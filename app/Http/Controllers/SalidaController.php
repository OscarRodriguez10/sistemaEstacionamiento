<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use Illuminate\Http\Request;
use DB;
use Fpdf;
use Carbon\Carbon;


class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return Salida::with("vehiculo")->where('estado','Cancelado')->get();
    }
    public function index2()
    {
        return view('salida');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $salida = new Salida;
        $salida->idvehiculo=$request->get('idvehiculo');
        $mytime = Carbon::now('America/Guatemala');
        $salida->fecha_salida=$mytime->toDateTimeString();
        $salida->fecha_entrada=$request->get('fecha_entrada');
        $salida->duracion=$request->get('duracion');
        $salida->cobro=$request->get('cobro');
        $salida->idusuario= auth()->id();
        $salida->identrada= $request->get('idEntrada');
        $salida->estado='Cancelado';
        $salida->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function show(Salida $salida)
    {
        return $salida;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salida $salida)
    {
        $salida->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function destroy($idSalida)
    {
        $salida=Salida::findOrFail($idSalida);
        $salida->estado='Inactivo';
        $salida->update();
    }

    public function reporte(){
        //dd("entro al reporte");
         //Obtenemos los registros
         $registros=DB::table('salida as s')
             ->join('vehiculos as v','v.idVehiculo','=','s.idVehiculo')
             ->select('v.numeroPlaca','s.duracion','s.cobro')
             ->where('s.estado','=','Cancelado')
            ->orderBy('idSalida','asc')
            ->get();

         $pdf = new Fpdf();
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Registros de Salidas"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(50,8,utf8_decode("Num. Placa"),1,"","L",true);
         $pdf::cell(70,8,utf8_decode("Tiempo Estacionado(min.)"),1,"","L",true);
         $pdf::cell(70,8,utf8_decode("Cantidad a pagar"),1,"","L",true);
         

         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(50,6,utf8_decode($reg->numeroPlaca),1,"","L",true);
            $pdf::cell(70,6,utf8_decode($reg->duracion),1,"","L",true);
            $pdf::cell(70,6,utf8_decode($reg->cobro),1,"","L",true);
            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }
}
