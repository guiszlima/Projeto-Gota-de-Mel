@extends('layouts.main')

@section('content')
@php

$response = $response ?? ""

@endphp

<x-button-back :route="route('menu')"></x-button-back>
@if($response)

@livewire('criar-produto', ['response' => $response])
@else
@livewire('criar-produto')
@endif
@endsection
