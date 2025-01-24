<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Automattic\WooCommerce\Client;

class WooCommerceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $consumerKey = env('WOOCOMMERCE_CONSUMER_KEY');
            $consumerSecret = env('WOOCOMMERCE_CONSUMER_SECRET');
            $storeUrl = env('WOOCOMMERCE_STORE_URL');

            return new Client(
                $storeUrl,
                $consumerKey,
                $consumerSecret,
                [
                    'version' => 'wc/v3',
                ]
            );
        });
    }

 


    
}