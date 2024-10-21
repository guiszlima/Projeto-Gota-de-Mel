@extends('layouts.main')

@section('content')
@livewireStyles
<x-button-back :route="route('menu')"></x-button-back>
<livewire:mostrar-produtos />





@endsection