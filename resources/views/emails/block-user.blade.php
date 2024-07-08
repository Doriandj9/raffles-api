@extends('layouts.email')

@section('content')

<p>
!Estimado/a {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>

{!!
$data['active'] ? 'Se le ha vetado de la plataforma por mala conducta u otras circunstancias.<br><br>
Si tiene alguna obsevación comuníquese con los administradores de la plataforma
en la sección inferior se encuentran los contactos.'
:
'Felicidades te encuetras nuevamente habilitado para utilizar nuestra plataforma inovemos juntos'
!!}



<br>



@endsection

@section('mail', base64_encode($data['user']->email))
