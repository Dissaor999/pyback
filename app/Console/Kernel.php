<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


    protected $commands = [
        Commands\GetOrdersShopify::class,
        Commands\GetItemsZoho::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cron:get-orders-shopify')->withoutOverlapping()->hourlyAt(0);
        $schedule->command('cron:get-items-zoho')->withoutOverlapping()->hourlyAt(15);
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
