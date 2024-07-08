@extends('layouts.email')

@section('content')

<p>
¡Enhorabuena {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>
Has solicitado un cambio de correo electronico, el cual se ha completado correctamente. <br>
¡ Bienvenido a <strong>HAYU24</strong> !
<br> <br>
<a class="text-xl font-bold italic" target="__blank" href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a> 

@endsection

@section('mail', base64_encode($data['user']->email))
