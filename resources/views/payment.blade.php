@extends('layouts.main')

@section('content')
@livewireStyles

{{-- Passando a variável sell corretamente para o componente Livewire --}}
@if ($sell)
@livewire('pay-no-integration', ['sell' => $sell])
@else

@endif



@endsection