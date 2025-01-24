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
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 5px;
        }
        .item {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Loja Gotas De Mel</h2>
        
        <p>Telefone: (00) 0000-0000</p>
    </div>

    <div class="content">
        @foreach ($dados['cart'] as $item)
            <div class="item">
                <p>Produto: {{ $item['name'] }}</p>
                <p>Quantidade: {{ $item['quantidade'] }}</p>
                <p>Preço Unitário: R$ {{ number_format($item['value'], 2, ',', '.') }}</p>
                <p><strong>Total: R$ {{ number_format($item['value'] * $item['quantidade'], 2, ',', '.') }}</strong></p>
            </div>
        @endforeach

        <div class="payment">
            <h3>Referências de Pagamento</h3>
            @foreach ($dados['paymentReference'] as $payment)
                <p>ID da Venda: {{ $payment['sell_id'] }}</p>
                <p>Pagamento: {{ $payment['pagamento'] }}</p>
                <p>Preço: R$ {{ number_format($payment['preco'], 2, ',', '.') }}</p>
                @if ($payment['parcelas']??"")
                    <p>Parcelas: {{ $payment['parcelas'] ?? "" }}</p>
                @endif
            @endforeach
        </div>
    </div>

    <div class="footer">
        <p>Obrigado pela preferência!</p>
    </div>
</body>
</html>