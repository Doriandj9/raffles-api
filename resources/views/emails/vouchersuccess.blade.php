@extends('layouts.email')

@section('content')

<p>
¡Enhorabuena {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>
Has completado con éxito el proceso de autenticación del comprobante de pago. 
¡Bienvenido al sistema! Ahora tienes acceso a todas las funciones y características disponibles para crear rifas. 
Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos. ¡Disfruta de tu experiencia en nuestra plataforma y gracias por confiar en nosotros!
<a class="text-xl font-bold italic" target="__blank" href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a> 
<br> Saludos, <br>
El Equipo de HAYU 24
</p>

@endsection