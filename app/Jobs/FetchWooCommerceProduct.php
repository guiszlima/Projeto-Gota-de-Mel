<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Automattic\WooCommerce\Client;
use App\Models\Product;

class FetchWooCommerceProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $woocommerce;
    protected $searchTerm;
    protected $callback;

    public function __construct(Client $woocommerce, $searchTerm, callable $callback)
    {
        $this->woocommerce = $woocommerce;
        $this->searchTerm = $searchTerm;
        $this->callback = $callback;
    }

    public function handle()
    {
        $param = ['sku' => $this->searchTerm];
        $pedido = $this->woocommerce->get('products', $param);

        if (!empty($pedido)) {
            $produto = $pedido[0];

            // Chama o callback para adicionar ao carrinho
            call_user_func($this->callback, $produto->name, $produto->price, $produto->id, $produto->stock_quantity);
        }
    }
}
