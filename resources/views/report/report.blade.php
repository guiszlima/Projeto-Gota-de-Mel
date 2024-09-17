@extends('layouts.main')

@section('content')
<x-button-back :route="route('stock.index')"></x-button-back>
@livewire('report-view')

@endsection