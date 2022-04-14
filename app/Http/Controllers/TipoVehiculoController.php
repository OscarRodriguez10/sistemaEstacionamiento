<?php

namespace App\Http\Controllers;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;
use DB;
use Fpdf;


class TipoVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return tipovehiculo::where('estado','Activo')->get();
    }
     public function index2()
    {
        return view('tipoVehiculo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $tipoVehiculo = new TipoVehiculo;
        $tipoVehiculo->descripcion=$request->get('descripcion');
        $tipoVehiculo->costo=floatval($request->get('costo'));
        $tipoVehiculo->estado='Activo';
        $tipoVehiculo->save();
      

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoVehiculo  $tipoVehiculo
     * @return \Illuminate\Http\Response
     */
    public function show(TipoVehiculo $tipoVehiculo)
    {
        return $tipoVehiculo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoVehiculo  $tipoVehiculo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoVehiculo $tipoVehiculo)
    {
        $tipoVehiculo->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoVehiculo  $tipoVehiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoVehiculo $tipoVehiculo)
    {
        $tipoVehiculo=Vehiculo::findOrFail($idvehiculo);
        $tipoVehiculo->estado='Inactivo';
        $tipoVehiculo->update();
    }
     public function reporte(){
        //dd("entro al reporte");
         //Obtenemos los registros
         $registros=DB::table('tipovehiculo')
            ->where ('estado','=','Activo')
            ->orderBy('idTipoVehiculo','asc')
            ->get();

         $pdf = new Fpdf();
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Tipo Vehiculo"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(70,8,utf8_decode("Descripción"),1,"","L",true);
         $pdf::cell(70,8,utf8_decode("Costo"),1,"","L",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(70,6,utf8_decode($reg->descripcion),1,"","L",true);
            $pdf::cell(70,6,utf8_decode($reg->costo),1,"","L",true);
            $pdf::Ln(); 
         }

         $pdf::Output();
         exit;
    }

}
