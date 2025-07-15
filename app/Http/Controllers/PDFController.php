<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Sell;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    public function generatePdfTroca(Request $request)
    {
        $dados = [];
        foreach ($request->all() as $item) {
            $dados[] = [
                'id' => $item['id_produto'],
                'cont' => $item['cont'],
                'preco_produto' => $item['preco_produto'],
            ];
        }

        $pdf = Pdf::loadView('pdf.template-troca', ['dados' => $dados]);
        return $pdf->stream('nota-fiscal.pdf');
    }

    public function generatePdfRelatorio(Request $request)
{
    $data = $request->all();

    // Define as datas padrão como hoje se não forem fornecidas
   
    
   $sales = Sell::query()
        ->join('users', 'sells.user_id', '=', 'users.id')
        ->leftJoin('payments', 'sells.id', '=', 'payments.sell_id')
        ->when(isset($data['selectedStatus']), function ($query) use ($data) {
            $query->where('sells.cancelado', (int) $data['selectedStatus']);
        })
        ->when($data['searchName'] ?? null, function ($query, $name) {
            $query->where('users.name', 'like', '%' . $name . '%');
        })
       ->when(true, function ($query) use ($data) {
            $startDate = ($data['searchStartDate'] ?? now()->toDateString()) . ' 00:00:00'; // Início do dia
            $endDate = ($data['searchEndDate'] ?? now()->toDateString()) . ' 23:59:59'; // Final do dia
          
            $query->whereBetween('sells.created_at', [$startDate, $endDate]);
        })
        ->where('sells.cancelado', false)
        ->groupBy('users.id', 'users.name')
        ->select([
            'users.name as user_name',
            DB::raw('COUNT(sells.id) as total_vendas'),
            DB::raw('SUM(sells.preco_total) as total_vendido'),
            DB::raw('MAX(sells.created_at) as ultima_venda'),
        ])
        ->get();
 // Agrupa os resultados por ID da venda

    $pdf = Pdf::loadView('pdf.template-relatorio', compact('sales','data'));
    return $pdf->download('sales_report.pdf');
}

    public function generatePdf(Request $request)
    {
        
        $dados = [
            'cart' => $request->input('cart'),
            'paymentReference' => $request->input('paymentReference'),
            'IdVenda' => $request->input('IdVenda'),
            'horario' => date('d/m/Y H:i'),
            'troco' => $request->input('troco'),
            'desconto' => $request->input('desconto')
        ];

        $pdf = Pdf::loadView('pdf.template', ['dados' => $dados]);
        return $pdf->stream('nota-fiscal.pdf');
    }
}
