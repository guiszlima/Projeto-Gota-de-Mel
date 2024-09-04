@extends('layouts.main')

@section('content')

@livewireStyles
@if (!session('warn'))
@livewire('fazer-produto-variante')

@else
@livewire('fazer-produto-variante',['report' => session('warn')])
@endif

@livewireScripts

@endsection