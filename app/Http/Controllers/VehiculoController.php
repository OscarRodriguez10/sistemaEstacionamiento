<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;

use DB;
use Fpdf;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return vehiculo::with("tipo")->where('estado','Activo')->get();
    }
    public function index2()
    {
        return view('vehiculo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vehiculo = new Vehiculo;
        $vehiculo->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(Vehiculo $vehiculo)
    {
        return $vehiculo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $vehiculo->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehiculo  $vehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy($idvehiculo)
    {
        $vehiculo=Vehiculo::findOrFail($idvehiculo);
        $vehiculo->estado='Inactivo';
        $vehiculo->update();
    }

     public function reporte(){
        //dd("entro al reporte");
         //Obtenemos los registros
         $registros=DB::table('vehiculos as v')
            ->join('tipovehiculo as tv','v.idTipoVehiculo','=','tv.idTipoVehiculo')
             ->select('v.descripcion','v.numeroPlaca','tv.descripcion as nombre')
             ->where('v.estado','=','Activo')
            ->orderBy('idVehiculo','asc')
            ->get();

         $pdf = new Fpdf();
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Vehiculo"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(50,8,utf8_decode("Descripción"),1,"","L",true);
         $pdf::cell(70,8,utf8_decode("NumeroPlaca"),1,"","L",true);
         $pdf::cell(70,8,utf8_decode("Tipo"),1,"","L",true);
         

         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(50,6,utf8_decode($reg->descripcion),1,"","L",true);
            $pdf::cell(70,6,utf8_decode($reg->numeroPlaca),1,"","L",true);
            $pdf::cell(70,6,utf8_decode($reg->nombre),1,"","L",true);

            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }

}
