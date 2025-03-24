<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 58mm; /* Ajuste conforme a largura da sua bobina */
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

--------------------------------
    <div class="header">
        <h2>Loja Gotas De Mel</h2>
        --------------------------------
        <p>Rua Florestal 20, 37A, 104, Bairro Cidade Nova Heliopolis, Sao Paulo - SP CEP 04235-200</p>
        <p>CNPJ 51.891.343/0001-24</p>
        <p>Horario: {{ now()->format('H:i:s') }}</p>
       
    </div>
--------------------------------
    <div class="content">
        <h3>Referencias de Troca</h3>
        --------------------------------
        @foreach($dados as $item)
            @if($item["cont"] > 0)
                <div class="item">
                    <p>ID do Produto: {{ $item['id'] }}</p>
                    <p>Quantidade: {{ $item['cont'] }}</p>
                    <p>Preço Produto Unitario: R$ {{ number_format($item['preco_produto'], 2, ',', '.') }}</p>
                    <p><strong>Valor Total Devolvido: R$ {{ number_format($item['preco_produto'] * $item['cont'], 2, ',', '.') }}</strong></p>
                </div>
            @endif
        @endforeach
    </div>
--------------------------------
    <div class="footer">
        <p>Obrigado pela preferência!</p>
    </div>
--------------------------------
</body>
</html>
