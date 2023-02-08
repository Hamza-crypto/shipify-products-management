<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return view('pages.product.index', get_defined_vars());
    }

    public function sku()
    {
        $stores = Store::all();
        return view('pages.product.sku', get_defined_vars());
    }

    public function sku_update_from_csv(Request $request)
    {
        $store = Store::find($request->store);
        $key = $request->key_type;

        $handle = fopen($_FILES['file']['tmp_name'], "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $product = Product::where($key, $data[0])->first();

                if($product){
                    app('log')->channel('products')->info($product->id);
                    $body = ['product' => ['status' => 'draft']];
                    $this->shopify_call(
                        $store->token,
                        $store->slug,
                        sprintf('/admin/api/2023-01/products/%s.json', $product->id),
                        $body,
                        'PUT'
                    );

                    $product->update(['status' => 'draft', 'is_blacklisted' => 1]);

                }
        }
        fclose($handle);

        Session::flash('success', 'Products updated successfully');
        return redirect()->back();
    }

    public function delete_products(Request $request){
        if ($request->has('images')) {
            $products = Product::where('num_of_images', 0)->get();
        }
        if ($request->has('upc')) {
            $products = Product::whereNull('upc')->get();
        }
        if ($request->has('sku')) {
            $products = Product::whereNull('sku')->get();
        }

        $count = 0;
        foreach ($products as $product) {
//            $this->shopify_call(
//                $product->store->token,
//                $product->store->slug,
//                sprintf('/admin/api/2023-01/products/%s.json', $product->id),
//                [],
//                'DELETE'
//            );
            $product->update(['status' => 'deleted']);
            $count++;
        }

        Session::flash('success', sprintf('%d products deleted', $count));
        return redirect()->back();

    }

    public function sku_update(Request $request)
    {
        dd($request->all());
        $shop = 'ursula-miles';
        $token = 'shpua_da2ffe15517ba8cf9d6d8b46849a369e';

        $shop = 'grill-merchant';
        $token = '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';

        $handle = fopen($_FILES['file']['tmp_name'], "r");
        $count = 0;
        $skus = ['InsulatedPot3201', 'Pizza11608', 'PizzaScreen11707', 'StockPot55CMwPastaInsert3906'];
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            if (in_array($data[0], $skus)) {
                echo sprintf('Count: %d ------ SKU: %s </br>', $count, $data[0]);
                $body = [
                    'product' => [
                        'title' => $data[3],
                        'product_type' => $data[1],
                        'body_html' => $data[8],
                        'variant' => [
                            'sku' => $data[0],
                            'price' => $data[6],
                            'inventory_quantity' => $data[4],
                        ],
                        'images' => [
                            ['src' => $data[10]],
                            ['src' => $data[11]],
                            ['src' => $data[12]],
                            ['src' => $data[13]],
                            ['src' => $data[14]],
                            ['src' => $data[15]],
                            ['src' => $data[16]],
                            ['src' => $data[17]],
                            ['src' => $data[18]],
                            ['src' => $data[19]]

                        ]
                    ]
                ];
                $products = $this->shopify_call($token, $shop, "/admin/api/2023-01/products.json", $body, 'POST');
                $products = json_decode($products['data'], TRUE);
                $product_id = $products['product']['id'];
                dump($product_id);
            }


            $count++;
        }
        dd('ads');

        $count = 1;
        foreach ($data as $value) {
            dump($value);
            $count++;
            if ($count > 30) break;
        }

        $handle = fopen($_FILES['file']['tmp_name'], "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        }
        fclose($handle);
    }

    public function shopify_call($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array())
    {
        $url = sprintf('https://%s.myshopify.com%s', $shop, $api_endpoint);
        if (!is_null($query) && in_array($method, array('GET', 'DELETE'))) $url = $url . "?" . http_build_query($query);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        $request_headers[] = '';
        $request_headers[] = 'Content-Type: application/json';
        if (!is_null($token)) $request_headers[] = sprintf('X-Shopify-Access-Token: %s', $token);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

        if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
            if (is_array($query)) $query = json_encode($query);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }

        $response = curl_exec($curl);
        $error_number = curl_errno($curl);
        $error_message = curl_error($curl);
        curl_close($curl);

        if ($error_number) {
            return $error_message;
        } else {
            $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
            $headers = array();
            $header_data = explode("\n", $response[0]);
            $headers['status'] = $header_data[0];
            array_shift($header_data);
            foreach ($header_data as $part) {
                $h = explode(":", $part, 2);
                $headers[trim($h[0])] = trim($h[1]);
            }

            return array('headers' => $headers, 'data' => $response[1]);
        }
    }

    public function update_page_info($headers, $store)
    {
        dump('Page info updated');
        if (isset($headers['link'])) {
            if (is_null($store->page_info)) {
                $expression = preg_match_all(
                    '/&page_info=(.*?)>; rel="next"/m',
                    $headers['link'],
                    $matches,
                    PREG_SET_ORDER,
                    0
                );
            } else {
                $expression = preg_match_all(
                    '/rel="previous".*&page_info=(.*?)>; rel="next"/m',
                    $headers['link'],
                    $matches,
                    PREG_SET_ORDER,
                    0
                );
            }

            if (!isset($matches[0])) {
                dump($headers);
                $store->page_info = null;
                $store->last_run = time();
                $store->save();
                echo "No more products and breaking the loop";
                return;
            }

            $store->page_info = $matches[0][1];
            $store->save();
        }


    }

    public function exports(Request $request){

        $products= Product::filters($request->all())->get();
        $data = [];
        foreach ($products as $product){
            $data[] = [
                'Product ID' =>  sprintf('="%s"', $product->product_id),
                'Title' => $product->title,
                'SKU' => sprintf('="%s"', $product->sku),
                'UPC' => sprintf('="%s"', $product->upc)
            ];
        }

        return (new FastExcel($data))->download('products.csv');
    }
}
