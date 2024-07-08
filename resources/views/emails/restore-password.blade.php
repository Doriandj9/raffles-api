@extends('layouts.email')

@section('content')

<p>
Estimado/a ,{{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>

Queremos informarte que se ha solicitado un cambio de contraseña para tu cuenta. <br>
Este es un proceso estándar de seguridad diseñado para proteger tu información personal.
<br> <br>

Si no has solicitado este cambio o consideras que ha sido realizado por error, por favor contáctanos de inmediato para que podamos tomar las medidas necesarias.
<br>

<span class="font-bold"> Por favor ingresa en el siguiente link para restablecer tu contraseña
    <a target="__blank" href="{{ env('APP_URL_FRONT') }}/{{ $data['url'] }}"> Completar registro </a> 
</span>
¡Gracias por unirte a nosotros en este emocionante viaje! <br>
<br>


@endsection

@section('mail', base64_encode($data['user']->email))
