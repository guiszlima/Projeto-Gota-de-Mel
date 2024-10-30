@extends('layouts.main')

@section('content')
@livewireStyles

{{-- Passando a variável sell corretamente para o componente Livewire --}}
@if ($sell)
@livewire('pay-no-integration', ['sell' => $sell])
@else
<h1>Não há nenhuma Venda Solicitada</h1>
@endif



@endsection