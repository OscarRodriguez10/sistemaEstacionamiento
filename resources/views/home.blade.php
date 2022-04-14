@extends('layouts.app')
@section('content')
<div class="container">
<a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('Inicio', 'Inicio') }}</a>

<a class="navbar-brand" href="{{ url('/vehiculo') }}">
                    {{ config('Registro de Vehiculo', 'Vehiculo') }}</a>
<a class="navbar-brand" href="{{ url('/tipoVehiculo') }}">
    {{ config('Tipo Vehiculo', 'Tipo de Vehiculo') }}</a>
    <a class="navbar-brand" href="{{ url('/entrada') }}">
    {{ config('Registro Entrada', 'Entrada') }}</a>
    <a class="navbar-brand" href="{{ url('/salida') }}">
    {{ config('Registro Salida', 'Salida') }}</a>
</div>
@endsection
