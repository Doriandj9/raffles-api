@extends('layouts.email')

@section('content')

<p>
¡Enhorabuena {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>
Te damos una cálida bienvenida a nuestra comunidad. ¡Tu registro ha sido un éxito! 🎉 <br>

Estamos emocionados de tenerte a bordo y esperamos que disfrutes de todas las increíbles experiencias que nuestra plataforma tiene para ofrecer. Ahora formas parte de una comunidad apasionada y dedicada. <br>

Recuerda que estamos aquí para ayudarte en cada paso del camino. Si tienes alguna pregunta, comentario o simplemente quieres compartir tus pensamientos, no dudes en ponerte en contacto con nosotros. <br>

<span class="font-bold"> Por favor completa el registro ingresando en el siguiente link 
    <a target="__blank" href="{{ env('APP_URL_FRONT') }}/{{ $data['url'] }}"> Completar registro </a> 
</span>
¡Gracias por unirte a nosotros en este emocionante viaje! <br>
<br>


@endsection

@section('mail', base64_encode($data['user']->email))
