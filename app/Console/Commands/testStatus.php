<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class testStatus extends Command
{
    protected $signature = 'test:status';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
         $msg = "Test Bot run at this time";
         app('log')->channel('bot_test')->info($msg);
         echo "Task Done";
    }
}
