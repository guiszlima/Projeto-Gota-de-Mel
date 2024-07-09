@props(['titulo','image','alt','img_title','routeName'])
<div id="whoobe-3fery" class="w-full md:w-64 justify-center items-center bg-white shadow-lg rounded-lg flex flex-col">
        <img src="{{$image??''}}" alt="{{$alt??''}}" title="{{$img_title??''}}" class="w-full h-auto object-cover rounded-t-lg" id="whoobe-ixxe5">
        <div id="whoobe-1okdg" class="w-full p-4 justify-start flex flex-col">
            <h4 class="border-b-2 text-3xl text-center" id="whoobe-3mr7n">{{$titulo}}</h4>
            <a href="{{route($routeName)}}" value="button" class="my-4 px-4 py-2 text-white hover:bg-blue-700 bg-blue-500 text-center" >Ir para página</a>
        </div>
    </div>