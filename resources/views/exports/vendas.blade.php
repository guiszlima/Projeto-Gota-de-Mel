<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>CPF</th>
            <th>Pagamento</th>
            <th>Total</th>
            <th>Troco</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->user_name }}</td>
                <td>{{ $sale->user_cpf }}</td>
                <td>{{ $sale->pagamento }}</td>
                <td>R$ {{ number_format($sale->preco_total, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($sale->troco, 2, ',', '.') }}</td>
                <td>{{ $sale->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
