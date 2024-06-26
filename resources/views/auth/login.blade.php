<div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
</div>
@extends('layouts.guest')
@section('content')

<h1>Login:</h1>
@if (session('message'))
    <h1>{{session('message')}}</h1>

@endif





@endsection