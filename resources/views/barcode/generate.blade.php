@extends('layouts.main')

@section('content')
<div  class="printable" id='printable_div_id'>
@foreach ($product as $items )
    {{$items}}
@endforeach


</div>
<button onClick="printdiv('printable_div_id');">
    Imprimir
</button>

<script>
function printdiv(elem) {
  
  let new_str = document.getElementById(elem).innerHTML;
  let old_str = document.body.innerHTML;
  document.body.innerHTML = new_str
  window.print();
  document.body.innerHTML = old_str;
  return false;
}
</script>
@endsection