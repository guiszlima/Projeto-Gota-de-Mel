@extends('layouts.main')

@section('content')
<x-button-back :route="route('menu')"></x-button-back>
@livewire('report-view-sells')

@endsection