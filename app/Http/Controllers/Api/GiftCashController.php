<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Gateway;
use App\Models\UserMeta;
use Illuminate\Auth\Access\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderStatusUpdated;

class GiftCashController extends Controller
{
    public $token;
    public $base_url;

    public function __construct($token = null, $apiKey = null, $api_secret = null)
    {
        $this->token = $token;
        $this->apiKey = $apiKey;
        $this->apiSecret = $api_secret;
        $this->base_url = env('GC_BASE_URL', 'https://partner-dev.giftcash.io');
    }

    public function login()
    {
        if (empty(setting('jwt')) || setting('jwt') == null) {
            $this->jwt();
        }


        $gateway_id = UserMeta::where('user_id' , Auth::id())
        ->where('meta_key', );

        $this->token = setting('jwt');
    }

    public function jwt($id, $username , $password)
    {
        $url = sprintf("%s/auth", $this->base_url);

        try {
            $response = Http::withBasicAuth($username, $password)->get($url);

        } catch (\Exception $exception) {
            Log::channel('auth')->info('gc:jwt:failure', $exception->getMessage());
        }

        setting([
            'jwt' .$id  => $response['data']['attributes']['token'],
        ])->save();

        Log::channel('auth')->info('gc:jwt:success', $response->json());

        return $response['data']['attributes']['token'];
    }

    public function place_order(Request $request)
    {
        $this->login();
        $httpData =
            [
                "cardBalance" => $request->amount,
                "cardNumber" => $request->card_number,
                "cardExpiryMonth" => $request->month,
                "cardExpiryYear" => "20" . $request->year,
                "callbackStatus" => route('gc.web_hook') , // https://techouse.club/gc/webhook
                "cardCVC" => $request->cvc
            ];

        $url = sprintf("%s/order", $this->base_url);

        try {
            $response = Http::withToken($this->token)->post(
                $url,[$httpData]
            );

            Log::channel('cards')->info('place_order', $response->json());

        } catch (\Exception $exception) {

            Log::channel('cards')->info('place_order:exception',);

        }

        return $response->json();
    }

    public function gc_webhook(Request $request)
    {
        $payload = $request->all();
        Log::channel('webhook')->info('webhook:gc', $payload);

    }


}
