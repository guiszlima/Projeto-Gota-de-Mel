@extends('layouts.main')

@section('content')
@livewireStyles

{{-- Passando a variável sell corretamente para o componente Livewire --}}

@livewire('pay-no-integration', ['sell' => $sell])




@endsection