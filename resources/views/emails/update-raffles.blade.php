@extends('layouts.email')

@section('content')

<h2 >¡Estimado/a, <span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    Queremos informarle que, debido a motivos de fuerza mayor, hemos tenido que realizar un cambio en la fecha del sorteo de nuestra rifa
     <span style="font-weight: bold;">{{ $data['raffle']->name }}</span>
    realizado actualizaciones.
    <br><br>
    La nueva fecha programada para el sorteo es de acuerdo al siguiente detalle:
    <br> <br>
</p>
<table>
    <thead>
        <tr>
            <th>Título</th> 
            <th>Fecha de sorteo anterior</th>
            <th>Nueva fecha de sorteo</th>
        </tr>
    </thead>
    @foreach ($data['changes'] as $title => $item)
        <tr>
            <td style="font-weight: bold;">
                {{$title}}: 
            </td>
            <td style="color: #D62829; font-weight: bold;">
                {{$item[0]}}
            </td>
            <td style="color: #003049; font-weight: bold;">
                {{$item[1]}}
            </td>
        </tr>
    @endforeach
</table>
<p style="text-align: justify;">
    Todos los demás detalles permanecen sin cambios.
    Lamentamos cualquier inconveniente que esto pueda causar y agradecemos su comprensión y apoyo continuo.
    ¡Gracias por ser parte de nuestra rifa y les deseamos mucha suerte!
    <br>
    <a target="__blank" style="color: #003049; text-decoration: underline;"
    href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a>
</p>

<hr>
<br>
@endsection