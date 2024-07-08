@extends('layouts.email')

@section('content')

<p>
test
</p>

@endsection

@section('mail', base64_encode($data['user']->email))
