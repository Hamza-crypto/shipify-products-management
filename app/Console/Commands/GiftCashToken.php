<?php

namespace App\Console\Commands;

use App\Models\Gateway;
use App\Http\Controllers\Api\GiftCashController;
use Illuminate\Console\Command;


class GiftCashToken extends Command
{
    protected $signature = 'gc:auth';

    protected $description = 'It generates gift cash jwt token';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $gc = new GiftCashController();
        $gateways = Gateway::all();
        foreach ($gateways as $gateway) {
            $gc->jwt($gateway->id, $gateway->api_key, $gateway->api_secret);
        }
        echo "Token generated successfully";
    }
}
