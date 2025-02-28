@extends('layouts.main')

@section('content')

<x-button-back :route="route('barcode.index')"></x-button-back>






<!-- SVG escondido para código de barras -->
<div id="printable_div_id m-auto">
    <svg class="hidden" id="codBarras">{{$product['sku']}}</svg>
</div>

<!-- Imagem do código de barras -->
<img id="barcodeImage" class="mx-auto my-4" alt="Código de Barras">

<!-- Canvas escondido -->
<canvas id="canvas" style="display: none;"></canvas>

<!-- Botão para baixar -->
<button
    class="print-hidden absolute left-[43%] bg-blue-500 text-white m-auto px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
    id="printButton">
    <span>Baixar</span>
</button>



<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
<script>
const productId = document.getElementById('codBarras');
const printButton = document.getElementById('printButton');
const barcodeImage = document.getElementById('barcodeImage');

function GerarCódigoDeBarras() {
    /*A função JsBarcode não aceita string vazia*/
    if (!productId.innerHTML) {
        productId.innerHTML = 0;
    }
    JsBarcode('#codBarras', productId.innerHTML, {
        text: "{{$product['price']}}",
        displayValue: true
    });

    // Converter SVG para PNG e exibir como imagem
    convertSvgToPng('#codBarras');
}

function convertSvgToPng(svgSelector, boolean) {
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    const svgString = new XMLSerializer().serializeToString(document.querySelector(svgSelector));
    const img = new Image();
    img.onload = function() {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);
        const pngUrl = canvas.toDataURL('image/png');
        barcodeImage.src = pngUrl; // Mostra a imagem do código de barras

        if (boolean == true) {
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = "{{$product['name']}}" + '.png';
            downloadLink.click();
        }
    };
    img.src = 'data:image/svg+xml;base64,' + btoa(svgString);
}

// Gerar o código de barras ao carregar a página
GerarCódigoDeBarras();

// Evento para imprimir ao clicar no botão
printButton.addEventListener('click', function() {
    convertSvgToPng('#codBarras', true);
});
</script>
@endsection