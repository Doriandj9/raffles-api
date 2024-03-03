@extends('layouts.email')

@section('content')

<h2 >Estimado/a ,<span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    ¡Gracias por adquirir un plan de rifas.</span>
    <br>
    A continuación, encontrará los detalles de su compra:
    <br> <br>
</p>
<table>
        <tr>
            <td style="font-weight: bold;">
                Nombre del plan: 
            </td>
            <td style="color: #003049; font-weight: bold; font-style: italic;">
               {{ $data['sub']->title }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Fecha de compra: 
            </td>
            
            <td style="color: #003049; font-weight: bold;">
               {{ $data['startDate'] }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Fecha de expiración del plan:
            </td>
            <td style="color: #003049; font-weight: bold;">
                {{ $data['endDate'] }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Estado del Pago: 
            </td>
            <td style="color: #D62829; font-weight: bold; font-style: italic;">
               Pendiente
            </td>
        </tr>
</table>
<p style="text-align: justify;">
    Recuerde que siempre puedes actualizar tu plan de rifas, 
    <a target="__blank" style="color: #003049; text-decoration: underline;"
    href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a>
</p>

<hr>
<br>
@endsection