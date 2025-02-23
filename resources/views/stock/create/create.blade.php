@extends('layouts.main')

@section('content')
<x-button-back :route="route('menu')"></x-button-back>

@livewire('criar-produto')

@endsection
