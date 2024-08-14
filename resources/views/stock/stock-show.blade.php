@extends("layouts.main")

@section('content')
<div class="flex items-center justify-center h-screen">
    <div class="w-[80vw] h-[80vh] bg-slate-200 py-[5%] px-[10%]">
        @dd($product)
        <div class="flex justify-end mr-5 ">
            <button>Editar</button>
        </div>
        <div>
            <img src="{{ $product->images[0]->src }}" alt="">
        </div>

    </div>
</div>
@endsection