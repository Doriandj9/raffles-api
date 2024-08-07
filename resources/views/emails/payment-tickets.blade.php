@extends('layouts.email')

@section('content')

<h2 >Estimado/a ,<span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    ¡Gracias por su compra de la rifa <span style="font-weight: bold;">"{{ $data['raffle']->name }}"</span>
    <br>
    @if (empty($data['seller']))
    Nos complace confirmar que su compra ha sido procesada con éxito. <br>
    La validación del comprobante de pago de sus boletos se vera reflejado en un tiempo máximo de 24 horas. <br>
    @endif
    A continuación, encontrará los detalles de sus boletos adquiridos:
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
                Fecha de Compra: 
            </td>
            <td style="color: #003049; font-weight: bold;">
               {{ $data['tickets'][0]->created_at }}
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
                Precio Total: 
            </td>
            <td style="color: #003049; font-weight: bold; font-style: italic;">
               ${{ $data['receipt']->total }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Estado del Pago: 
            </td>
            <td style="color: {{ empty($data['seller']) ? '#D62829' : '#003049' }}; font-weight: bold; font-style: italic;">
               {{ empty($data['seller']) ? 'Pendiente' : 'Pagado'  }} 
            </td>
        </tr>
</table>
<p style="text-align: justify;">
    Recuerde que puede ingresar a la plataforma y consultar sus boletos en formato 
    visual, en el aparto <span style="font-weight: bold;"> Mis boletos </span>
    <a target="__blank" style="color: #003049; text-decoration: underline;"
    href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a>
</p>

<hr>
<br>
@endsection

@section('mail', base64_encode($data['user']->email))
