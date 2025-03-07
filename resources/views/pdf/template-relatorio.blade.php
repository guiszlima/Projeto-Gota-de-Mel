<style>
    table {
        width: 100%;
        table-layout: fixed; /* Isso vai forçar a tabela a se ajustar ao tamanho da página */
        border-collapse: collapse;
        border: 1px solid #D1D5DB;
        word-wrap: break-word; /* Quebra de palavras longas */
    }

    thead {
        background-color: #2d3748; /* cor de fundo para o cabeçalho */
        color: white; /* cor do texto no cabeçalho */
        font-size: 10px; /* Fonte reduzida para uma melhor legibilidade */
        font-family: Arial, sans-serif; /* Fonte legível */
    }

    th, td {
        padding: 8px 10px; /* Reduz o espaçamento interno das células */
        text-align: center;
        border: 1px solid #D1D5DB; /* borda nas células */
        word-wrap: break-word; /* Quebra de palavras longas nas células */
        line-height: 1.4; /* Aumenta a altura da linha para melhorar a leitura */
        letter-spacing: 0.5px; /* Ajusta o espaçamento das letras */
        font-family: Arial, sans-serif; /* Fonte legível */
    }

    th {
        background-color: #2d3748; /* cor de fundo do cabeçalho */
        color: white; /* cor do texto no cabeçalho */
        font-weight: bold;
    }

    tbody tr:nth-child(odd) {
        background-color: #f7fafc; /* cor de fundo das linhas ímpares */
    }

    tbody tr:nth-child(even) {
        background-color: #edf2f7; /* cor de fundo das linhas pares */
    }

    .status-cancelado {
        background-color: #fed7d7; /* fundo vermelho claro para cancelado */
        color: #c53030; /* cor de texto vermelho para cancelado */
        padding: 4px 6px;
        font-size: 10px; /* Tamanho de fonte menor para status */
        border-radius: 4px;
        font-family: Arial, sans-serif; /* Fonte legível */
    }

    .status-aprovado {
        background-color: #c6f6d5; /* fundo verde claro para aprovado */
        color: #38a169; /* cor de texto verde para aprovado */
        padding: 4px 6px;
        font-size: 10px; /* Tamanho de fonte menor para status */
        border-radius: 4px;
        font-family: Arial, sans-serif; /* Fonte legível */
    }

    .status-credito {
        background-color: #bee3f8; /* fundo azul claro para crédito */
        color: #3182ce; /* cor de texto azul para crédito */
        padding: 4px 6px;
        font-size: 10px; /* Tamanho de fonte menor para status */
        border-radius: 4px;
        font-family: Arial, sans-serif; /* Fonte legível */
    }

    .status-debito {
        background-color: #fbd38d; /* fundo amarelo claro para débito */
        color: #dd6b20; /* cor de texto laranja para débito */
        padding: 4px 6px;
        font-size: 10px; /* Tamanho de fonte menor para status */
        border-radius: 4px;
        font-family: Arial, sans-serif; /* Fonte legível */
    }

    .price {
        font-weight: bold;
        color: #2f855a; /* verde escuro para preço */
        font-size: 10px; /* Fonte ajustada para legibilidade */
    }

    .total {
        font-weight: bold;
        color: #1a202c; /* cor escura para total */
        font-size: 10px; /* Fonte ajustada para legibilidade */
    }
</style>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Total</th>
            <th>Produtos</th>
            <th>Pagamento</th>
            <th>Troco</th>
            <th>Parcelas</th>
            <th>Desconto</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $productId => $groupedItems)
            <tr>
                <td colspan="11" class="font-semibold text-gray-700 bg-gray-300">
                    Venda ID: {{ $productId }}
                </td>
            </tr>

            @foreach($groupedItems as $item)
                <tr>
                    <td>{{ $item['user_name'] }}</td>
                    <td class="price">R${{ number_format($item['preco'], 2, ',', '.') }}</td>
                    <td class="total">R${{ number_format($item['preco_total'], 2, ',', '.') }}</td>
                    <td>{{ implode(', ', json_decode($item['produtos'])) }}</td>
                    <td>
                        <span class="{{ $item['pagamento'] === 'credit' ? 'status-credito' : ($item['pagamento'] === 'debit' ? 'status-debito' : '') }}">
                            {{ $item['pagamento'] === 'credit' ? 'Crédito' : ($item['pagamento'] === 'debit' ? 'Débito' : $item['pagamento']) }}
                        </span>
                    </td>
                    <td>{{ $item['troco'] ?? '-' }}</td>
                    <td>{{ $item['parcelas'] ?? '-' }}</td>
                    <td>{{ $item['desconto'] ?? '0.00' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
