@extends('layouts.email')

@section('content')

<p>
Estimado/a ,{{ $data['user']->first_name }} {{ $data['user']->last_name }}! <br>

Resiva un cordial saludo de parte de <strong>HAYU24</strong>, nos entristece informarte que la rifa <strong> {{$data['raffle']->name}} </strong> a 
que estabas suscrito a decido ser cancelada, pero no te preocupes se te devolvera tu dinero de la compra. 
<br>
El organizador de la rifa nos menciona del por qué de su decición.
<br>
Sueña y cree en ti mismo. ¡Adquiere ya un boleto! 
<br><br>

<strong>Contactate:</strong> <span> {{$data['phone']}} </span>

<div class=".ql-container">
    <div class="ql-editor">
        {!! $data['description'] !!}
    </div>
</div>

<br> <br>

@endsection

@section('mail', base64_encode($data['user']->email))
