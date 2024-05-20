@extends('layouts.email')

@section('content')

<h2 >¡Estimado/a, <span style="font-weight: bold;"> {{ $data['user']->first_name }} {{ $data['user']->last_name }}! </span> </h2>
<br> <br>
<p style="padding: 0px;">
    Queremos informarle que, que la rifa <span style="font-weight: bold;">{{ $data['raffle']->name }}</span>
    va a dar comienzo con el sorteo promgramado en la fecha <strong>{{ $data['raffle']->draw_date }}</strong>.
    <br><br>
     Para poder vizualizar el en vivo ingresando en el siguiente link: 
    <br> <br>

</p>
<p class="text-center" style="margin-bottom: 25px;">
    <a target="__blank" class="text-4xl" style="color: #003049; text-decoration: underline;"
    href="{{$data['raffle']->summary}}">Ver en vivo.</a>
</p>
<hr>
<br>
@endsection