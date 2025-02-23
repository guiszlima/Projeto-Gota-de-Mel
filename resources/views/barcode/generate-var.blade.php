@extends('layouts.main')

@section('content')

<x-button-back :route="route('barcode.index')"></x-button-back>

<div class="text-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{$parent_name}}</h1>
</div>

<div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($variations as $variation)
    <div class="border rounded-lg shadow-lg p-4 bg-white">
        <h2 class="text-xl font-semibold text-center text-gray-700 mb-4">{{$variation->name}}</h2>
        
        <!-- SVG escondido para código de barras -->
        <svg class="hidden" id="codBarras-{{$variation->id}}">{{$variation->sku}}</svg>
        
        <!-- Exibição do código de barras -->
        <img id="barcodeImage-{{$variation->id}}" class="mx-auto my-4 w-1/2" alt="Código de Barras">
        
        <!-- Botão para baixar -->
        <div class="text-center">
            <button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition"
                onclick="convertSvgToPng('codBarras-{{$variation->id}}', '{{$variation->name}}', true)">
                Baixar Código de Barras
            </button>
        </div>
    </div>
    @endforeach
</div>

<!-- Canvas escondido -->
<canvas id="canvas" style="display: none;"></canvas>

<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function GerarCódigoDeBarras() {
        @foreach ($variations as $variation)
            (() => { 
                let elementId = 'codBarras-{{$variation->id}}';
                let sku = "{{$variation->sku}}";
                let price = "{{$variation->price}}";

                if (!sku) {
                    sku = "000000";
                }

                JsBarcode('#' + elementId, sku, {
                    text: price,
                    displayValue: true
                });

                convertSvgToPng(elementId, '{{$variation->name}}', false);
            })();
        @endforeach
    }

    // Gerar códigos de barras ao carregar a página
    GerarCódigoDeBarras();
});

// Definir convertSvgToPng globalmente
function convertSvgToPng(svgId, productName, download = true) {
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    const svgElement = document.getElementById(svgId);
    if (!svgElement) return;

    const svgString = new XMLSerializer().serializeToString(svgElement);
    const img = new Image();
    
    img.onload = function() {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);
        const pngUrl = canvas.toDataURL('image/png');
        document.getElementById('barcodeImage-' + svgId.split('-')[1]).src = pngUrl;

        if (download) {
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = productName + '.png';
            downloadLink.click();
        }
    };
    img.src = 'data:image/svg+xml;base64,' + btoa(svgString);
}
</script>

@endsection
