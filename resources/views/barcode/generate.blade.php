@extends('layouts.main')

@section('content')







<div id="printable_div_id">
    <svg class="hidden" id="codBarras">{{$product['id']}}</svg>
</div>


<img id="barcodeImage">
<canvas id="canvas" style="display: none;"></canvas>
<button id="printButton">
    Baixar
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

    function convertSvgToPng(svgSelector,boolean) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        const svgString = new XMLSerializer().serializeToString(document.querySelector(svgSelector));
        const img = new Image();
        img.onload = function () {
            ctx.drawImage(img, 0, 0);
            const pngUrl = canvas.toDataURL('image/png');
            barcodeImage.src = pngUrl;  // Mostra a imagem do código de barras

           if(boolean == true){
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = "{{$product['name']}}"+'.png';
            downloadLink.click();
          }
        };
        img.src = 'data:image/svg+xml;base64,' + btoa(svgString);
    }

    // Gerar o código de barras ao carregar a página
    GerarCódigoDeBarras();

    // Evento para imprimir ao clicar no botão
    printButton.addEventListener('click', function () {
        convertSvgToPng('#codBarras',true);
    });
</script>
@endsection
