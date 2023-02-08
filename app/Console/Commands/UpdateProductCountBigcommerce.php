<?php

namespace App\Console\Commands;

use App\Models\TblChannel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateProductCountBigcommerce extends Command
{
    protected $signature = 'update-count:bigcommerce';

    protected $description = 'It will fetch products count from bigcommerce and saves into database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $rows = TblChannel::where('ecom', 'bigc')->get();
        foreach ($rows as $row) {
            $api_key = $row->api_key;
            $token = $row->token;

            $api_url = 'https://api.bigcommerce.com' . '/stores/' . $api_key . '/v3/catalog/products';
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Auth-Token' => $token,
            ])->get($api_url);

            $response = json_decode($response->body(), true);

            try {
                $row->platform = $response['meta']['pagination']['total'];
                dump($response['meta']);
            } catch (\Exception $e) {
                dump($response);
                $row->platform = 0;
            }

            $row->timestamps = false;
            $row->save();
        }

        return 0;
    }
}
