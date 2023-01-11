<?php

namespace App\Http\Controllers;


use App\Http\Requests\OrderRequest;
use App\Models\Bin;
use App\Models\Gateway;
use App\Models\Order;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TelegramCardCreated;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\Api\PaxfulAPIController;
use App\Http\Controllers\Api\TransactionGatewayController;

class PaxfulTrades extends Controller
{
    public $api_controller_client;
    public $access_token;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->key = Auth::user()->paxful_api_key();
            $this->api_secret = Auth::user()->paxful_api_secret();

            if (!(empty($this->key) || empty($this->api_secret))) {
                $this->api_controller_client = new PaxfulAPIController($this->access_token, $this->key, $this->api_secret);
                return $next($request);
            } else {
                Redirect::to('/dashboard')->send();
            }

        });


//        $this->middleware(function ($request, $next) {
//
//            $this->access_token = Auth::user()->paxful_token();
//
//            if (!$this->access_token) { // $this->access_token->hasExpired() ||
//a
//                $this->key = Auth::user()->paxful_api_key();
//                $this->api_secret = Auth::user()->paxful_api_secret();
//
//                if (!(empty($this->key) || empty($this->api_secret))) {
//
//                    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
//                        'clientId' => $this->key,
//                        'clientSecret' => $this->api_secret,
//                        'redirectUri' => '',
//                        'urlAuthorize' => 'https://accounts.paxful.com/oauth2/token',
//                        'urlAccessToken' => 'https://accounts.paxful.com/oauth2/token',
//                        'urlResourceOwnerDetails' => 'https://accounts.paxful.com/oauth2/token'
//                    ]);
//
//                    $response = $provider->getAccessToken('client_credentials');
//
//                    $this->access_token = $response->getToken();
//
//                    Auth::user()->metas()->updateOrCreate(
//                        ['meta_key' => 'paxful_token'],
//                        ['meta_value' => $this->access_token]);
//
//
//                } else {
//
//                    $this->access_token = 'abcd';
//                }
//            }
//
//
//            $this->api_controller_client = new PaxfulAPIController($this->access_token);
//            return $next($request);
//
//
//        });

    }

    public function getAllOffers()
    {
        $end_point = "offer/list";
        $body = ['offer_type' => 'buy'];
        $response = $this->api_controller_client->get_api_response($end_point, $body);

//dd($response->status);
        if ($response->status == 'error') {
            Session::flash('error', "Missing apikey parameter");
            $offers = 0;
            return view('pages.paxful.offers.index', compact('offers'));
        }

        $offer_buy = $response->data->offers;

        $body = ['offer_type' => 'sell'];
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        $offers_sell = $response->data->offers;

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

        //dd($body, "Activated");
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

        //dd($body, "Deactivated");
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
        $msg = "coin released for trade " . $hash . " by " . Auth::user()->name;
        app('log')->channel('trades')->info($msg);
        $end_point = "trade/release";
        $body = [
            'trade_hash' => $hash,
        ];
      // return $body;
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        $response_for_log = json_encode($response);
        app('log')->channel('trades')->info($response_for_log);

        if ($response->status == 'success') {

            $response = [
                'status' => 1,
                'msg' => 'Successfully Released',
            ];

        } else {
            $response = [
                'status' => 0,
                'msg' => 'Something went wrong with Paxful',
            ];
        }
        return (object)$response;
    }

    public function dispute(Request $request, $hash, $reason)
    {
        $msg = "Dispute opened for trade " . $hash . " --- " . $reason . " by " . Auth::user()->name;
        app('log')->channel('trades')->info($msg);
        $end_point = "trade/dispute";
        $body = [
            'trade_hash' => $hash,
            'reason' => $reason,
        ];
        //return $body;
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        $response_for_log = json_encode($response);
        app('log')->channel('trades')->info($response_for_log);

        if ($response->status == 'success') {

            $response = [
                'status' => 1,
                'msg' => 'Successfully Disputed',
            ];

        } else {
            $response = [
                'status' => 0,
                'msg' => $response->status,
            ];
        }
        return (object)$response;
    }

    public function getActiveTrades()
    {
        $order_controller = new OrderController();
        $open = $order_controller->is_open_hour();
        $msg_title = '';
        $msg = '';

        if (!$open) {
            $msg_title = Settings::where('meta_key', 'business_msg_title')->get()->toArray()[0]['meta_value'];
            $msg = Settings::where('meta_key', 'business_msg')->get()->toArray()[0]['meta_value'];
        }
        $open = true;
        return view('pages.paxful.trades.index', compact('open', 'msg_title', 'msg'));
    }

    public function getActiveTrades2($load_images)
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
        $trades = $response->data->trades;

        //$trades = (object)$trades;
        //$author = $trades->owner_username;


        $count = 0;
        foreach ($trades as $trade) {
            $count++;
//            if($count<5) continue;
            $author = $trade->owner_username;

            $data[$trade->trade_hash] [] = (object)$trade;
            $data[$trade->trade_hash] [] = $this->get_trade_chat($trade->trade_hash, $author, $load_images);

            //  dd($trade->trade_hash);
            //  break;
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


        //return $response;
        if ($response->status == 'error') {
            Session::flash('error', "Missing apikey parameter");
            $offers = 0;
            return view('pages.paxful.offers.index', compact('offers'));
        }

        $trades = $response->data->trades;



        return view('pages.paxful.trades.completed', compact('trades'));
    }

    public function get_trade_chat_view($hash, $author)
    {

        return view('pages.paxful.trades.__chat.chat', compact('hash', 'author'));
    }

    public function get_trade_chat($hash, $author, $load_images)
    {
        $body = ['trade_hash' => $hash];

        $end_point = "trade-chat/get";
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        //dd($response);
        $response->data->author = $author;

        $messages = (object)$response->data;

        $messages->attachments = (object)$this->get_images($messages->attachments, $load_images);


        $messages = (object)$messages;

        return $messages;
    }

