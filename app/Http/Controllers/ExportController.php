<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sell;

class ExportController extends Controller
{
    public function exportVendas()
    {
        $sales = Sell::query()
            ->join('users', 'sells.user_id', '=', 'users.id')
            ->leftJoin('payments', 'sells.id', '=', 'payments.sell_id')
            ->whereDate('sells.created_at', today()) // Apenas vendas de hoje
            ->orderBy('sells.created_at', 'desc')
            ->select('sells.*', 'users.name as user_name', 'users.CPF as user_cpf', 'payments.pagamento', 'payments.troco')
            ->get();

        // Crie o arquivo CSV
        $filename = 'vendas-hoje.csv';
        $handle = fopen(public_path($filename), 'w');

        // Adicione os cabeçalhos
        fputcsv($handle, ['ID', 'User Name', 'User CPF', 'Pagamento', 'Troco', 'Created At']);

        // Adicione os dados das vendas
        foreach ($sales as $sale) {
            fputcsv($handle, [
                $sale->id,
                $sale->user_name,
                $sale->user_cpf,
                $sale->pagamento,
                $sale->troco,
                $sale->created_at
            ]);
        }

        fclose($handle);

        // Faça o download do arquivo CSV
        return response()->download(public_path($filename))->deleteFileAfterSend(true);
    }
}
