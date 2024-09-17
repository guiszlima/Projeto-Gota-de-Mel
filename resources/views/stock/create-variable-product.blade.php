@extends('layouts.main')

@section('content')
<x-button-back :route="route('stock.index')"></x-button-back>
@livewireStyles
@if (!session('warn'))
@livewire('fazer-produto-variante')

@else
@livewire('fazer-produto-variante',['report' => session('warn')])
@endif

@livewireScripts

@endsection