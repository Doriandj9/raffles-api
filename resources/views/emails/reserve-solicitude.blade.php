@extends('layouts.email')

@section('content')

<h2 >Estimado/a ,<span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    ¡Gracias enviar tu solicitud de afiliación.
    <br>
</p> <br>
    A continuación, encontrarás los detalles:
    <br> <br>
</p>
<table>
        <tr>
            <td style="font-weight: bold;">
                Nombre de la rifa: 
            </td>
            <td style="color: #003049; font-weight: bold; font-style: italic;">
                {{ $data['raffle']->name }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Fecha de solicitud: 
            </td>
            
            <td style="color: #003049; font-weight: bold;">
               {{ $data['commission']->created_at }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Estado de la solicitud:
            </td>
            <td style="color: #D62829; font-weight: bold; font-style: italic;">
               Pendiente
            </td>
        </tr>
</table>
<br>
<p style="text-align: justify;">
    <a target="__blank" style="color: #003049; text-decoration: underline;"
    href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a>
</p>

<hr>
<br>
@endsection