@extends('layouts.email')

@section('content')

<h2 >¡Estimado/a, <span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    Queremos informarle que se ha culminado la rifa <strong>{{ $data['raffle']->name }}</strong> con el sorteo realizado en la fecha: <strong> {{$data['raffle']->draw_date}} </strong> <br>
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
    @foreach ($data['winners'] as $item)
        <tr>
            <td style="font-weight: bold;">
                {{ $item->winner->user->first_name }} {{$item->winner->user->last_name}}
            </td>
            <td style="color: #003049; font-weight: bold;">
                {{$item->description->title}}
            </td>
            <td style="color: #003049; font-weight: bold;">
                {{$item->description->description}}
            </td>
        </tr>
    @endforeach
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