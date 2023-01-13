<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Console\Command;

class FetchAndDeleteProducts extends Command
{
    protected $signature = 'products:fetch-delete';

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

//        $shop = 'ursula-miles';
//        $token = 'shpua_da2ffe15517ba8cf9d6d8b46849a369e';
//
//        $shop = 'grill-merchant';
//        $token = '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';
dump($filters);
                $productController = new ProductController();
                $products = $productController->shopify_call($store->token, $store->slug, "/admin/api/2021-01/products.json", $filters);

                $response = json_decode($products['data'], JSON_PRETTY_PRINT);
                $headers = $products['headers'];
                dump($headers);
                $productController->update_page_info($headers, $store);
                foreach ($response['products'] as $product) {
                    $productExists = Product::where('sku', $product['variants'][0]['sku'])->first();
                    if ($productExists) {
                        if($productExists->store_id == $store->id){
//                            $productController->shopify_call($store->token, $store->slug,
//                                sprintf('/admin/api/2023-01/products/%s.json', $product['id']),
//                                [],
//                                'DELETE');
                            $this->info(sprintf('Product ID: %s Deleted', $product['id']));
                        }

                    } else {
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
