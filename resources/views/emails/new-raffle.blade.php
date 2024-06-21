@extends('layouts.email')

@section('content')

<p>
Estimado/a ,{{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>

Resiva un cordial saludo de parte de <strong>HAYU24</strong>, nos complace informarte que se ha organizado una nueva rifa con excelentes premios en los cuales tú puedes ser uno de los ganadores. 
<br>
Sueña y cree en ti mismo. ¡Adquiere ya un boleto! 
<br>
A continuación, los detalles:

<ul style="list-style: circle;">
    <li>
        Nombre de la rifa: {{ $data['raffle']->name }}
    </li>
    <li>
        Organizador: {{ $data['raffle']->user->first_name}} {{ $data['raffle']->user->last_name }}
    </li>
    <li>
        Fecha del sorteo: {{ $data['raffle']->draw_date }}
    </li>
    <li>
        Premios
        <ul>
            @foreach ($data['awards'] as $award)
                <li>
                   <strong>{{$award->title}}:</strong> {{$award->description}}
                </li>
            @endforeach
        </ul>
    </li>
</ul>

<p style="text-align: justify;">
    <a target="__blank" style="color: #003049; text-decoration: underline;"
    href="{{ env('APP_URL_FRONT') }}/{{$data['url']}}"> Ingresar a la rifa </a>
</p>




@endsection