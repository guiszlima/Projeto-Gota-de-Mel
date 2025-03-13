<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Sell;

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
    $startDate = $data['searchStartDate'] ?? now()->startOfDay()->toDateTimeString();
    $endDate = $data['searchEndDate'] ?? now()->endOfDay()->toDateTimeString();

    $sales = Sell::query()
        ->join('users', 'sells.user_id', '=', 'users.id')
        ->leftJoin('payments', 'sells.id', '=', 'payments.sell_id')
        ->when($data['selectedPay'] ?? null, function ($query, $pay) {
            $query->where('payments.pagamento', $pay);
        })
        ->when(isset($data['selectedStatus']), function ($query) use ($data) {
            $query->where('sells.cancelado', (int) $data['selectedStatus']);
        })
        ->when($data['searchName'] ?? null, function ($query, $name) {
            $query->where('users.name', 'like', '%' . $name . '%');
        })
        ->when($data['searchId'] ?? null, function ($query, $id) {
            $query->whereRaw('JSON_CONTAINS(sells.produtos, ?)', [json_encode($id)]);
        })
        ->when($data['searchSellId'] ?? null, function ($query, $sellId) {
            $query->where('sells.id', 'like', '%' . $sellId . '%');
        })
        ->when($data['searchPrice'] ?? null, function ($query, $price) {
            $query->where('sells.preco_total', $price);
        })
        ->when($data['searchTroco'] ?? null, function ($query, $troco) {
            $query->where('payments.troco', $troco);
        })
        ->whereBetween('sells.created_at', [$startDate, $endDate])
        ->where('sells.cancelado', false)
        ->orderBy('sells.created_at', 'desc')
        ->select('sells.*', 'users.name as user_name', 'users.CPF as user_cpf', 'payments.pagamento', 'payments.preco', 'payments.troco', 'payments.parcelas')
        ->get()
        ->groupBy('id'); // Agrupa os resultados por ID da venda

    $pdf = Pdf::loadView('pdf.template-relatorio', compact('sales'));
    return $pdf->download('sales_report.pdf');
}

    public function generatePdf(Request $request)
    {
        $dados = [
            'cart' => $request->input('cart'),
            'paymentReference' => $request->input('paymentReference'),
            'horario' => date('d/m/Y H:i'),
            'troco' => $request->input('troco'),
            'desconto' => $request->input('desconto')
        ];

        $pdf = Pdf::loadView('pdf.template', ['dados' => $dados]);
        return $pdf->stream('nota-fiscal.pdf');
    }
}
