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
        <p>Rua Florestal 20, 37A, 104, Bairro Cidade Nova Heliopolis, Sao Paulo - SP CEP 04235-200
        </p>

        <p>CNPJ 51.891.343/0001-24
        </p>
        <p>Horario: {{$dados['horario']}} </p>
        <p>AVISO: 7 DIAS PARA TROCA</p>
    </div>
    --------------------------------
    <div class="content">
        @foreach ($dados['cart'] as $item)
            <div class="item">
                <p>Produto: {{ $item['name'] }}</p>
                <p>Quantidade: {{ $item['quantidade'] }}</p>
                <p>Preço Unitario: R$ {{ number_format($item['value'], 2, ',', '.') }}</p>
                <p><strong>Total: R$ {{ number_format($item['value'] * $item['quantidade'], 2, ',', '.') }}</strong></p>
            </div>
        @endforeach
        --------------------------------
        @if($dados['paymentReference'])
        <div class="payment">
            <h3>Referencias de Pagamento</h3>
            @foreach ($dados['paymentReference'] as $payment)
                <p>ID da Venda: {{ $payment['sell_id'] }}</p>
                <p>Forma de Pagamento: {{ $payment['pagamento'] == '' ? 'Débito' : ($payment['pagamento'] == 'credit' ? 'Crédito' : $payment['pagamento'] ) }}</p>
                <p>Pagamento: R$ {{ number_format($payment['preco'], 2, ',', '.') }}</p>
                @if ($payment['parcelas']??"")
                    <p>Parcelas: {{ $payment['parcelas'] ?? "" }}</p>
                @endif
            @endforeach
        </div>
        @elseif(!$dados['paymentReference'] && $dados['desconto'] )
        <p>ID da Venda: {{ $dados['IdVenda'] }}</p>
        
        @endif
        


        @if ($dados['troco']??"")
                    <p>Troco: {{ number_format($dados['troco'], 2, ',', '.') ?? "" }}</p>
                @endif
        @if($dados['desconto']??"")
        <p>Desconto: {{ number_format($dados['desconto'], 2, ',', '.') ?? "" }}</p>
        @endif
    </div>
--------------------------------
    <div class="footer">
        <p>Obrigado pela preferencia! <br></p>
<br>
    </div>
    --------------------------------
</body>
</html>