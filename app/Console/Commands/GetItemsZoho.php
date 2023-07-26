<?php

namespace App\Console\Commands;

use App\Http\Controllers\join\zoho\ZohoItemController;
use Illuminate\Console\Command;


class GetItemsZoho extends Command
{

    protected $signature = 'cron:get-items-zoho';


    protected $description = 'Get items from zoho.';


    public function handle()
    {
        $getItemsZoho = new ZohoItemController();
        $getItemsZoho->getZohoItems();
    }
}
