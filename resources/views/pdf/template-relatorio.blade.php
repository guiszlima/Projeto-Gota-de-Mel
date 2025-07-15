<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header small {
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

    </style>
</head>
<body>

    <div class="header">
        <h2>Relatório de Vendas</h2>
        <small>Período: {{ request('searchStartDate') ?? 'Hoje' }} até {{ request('searchEndDate') ?? 'Hoje' }}</small><br>
        <small>Gerado em: {{ now()->format('d/m/Y H:i') }}</small>
    </div>
        
    <p style="
    background-color: #e6f4ea;     /* tom de verde claro translúcido */
    color: #2e7d32;                /* verde escuro para o texto */
    padding: 8px 12px;
    border: 1px solid #a5d6a7;     /* verde intermediário para a borda */
    border-radius: 6px;
    font-weight: bold;
    display: inline-block;
    margin-top: 15px;
">
    Total geral vendido: R$ {{ number_format($totalVendasGerais, 2, ',', '.') }}
</p>
    <table>
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Qtde. de Vendas</th>
                <th>Total Vendido (R$)</th>
                <th>Última Venda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->user_name }}</td>
                    <td>{{ $sale->total_vendas }}</td>
                    <td>R$ {{ number_format($sale->total_vendido, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->ultima_venda)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Nenhuma venda encontrada no período.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


    <div class="footer">
        


        Sistema PDV | {{ config('app.name') }}
    </div>

</body>
</html>
