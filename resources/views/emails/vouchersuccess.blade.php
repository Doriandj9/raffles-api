@extends('layouts.email')

@section('content')

<p>
¡Enhorabuena {{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>
Has completado con éxito el proceso de autenticación del comprobante de pago. 
¡Bienvenido al sistema! Ahora tienes acceso a todas las funciones y características disponibles para crear rifas. 
Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos. ¡Disfruta de tu experiencia en nuestra plataforma y gracias por confiar en nosotros!
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
               {{ str_replace('-','/',$data['startDate']) }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Fecha de expiración del plan:
            </td>
            <td style="color: #003049; font-weight: bold;">
                {{ str_replace('-','/',$data['endDate']) }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Estado del Pago: 
            </td>
            <td style="color: #003049; font-weight: bold; font-style: italic;">
               Pagado
            </td>
        </tr>
</table>

<br><br>

<a class="text-xl font-bold italic" target="__blank" href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a> 


@endsection