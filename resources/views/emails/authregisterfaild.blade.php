@extends('layouts.email')

@section('content')

<p>
Hola {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>
Lamentamos los inconvenientes que estás experimentando con la fase de autenticación en nuestro sistema.
Para poder completar el registro, ten en cuenta la siguiente observación.
<br> <br>
<p class="font-bold italic text-xl">
 {{ $data['observation'] }} <br>
 Ingrese en el siguiente link para completar el proceso
 <a class="text-xl font-bold italic" target="__blank" href="{{ env('APP_URL_FRONT') }}/security/login">Actualizar datos. </a> 
</p>

</p>

@endsection

@section('mail', base64_encode($data['user']->email))