//    public function get_chat_for_single_trade($hash)
//    {
//        $body = ['trade_hash' => $hash];
//
//        $end_point = "trade-chat/get";
//        $response = $this->api_controller_client->get_api_response($end_point, $body);
//
//        $response_for_log = json_encode($response);
//        app('log')->channel('trades')->info($response_for_log);
//
//        $messages = $response->data;
//
//
//        return $messages;
//    }

    public function set_trade_chat($hash, $msg)
    {
        $body = [
            'trade_hash' => $hash,
            'message' => $msg
        ];

        $msg = "Msg sent: " . $msg . " ----- hash:". $hash . " by " . Auth::user()->name;
        app('log')->channel('trades')->info($msg);

        //return $body;
        //dd($body);

        $end_point = "trade-chat/post";
        $response = $this->api_controller_client->get_api_response($end_point, $body);

        $response_for_log = json_encode($response);
        app('log')->channel('trades')->info($response_for_log);

        return $response;

    }

    public function upload_img(Request $request)
    {
        $msg = "Img Uploaded:  ----- hash:". $request->hash . " by " . Auth::user()->name;
        app('log')->channel('trades')->info($msg);

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

        //return $body;

        $end_point = "trade-chat/image/add";
        $response = $this->api_controller_client->get_api_response($end_point, $body);
        return $response;

    }

    public function get_images($img_hashes, $load_images)
    {
        $arr = [];
        if (!$load_images) {
            return $arr;
        }

        $end_point = "trade-chat/image";

        foreach ($img_hashes as $img_hash) {

            $myBody = [
                'image_hash' => $img_hash->image_hash,
                'size' => '1'
            ];

            $response = $this->api_controller_client->get_api_response($end_point, $myBody, true);

            $base_64 = base64_encode($response);
            $arr[] = "data:image/jpeg;base64,$base_64";
        }

        //$arr = array_reverse($arr);

        return $arr;

    }

    public function upload_img_with_public_url(Request $request)
    {
        $body = [
            'trade_hash' => $request->hash,
            'file' => $request->file_url
        ];

        return $body;

//        $end_point = "trade-chat/image/add";
//        $response = $this->api_controller_client->get_api_response($end_point, $body);
//        return $response;

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

    public function get_balance_screenshot(OrderRequest $request)
    {
        $order_controller = new OrderController();

        $response = $order_controller->check_balance($request->card_number, $request->month, $request->year, $request->cvc);
        if ($response->success) {
            $screenshot = $response->screenshot->image;
            $balance = $response->data->balance;

            if ($balance < $request->amount) {

                $response = [
                    'status' => 0,
                    'msg' => "You don't have sufficient balance. Current balance " . $balance,
                    'image' => $screenshot,
                    'balance' => $balance,
                ];

            } else {
                $response = [
                    'status' => 1,
                    'msg' => "You current balance is: " . $balance,
                    'image' => $screenshot,
                    'balance' => $balance,
                ];
            }

            return (object)$response;


        } else {
            if (isset($response->screenshot)) {
                $screenshot = $response->screenshot->image;

                $response = [
                    'status' => 0,
                    'msg' => $response->message,
                    'image' => $screenshot,
                ];
            } else {
                $response = [
                    'status' => 0,
                    'msg' => $response->message,
                ];
            }

        }

        return (object)$response;

    }

    public function add_card(OrderRequest $request)
    {
        $card = Order::where('card_number', $request->card_number)->get()->toArray();

        if ($card) {

            $response = [
                'status' => 0,
                'msg' => 'This card cannot be submitted again',
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

        return $this->send_from_paxful_to_paylanze_gateway($request);

    }

    public function send_from_paxful_to_paylanze_gateway($request)
    {
        $user_gateway = Auth::user()->gateway();

        if ($user_gateway > 0) {
            $gateway = Gateway::where('id', $user_gateway)->get()->first();
        } else {
            $gateway = Gateway::where('id', 1)->get()->first();
        }

        $sk = $gateway->api_key;

        $tr_api = new TransactionGatewayController($sk);
        $response = $tr_api->doSale($request->amount, $request->card_number, $request->month . '' . $request->year, $request->cvc);

        $response['card_number'] = $request->card_number;
        app('log')->channel('gateway_transactions')->info($response);

        if ($response['response'] == 1) {

            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'accepted', 'status_update_reason' => 'success', 'processed_by' => $gateway->id, 'transaction_id' => $response['transactionid']]);

            $response = [
                'status' => 1,
                'msg' => 'Card processed successfully',
            ];


            app('log')->channel('gateway_transactions')->info('Card: ' . $request->card_number . 'was added by paxful' );

        } else if (str_contains($response['responsetext'], 'NSF')) {
            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'declined', 'status_update_reason' => 'NSF', 'processed_by' => $gateway->id]);

            $response = [
                'status' => 0,
                'msg' => 'Declined ! Insufficient funds',
            ];
             app('log')->channel('gateway_transactions')->info('Card: ' . $request->card_number . 'was added by paxful' );

        } else {

            $order_controller = new OrderController();
            if ($order_controller->is_open_hour()) {

                $response = $this->send_to_colin_from_paxful($request);

            } else {
                $order = Order::create(
                    $request->validated() + ['user_id' => Auth()->id(), 'status' => 'canceled', 'status_update_reason' => 'colin_not_available passed_from_gateway', 'processed_by' => '0']);

                $response = [
                    'status' => 0,
                    'msg' => 'Our manual gateway is currently offline. Please try again later',
                ];

                 app('log')->channel('gateway_transactions')->info('Card: ' . $request->card_number . 'was added by paxful' );
            }
        }

        return (object)$response;
    }

    public function send_to_colin_from_paxful($request)
    {

        $order = Order::create(
            $request->validated() + ['user_id' => Auth()->id(), 'status' => 'pending', 'processed_by' => '0'] // 0 = Colin
        );
         app('log')->channel('gateway_transactions')->info('Card: ' . $request->card_number . 'was added by paxful' );
        if (env('APP_ENV') != 'local') {
            $order->notify(new TelegramCardCreated());
        }

        $response = [
            'status' => 1,
            'msg' => 'Your card will be processed manually shortly',
        ];
        return $response;
    }

    public function check_card_status($card_number)
    {
        $card = Order::select('status')
            ->where('card_number', $card_number)->first();

        if ($card) {
            return $card->status;
        }
    }

}
