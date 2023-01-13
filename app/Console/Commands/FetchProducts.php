<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Console\Command;

class FetchProducts extends Command
{
    protected $signature = 'products:fetch';

    protected $description = 'It fetches products from the Shopify API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $stores = Store::all();

        $filters = [
            'limit' => 250
        ];
        foreach ($stores as $store) {
            if (empty($store->last_run) || $store->last_run < strtotime('-1 day', time())) {

                if ($store->page_info) {
                    $filters['page_info'] = $store->page_info;
                    $filters['rel'] = 'next';
                }

                dump($filters);
                $productController = new ProductController();
                $products = $productController->shopify_call($store->token, $store->slug, "/admin/api/2021-01/products.json", $filters);

                $response = json_decode($products['data'], JSON_PRETTY_PRINT);
                $headers = $products['headers'];
                dump($headers);
                $productController->update_page_info($headers, $store);
                foreach ($response['products'] as $product) {
                    $product_from_db = Product::where('sku', $product['variants'][0]['sku'])->first();
                    if (!$product_from_db) {
                        Product::create([
                            'title' => $product['title'],
                            'product_id' => $product['id'],
                            'sku' => $product['variants'][0]['sku'],
                            'upc' => $product['variants'][0]['barcode'],
                            'num_of_images' => count($product['images']),
                            'status' => $product['status'],
                            'is_blacklisted' => 0,
                            'store_id' => $store->id,
                        ]);
                    }
                }
                break;
            }
        }

        return 0;
    }
}
