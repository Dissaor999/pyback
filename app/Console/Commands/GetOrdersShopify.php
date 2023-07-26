<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\join\shopify\ShopifyOrderController;

class GetOrdersShopify extends Command
{

    protected $signature = 'cron:get-orders-shopify';


    protected $description = 'Get new sales orders from shopify.';


    public function handle()
    {
        $getOrders = new ShopifyOrderController();
        $getOrders->getOrders();
    }
}
