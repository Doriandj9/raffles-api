@extends('layouts.email')

@section('content')

<p>
Hola {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>
Lamentamos los inconvenientes que estás experimentando con la fase de autenticación del comprobante de pago de tu suscripción.
Para poder completar el tu plan, ten en cuenta la siguiente observación.
<br> <br>
<p class="font-bold italic text-xl">
 {{ $data['observation'] }} <br>
 Ingrese al sistema
 <a class="text-xl font-bold italic" target="__blank" href="{{ env('APP_URL_FRONT') }}/security/login">Loguearse. </a> 
</p>


@endsection