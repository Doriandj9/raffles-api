@extends('layouts.email')

@section('content')

<p>
¡Enhorabuena {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>
Has solicitado una restauración de contraseña, la cual se ha completado correctamente. <br>
Ten en cuenta que debes actualizarla dentro de la plataforma para mejorar la seguridad. <br>
Datos: <br><br>

Contraseña temporal:<span style="color: #003049;">{{trim($data['password'])}}</span><br>

<br><br>

<a class="text-xl font-bold italic" target="__blank" href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a> 


@endsection