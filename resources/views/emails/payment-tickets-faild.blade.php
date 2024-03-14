@extends('layouts.email')

@section('content')

<h2 >Estimado/a ,<span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    ¡Gracias por su compra de la rifa <span style="font-weight: bold;">"{{ $data['tickets'][0]->raffle->name }}"</span>
    <br>
    Lamentamos los inconvenientes que estás experimentando con la fase de autenticación del comprobante de pago de tus boletos. <br>
    Ten en cuenta la siguiente observación.<br>
    <p class="font-bold italic text-xl">
 {{ $data['observation'] }} <br>
</p> <br>
    A continuación, encontrará los detalles de su compra:
    <br> <br>
</p>
<table>
        <tr>
            <td style="font-weight: bold;">
                Números de Boletos: 
            </td>
            <td style="color: #003049; font-weight: bold; font-style: italic;">
                @php 
                $tickets = '';
                foreach($data['tickets'] as $item){
                    $tickets .= $item->order . ',';
                }
                $tickets = rtrim($tickets,',');
                @endphp
                {{ $tickets }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Fecha de Comprobación de compra: 
            </td>
            
            <td style="color: #003049; font-weight: bold;">
               {{ $data['tickets'][0]->updated_at }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Cantidad de Boletos: 
            </td>
            <td style="color: #003049; font-weight: bold;">
               {{ count($data['tickets']) }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Fecha y hora de rifa: 
            </td>
            <td style="color: #003049; font-weight: bold;">
               {{ $data['tickets'][0]->raffle->draw_date }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Estado del Pago: 
            </td>
            <td style="color: #D62829; font-weight: bold; font-style: italic;">
               Negado
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