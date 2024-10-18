@extends('layouts.main')

@section('content')
@livewireStyles



@livewire('pay-no-integration',['sell' => $sell])

@livewireScripts

@endsection