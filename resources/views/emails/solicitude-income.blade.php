@extends('layouts.email')

@section('content')

@php

    function returnState($state, $observation) {
        
        $template = ['DR' => [
                'message' => 'Se le notifica que se ha realizado un solicitud de retiro de ingresos de ventas de boletos.',
                'state' => 'Pendiente',
                'bg' => '#D62829'
            ],'AC' => [
                'message' => 'Se completado correctamente su solictud de retiro de ingresos',
                'state' => 'Completado',
                'bg' => '#003049'
            ], 'CL' => [
                'message' => 'Lamentamos los inconvenientes, tu solicitud a sido cancelada ten en cuenta la siguiente observación: <br > ' .  $observation,
                'state' => 'Cancelado',
                'bg' => '#D62829'
            ]];
        
        return $template[$state];
    }

   $info = returnState($data['request_income']->status, $data['observation'] ?? 'N/A');

@endphp

<h2 >Estimado/a ,<span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    {!! $info['message'] !!}
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
               {{ $data['request_income']->created_at }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Monto de retiro: 
            </td>
            
            <td style="color: #003049; font-weight: bold;">
               ${{ $data['request_income']->amount }}
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">
                Estado de la solicitud:
            </td>
            <td style="color: {{$info['bg']}}; font-weight: bold; font-style: italic;">
               {{ $info['state'] }}
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

@section('mail', base64_encode($data['user']->email))
