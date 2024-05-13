@extends('layouts.email')

@section('content')

<h2 >¡Estimado/a, <span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    Muchas felicidades usted es uno de los ganadores de la rifa <strong>{{ $data['raffle']->name }}</strong> que ha culminado con el sorteo realizado en la fecha: <strong> {{$data['raffle']->draw_date}} </strong> <br>
    A continuación describimos los detalles:
    <br> <br>
</p>
<table>
    <thead>
        <tr>
            <th>Nombre del ganador</th> 
            <th>Lugar del premio</th>
            <th>Descripción</th>
        </tr>
    </thead>
        <tr>
            <td style="font-weight: bold;">
                {{ $data['award']->winner->user->first_name }} {{$data['award']->winner->user->last_name}}
            </td>
            <td style="color: #003049; font-weight: bold;">
                {{$data['award']->description->title}}
            </td>
            <td style="color: #003049; font-weight: bold;">
                {{$data['award']->description->description}}
            </td>
        </tr>
</table>
<p style="text-align: justify;">
    ¡Gracias por ser parte de nuestra rifa y les deseamos mucha suerte para la próxima vez!
    <br>
    <a target="__blank" style="color: #003049; text-decoration: underline;"
    href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a>
</p>

<hr>
<br>
@endsection