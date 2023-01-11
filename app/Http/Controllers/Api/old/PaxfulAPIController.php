<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PaxfulAPIController extends Controller
{
    public $token;
    public $baseUrl;
    public $apiKey;
    public $apiSecret;

    public function __construct( $apiKey, $api_secret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $api_secret;
        $this->baseUrl = 'https://paxful.com/api/';
    }


//    public function get_api_response($end_point, $body = null, $img = false)
//    {
//        $client = Http::asForm()->timeout(40)
//            ->withToken( $this->token )
//            ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
//            ->post($this->baseUrl . $end_point, $body);
//
//        if ($img)
//            return $client->body();
//        else
//            return (object)$client->json();
//
//    }


    public function get_api_response($end_point, $body = null, $img = false)
    {

        $payload = [
            'apikey' => $this->apiKey,
        ];

        $payload = $payload + $body;

        $apiseal = hash_hmac('sha256', http_build_query($payload, null, '&', PHP_QUERY_RFC3986), $this->apiSecret);
        $payload['apiseal'] = $apiseal;

        // dd($this->baseUrl . $end_point);
        $ch = curl_init($this->baseUrl . $end_point);

        // NOTICE that we send the payload as a string instead of POST parameters
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload, null, '&', PHP_QUERY_RFC3986));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json; version=1',
            'Content-Type: text/plain',
        ]);

        // fetch response
        $response = curl_exec($ch);

        curl_close($ch);

        if ($img){
            return $response;
        }
        else{
            $data = json_decode($response);

            return $data;
        }


    }



//    public function get_api_response($end_point, $body = null, $type = "get")
//    {
//
//        $payload = [
//            'apikey' => $this->apiKey,
//        ];
//
//        $payload = $payload + $body;
//
//        $apiseal = hash_hmac('sha256', http_build_query($payload, null, '&', PHP_QUERY_RFC3986), $this->apiSecret);
//        $payload['apiseal'] = $apiseal;
//
//        // dd($this->baseUrl . $end_point);
//        $ch = curl_init($this->baseUrl . $end_point);
//
//        // NOTICE that we send the payload as a string instead of POST parameters
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload, null, '&', PHP_QUERY_RFC3986));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, [
//            'Accept: application/json; version=1',
//            'Content-Type: text/plain',
//        ]);
//
//        // fetch response
//        $response = curl_exec($ch);
//
//        curl_close($ch);
//
//        $data = json_decode($response);
//
//        return $data;
//
//    }
}
