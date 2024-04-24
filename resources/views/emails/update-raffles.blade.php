@extends('layouts.email')

@section('content')

<h2 >Estimado/a ,<span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    Nos complace informarte que tu rifa adquirida <span style="font-weight: bold;">"{{ $data['raffle']->name }}"</span>
    realizado actualizaciones.
    <br><br>
    A continuación, encontrará los detalles:
    <br> <br>
</p>
<table>
    <thead>
        <tr>
            <th>Título</th>
            <th>Información anterior</th>
            <th>Nueva información</th>
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
    <a target="__blank" style="color: #003049; text-decoration: underline;"
    href="{{ env('APP_URL_FRONT') }}/security/login"> Ingresar al sistema </a>
</p>

<hr>
<br>
@endsection