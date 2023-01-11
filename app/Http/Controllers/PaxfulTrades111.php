<?php

namespace App\Http\Controllers;


use App\Http\Requests\OrderRequest;
use App\Models\Bin;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Api\PaxfulAPIController;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TelegramCardCreated;
use Illuminate\Notifications\Notifiable;

class PaxfulTrades extends Controller
{
    public $api_controller_client;
    public $access_token;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {

            $this->access_token = Auth::user()->paxful_token();


            if (!$this->access_token) { // $this->access_token->hasExpired() ||

                $this->key = Auth::user()->paxful_api_key();
                $this->api_secret = Auth::user()->paxful_api_secret();

                if (!(empty($this->key) || empty($this->api_secret))) {


                        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
                            'clientId' => $this->key,
                            'clientSecret' => $this->api_secret,
                            'redirectUri' => '',
                            'urlAuthorize' => 'https://accounts.paxful.com/oauth2/token',
                            'urlAccessToken' => 'https://accounts.paxful.com/oauth2/token',
                            'urlResourceOwnerDetails' => 'https://accounts.paxful.com/oauth2/token'
                        ]);

                    $response = $provider->getAccessToken('client_credentials');

                    $this->access_token = $response->getToken();

                    Auth::user()->metas()->updateOrCreate(
                        ['meta_key' => 'paxful_token'],
                        ['meta_value' => $this->access_token]);


                } else {

                    $this->access_token = 'abcd';
                }
            }


            $this->api_controller_client = new PaxfulAPIController($this->access_token);
            return $next($request);


        });

    }

    public function getAllOffers()
    {
        $end_point = "offer/list";
        $body = ['offer_type' => 'buy'];
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        if ($response->status == 'error') {
            Session::flash('error', "Missing apikey parameter");
            $offers = 0;
            return view('pages.paxful.offers.index', compact('offers'));
        }

        $offer_buy = $response->data['offers'];

        $body = ['offer_type' => 'sell'];
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        $offers_sell = $response->data['offers'];

        $offers = array_merge($offer_buy, $offers_sell);
        return view('pages.paxful.offers.index', compact('offers'));
    }


    public function editOffer($hash)
    {
        $end_point = "offer/get";
        $body = [
            'offer_hash' => $hash
        ];

        $response = $this->api_controller_client->get_api_response($end_point, $body);

        $offer = $response->data;
        $offer = (object)$offer;
        return view('pages.paxful.offers.edit', compact('offer'));
    }

    public function updateOffer(Request $request, $hash)
    {
        //dd($request->all());
        $end_point = "offer/update";

        $api_response = [
            0 => "margin",
            1 => "range_min",
            2 => "range_max",
            3 => "offer_terms",
        ];

        $body = [
            'offer_hash' => $hash,
            $api_response[0] => $request->margin,
            $api_response[1] => $request->fiat_min,
            $api_response[2] => $request->fiat_max,
            $api_response[3] => $request->offer_terms,
        ];

        $response = $this->api_controller_client->get_api_response($end_point, $body);

        $response2 = json_decode(json_encode($response), true);

        //dd($response);
        if ($response2['status'] == 'error') {
            Session::flash('error', "Something went wrong");
            $messages = $response2['error']['message'];
            if (isset($messages['margin'])) {
                Session::flash('margin', $messages['margin'][0]);
            }
            if (isset($messages['range_max'])) {
                Session::flash('fiat_max', $messages['range_max'][0]);
            }
            return redirect()->back()->withInput($request->all());
        }

        Session::flash('success', "Successfully Updated");

        return redirect()->route('api.offer.edit', $hash);

    }

    public function activateOffer($hash)
    {
        $end_point = "offer/activate";
        $body = [
            'offer_hash' => $hash,
        ];

        dd($body, "Activated");
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        // dd($response);
        if ($response->status == 'success') {
            Session::flash('success', "Successfully updated");
        } else {
            Session::flash('error', "Something went wrong");
        }

        return redirect()->route('api.offer.edit', $hash);

    }

    public function dectivateOffer(Request $request, $hash)
    {
        $end_point = "offer/deactivate";
        $body = [
            'offer_hash' => $hash,
        ];

        dd($body, "Deactivated");
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        if ($response->status == 'success') {
            Session::flash('success', "Successfully updated");
        } else {
            Session::flash('error', "Something went wrong");
        }

        return redirect()->route('api.offer.edit', $hash);
    }

    public function cancel_trade(Request $request, $hash)
    {
        $end_point = "trade/cancel";
        $body = [
            'trade_hash' => $hash,
        ];

        return $body;
        dd($body, "Canceled");
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        if ($response->status == 'success') {
            Session::flash('success', "Successfully canceled");
        } else {
            Session::flash('error', "Something went wrong");
        }
    }

    public function release_coin(Request $request, $hash)
    {
        $end_point = "trade/release";
        $body = [
            'trade_hash' => $hash,
        ];

        return $body;
//        dd($body, "Released");
        //$response = $this->api_controller_client->get_api_response($end_point, $body);

        return $response;
        if ($response->status == 'success') {
            Session::flash('success', "Successfully released");
        } else {
            Session::flash('error', "Something went wrong");
        }
    }

    public function dispute(Request $request, $hash, $reason)
    {
        $end_point = "trade/dispute";
        $body = [
            'trade_hash' => $hash,
            'reason' => $reason,
        ];

        return $body;
//        dd($body, "Released");
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        return $response;
    }

    public function getActiveTrades()
    {

        // $trades = '';

        return view('pages.paxful.trades.index');
    }

    public function getActiveTrades2()
    {
        $data = [];
        $end_point = "trade/list";
        //$end_point = "trade/completed";
        $body = [];
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        if ($response->status == 'error') {
            Session::flash('error', "Missing apikey parameter");
            $offers = 0;
            return view('pages.paxful.offers.index', compact('offers'));
        }
        $trades = $response->data['trades'];
        $trades = (object)$trades;
        //$author = $trades->owner_username;

        $count = 0;
        foreach ($trades as $trade) {
            $author = $trade['owner_username'];
            $data[$trade['trade_hash']] [] = (object)$trade;
            $data[$trade['trade_hash']] [] = $this->get_trade_chat($trade['trade_hash'], $author);
            $count++;
//            if($count>1) break;
        }


        // $data = (object)$data;
        return $data;

    }

    public function getCompletedTrades()
    {
        $end_point = "trade/completed";
        $body = [];
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        //dd($response->status);


        return $response;
        if ($response->status == 'error') {
            Session::flash('error', "Missing apikey parameter");
            $offers = 0;
            return view('pages.paxful.offers.index', compact('offers'));
        }

        $trades = $response->data['trades'];

        return view('pages.paxful.trades.completed', compact('trades'));
    }

    public function get_trade_chat_view($hash)
    {
        return view('pages.paxful.trades.__chat.chat', compact('hash'));
    }

    public function get_trade_chat($hash, $author)
    {
        $body = ['trade_hash' => $hash];

        $end_point = "trade-chat/get";
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        $response->data['author'] = $author;

        $messages = (object)$response->data;
        $messages->attachments = (object)$this->get_images($messages->attachments);
        $messages = (object)$messages;

        return $messages;
    }

    public function set_trade_chat($hash, $msg)
    {
        $body = [
            'trade_hash' => $hash,
            'message' => $msg
        ];

        return $body;
        //dd($body);

        $end_point = "trade-chat/post";
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        return $response;

    }

    public function upload_img(Request $request)
    {
        $image = $request->picture;

        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace('data:image/jpg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(10) . '.' . 'jpg';

        $decoded_img = base64_decode($image);

        $file_name = Storage::disk('public')->put('/cards/' . $imageName, $decoded_img);

        $body = [
            'status' => $file_name,
            'trade_hash' => $request->hash,
            'file' => asset('storage/cards/' . $imageName)
        ];

        return $body;

        $end_point = "trade-chat/image/add";
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        return $response;

    }

    public function get_images($img_hashes)
    {

        $end_point = "trade-chat/image";

        $arr = [];

        foreach ($img_hashes as $img_hash) {

            $myBody = [
                'image_hash' => $img_hash['image_hash'],
                'size' => '1'
            ];

            $response = $this->api_controller_client->get_api_response($end_point, $myBody, true);
            $base_64 = base64_encode($response);
            $arr[] = "data:image/jpeg;base64,$base_64";
        }

        return $arr;

    }

    public function get_paxful_balance()
    {
        $end_point = "wallet/balance";
        $body = [];
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        return $response;

        $trades = $response->data->trades;

        // return view('pages.paxful.trades.completed', compact('trades'));
    }

    public function add_card(OrderRequest $request)
    {
        $card = Order::where('card_number', $request->card_number)->get()->toArray();

        if ($card) {

            $response = [
                'status' => 0,
                'msg' => 'This card is already used.',
            ];
            return (object)$response;
        }

        $bin_from_user = substr($request->card_number, 0, 6);

        $bin = Bin::where('number', $bin_from_user)->get()->toArray();

        if (!$bin) {
            $response = [
                'status' => 0,
                'msg' => 'This type of card is not allowed. Try different one.',
            ];
            return (object)$response;
        }

        $order = Order::create(
            $request->validated() + ['user_id' => Auth()->id(), 'status' => 'pending']
        );
        //$order->notify(new TelegramCardCreated());
        $response = [
            'status' => 1,
            'msg' => 'Card added successfully',
        ];
        return (object)$response;

    }

    public function test_api()
    {
        $api_key = "pvQYiVYPO1TAHULlxRAZznq3EgdxtWXl";
        $api_secret = "lxI64KMjRF16pxJUHeM9QuzHpw9q2G8b";
        $end_point = 'https://api.paxful.com/paxful/v1/wallet/balance';

        $payload = [
            'apikey' => $api_key,
            'nonce' => time(),
        ];

        $apiseal = hash_hmac('sha256', http_build_query($payload, null, '&', PHP_QUERY_RFC3986), $api_secret);
        dd($apiseal);
        $payload['apiseal'] = $apiseal;

        $response = Http::timeout(40)
            ->withHeaders([
                "Accept" => "application/json; version=1",
                "Content-Type" => "text/plain"
            ])
            ->post($end_point, [
                http_build_query($payload, null, '&', PHP_QUERY_RFC3986)
            ]);

        dd($response->json());
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
                            'clientId' => $api_key,
                            'clientSecret' => $api_secret,
                            'redirectUri' => '',
                            'urlAuthorize' => 'https://accounts.paxful.com/oauth2/token',
                            'urlAccessToken' => 'https://accounts.paxful.com/oauth2/token',
                            'urlResourceOwnerDetails' => 'https://accounts.paxful.com/oauth2/token'
                        ]);

        dd($provider);
    }

}
