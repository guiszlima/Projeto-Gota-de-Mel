<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WooCommerceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarCodeMakerController;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request; // Adicione esta linha
use Barryvdh\DomPDF\Facade\Pdf; 


// routes/web.php



// Redireciona a rota '/' para '/registro'



Route::middleware(['auth','check_pending'])->group(function () {


  Route::get('/',function(){
    if (Auth::check()) {
      // Se o usuário estiver autenticado, redireciona para a rota 'menu'
      return redirect()->route('menu');
  }
  });  
    Route::get("/menu", function(){
        $user = Auth::user();
        
        
    return view('menu')
    ->with('user', $user);
    
    })->name('menu');
    
 
      Route::middleware(['is_admin'])->group(function(){

 Route::get('admin', [AdminController::class,'index'])->name('admin.index');
    Route::put('admin/{id}', [AdminController::class,'update'])->name('admin.update');
    Route::delete('admin/{id}',[AdminController::class, 'deleteUsers'])->name('admin.delete');
    # Accept users to get in system routes
    Route::get('admin-accept', [AdminController::class,'accept_index'])->name('admin.index.accept');
    Route::put('admin-accept',[AdminController::class, 'acceptUsers'])->name('admin.accept');;
 Route::get('teste', [WooCommerceController::class, 'getProducts'])->name('get.products');
      });

      Route::middleware(['is_manager'])->group(function(){
  Route::get('estoque', [StockController::class, 'index'])->name('stock.index');

  
    Route::get('estoque/create', [StockController::class, 'create'])->name('stock.create');
    Route::get('estoque/create-variation-product',[StockController::class,'createVariableProduct'])->name('stock.create.var-product');
    Route::post('estoque/create-variation-product',[StockController::class,'storeVariableProduct'])->name('stock.store.var-product');
    Route::post('estoque', [StockController::class, 'store'])->name('stock.store');
    Route::get('estoque/{id}', [StockController::class, 'show'])->name('stock.show');
    Route::put('estoque', [StockController::class, 'update'])->name('stock.update');
    Route::delete('estoque/{id}', [StockController::class, 'destroy'])->name('stock.destroy');
    Route::get('relatorio/estoque',[ReportController::class,'get'])->name('report.products');
    Route::get('relatorio/vendas',[ReportController::class,'getSells'])->name('report.sells');
        
      });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    # Admin Routes
   
    # Sell routes
    Route::get('vender-produto',[WooCommerceController::class,'sellProducts'])->name('products.sell');
    Route::post('vender-produto',[WooCommerceController::class,'makeSell'])->name('products.make-sell');
    Route::get('pagamento',[WooCommerceController::class,'payment'])->name('products.payment');
    # BarCode Generator Routes
    Route::get('barcode',[BarCodeMakerController::class,'index'])->name('barcode.index');
    Route::get('gerar-codigo',[BarCodeMakerController::class,'generate'])->name('barcode.generate');
    # Administrate Products 
   // Listar todos os recursos
   Route::get('/generate-pdf', function (Request $request) {
    $dados = [
        'cart' => $request->input('cart'),
        'paymentReference' => $request->input('paymentReference'),
        'horario' => date('d/m/Y H:i'), // Adiciona o horário no formato correto
        'troco' => $request->input('troco'),
        'desconto' => $request->input('desconto')
    ];

    // Gera o PDF
    $pdf = Pdf::loadView('pdf.template', ['dados' => $dados]);

    // Retorna o PDF para visualização
    return $pdf->stream('nota-fiscal.pdf');
})->name('generate.pdf');

Route::get('/generate-pdf-troca', function (Request $request) {


foreach ($request->all() as $item) {
  $dados[] = [
      'id' => $item['id_produto'],
      'cont' => $item['cont'],
      'preco_produto' => $item['preco_produto'],
      
  ];
}

  // Gera o PDF
  $pdf = Pdf::loadView('pdf.template-troca', ['dados' => $dados]);

  // Retorna o PDF para visualização
  return $pdf->stream('nota-fiscal.pdf');
})->name('generate.pdf.troca');

Route::get('/generate-pdf-relatorio', function (Request $request) {
  
  $dados = collect($request->input('sales', []))
        ->filter() // Remove valores nulos
        ->groupBy('id'); // Agrupa pelo ID da venda
        
  
    // Gera o PDF
    $pdf = Pdf::loadView('pdf.template-relatorio', ['data' => $dados]);
  
    // Retorna o PDF para visualização
    return $pdf->stream('relatorio.pdf');
  })->name('generate.pdf.relatorio');
  
  

});

   
  

//Webhook para a enviar dados para a aplicação desktop de impressão de notas
Route::post('webhook', [WebhookController::class, 'EnviarWebhook'])->name('webhook');


require __DIR__.'/auth.php';