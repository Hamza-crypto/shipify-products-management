<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\GiftCashController;
use App\Http\Controllers\Api\TransactionGatewayController;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\StoreCardRequest;
use App\Models\Batch;
use App\Models\Bin;
use App\Models\Card;
use App\Models\Gateway;
use App\Models\Order;
use App\Models\OrderCategory;
use App\Models\Screenshot;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\Settings;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\TelegramCardCreated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Notifiable;

    public function index()
    {
        $order_status = ['accepted', 'pending', 'declined', 'canceled'];
        $users = User::all();

        $orders = Order::whereNotNull('status_updated_at')->get();
        $order_acceptance_rate = [];

        foreach ($orders as $order) {
            $status_time = $order->created_at->diffInMinutes($order->status_updated_at);
            $order_acceptance_rate[] = $status_time;
        }

        if (count($order_acceptance_rate)) {
            $average = array_sum($order_acceptance_rate) / count($order_acceptance_rate);
        } else {
            $average = 0;
        }
        $average = (int)round($average);

        $gateways = Gateway::all();
        $gateways = $gateways->sortBy('id');

        $user = Auth::user();
        if ($user->role == 'manager') {
            $tags = Tag::where('user_id', $user->id)->get();

            $users = User::where('parent_id', $user->id)->get(); //For Drop-down menu

        } elseif ($user->parent_id) {
            $tags = Tag::where('user_id', $user->parent_id)->get();
        } else {
            $tags = Tag::where('user_id', 0)->get();
        }

        return view('pages.order.index', compact('order_status', 'users', 'average', 'gateways', 'tags'));
    }


    public function create()
    {
        $open = $this->is_open_hour();
        $msg = "";
        $msg_title = "";
        if (!$open) {
            $msg_title = Settings::where('meta_key', 'business_msg_title')->get()->toArray()[0]['meta_value'];
            $msg = Settings::where('meta_key', 'business_msg')->get()->toArray()[0]['meta_value'];
        }

        $user = Auth::user();

        if ($user->role == 'manager') {
            $tags = Tag::where('user_id', $user->id)->get();
        } elseif ($user->parent_id) {
            $tags = Tag::where('user_id', $user->parent_id)->get();
        } else {
            $tags = Tag::where('user_id', 0)->get();
        }

        //dd($tags);


//        $categories = OrderCategory::all();
//
//        $non_available_users = UserMeta::select('user_id')
//            ->where('meta_key', 'availability')
//            ->where('meta_value', 0)
//            ->get()->toArray();
//
//        $non_available_categories = UserMeta::select('meta_value')
//            ->whereIn('user_id', $non_available_users)
//            ->where('meta_key', 'order_category')
//            ->get()->toArray();
//
//        $non_available_categories_array = [];
//        foreach ($non_available_categories as $category) {
//            $non_available_categories_array[] = $category['meta_value'];
//        }

        return view('pages.order.add', compact('open', 'msg_title', 'msg', 'tags')); //categories
    }

    public function add_store_card()
    {
        $open = $this->is_open_hour();
        $msg = "";
        $msg_title = "";
        if (!$open) {
            $msg_title = Settings::where('meta_key', 'business_msg_title')->get()->toArray()[0]['meta_value'];
            $msg = Settings::where('meta_key', 'business_msg')->get()->toArray()[0]['meta_value'];
        }

        $user = Auth::user();

        if ($user->role == 'manager') {
            $tags = Tag::where('user_id', $user->id)->get();
        } elseif ($user->parent_id) {
            $tags = Tag::where('user_id', $user->parent_id)->get();
        } else {
            $tags = Tag::where('user_id', 0)->get();
        }

        return view('pages.order.storecard', compact('open', 'msg_title', 'msg', 'tags')); //categories
    }


    public function store(OrderRequest $request)
    {
        $msg = $request->card_number . " added by ID:" . Auth()->id() . " " . Auth()->user()->name;
        app('log')->channel('cards')->info($msg);

        if (!$request->has('tag') || $request->tag == 0) {
            $request->request->add(['tag' => null]);
        }

        $card = Order::where('card_number', $request->card_number)->orderBy('id', 'desc')->first();


//        if ($card) {
//            Session::flash('error', 'This card cannot be submitted again');
//            return redirect()->back()->withInput($request->all());
//        }

//        $bin_from_user = substr($request->card_number, 0, 6);
//        $bin = Bin::where('number', $bin_from_user)->get()->toArray();
//
//        if (!$bin) {
//            Session::flash('error', 'This type of card is not allowed. Try different one.');
//            return redirect()->back()->withInput($request->all());
//        }

        $gc = new GiftCashController();
        $gc->place_order($request);

        Order::create(
            $request->validated() + ['user_id' => Auth()->id(), 'status' => 'pending',  'processed_by' => 0, 'tag_id' => $request->tag]);

        Session::flash('success', 'You will be notified soon');
        return redirect()->back()->withInput($request->all());

    }


  public function store_storeCard(StoreCardRequest $request)
    {
        //dd($request->all());
        $msg = $request->card_number . " added by ID:" . Auth()->id() . " " . Auth()->user()->name;
        app('log')->channel('cards')->info($msg);

        if (!$request->has('tag') || $request->tag == 0) {
            $request->request->add(['tag' => null]);
        }

        $card = Order::where('card_number', $request->card_number)->orderBy('id', 'desc')->first();

        if ($card) {
            $card = $card->toArray();
            if (in_array(Auth::user()->id, [1,7,17, 18]) && $card['status'] != 'pending') { // Bitzombie

                $this->add_store_card_in_db($request);

            } else {
                Session::flash('error', 'This card cannot be submitted again');
            }
            return redirect()->back()->withInput($request->all());
        }

        $bin_from_user = substr($request->card_number, 0, 6);
        $bin = Bin::where('number', $bin_from_user)->get()->toArray();

        if (!$bin) {
            Session::flash('error', 'This type of card is not allowed. Try different one.');
            return redirect()->back()->withInput($request->all());
        }

        $this->add_store_card_in_db($request);

        return redirect()->back()->withInput($request->all());

    }


    public function show()
    {
        return view('pages.order.add');
    }

    public function edit(Order $order)
    {
        $activities = $order->activities()->latest()->get();
        return view('pages.order.edit', compact('order', 'activities'));
    }


    public function update(Request $request, Order $order)
    {
        $user = Auth()->user();
        if ($user->role == 'customer') {

            //$this->validate($request, ['status' => 'required']);

            if ($request->has('status')) {
                Order::updateOrCreate(
                    ['user_id' => $order->user_id, 'id' => $order->id],
                    ['status' => $request->input('status')]
                );
            }

            if ($request->filled('assist_note')) {

                Screenshot::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'order_id' => $order->id,
                        'assist_note' => $request->assist_note,
                    ]);
            }

        } else if ($user->role == 'user') {

            $this->validate($request, [
                'card_number' => 'required|numeric',
                'month' => 'required|numeric',
                'year' => 'required|numeric',
                'cvc' => 'required|numeric',
                'amount' => 'required|numeric',]);

            Screenshot::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'order_id' => $order->id,
                    'user_note' => $request->user_note,
                ]);


            Order::updateOrCreate(
                ['id' => $order->id],
                [
                    'card_number' => $request->card_number,
                    'month' => $request->month,
                    'year' => $request->year,
                    'cvc' => $request->cvc,
                    'amount' => $request->amount,
                ]
            );

            $this->create_log($request, $order);

        } else if ($user->role == 'admin') {

            $this->validate($request, [
                'card_number' => 'required|numeric',
                'month' => 'required|numeric',
                'year' => 'required|numeric',
                'cvc' => 'required|numeric',
                'amount' => 'required|numeric',]);

            Order::updateOrCreate(
                ['id' => $order->id],
                [
                    'card_number' => $request->card_number,
                    'month' => $request->month,
                    'year' => $request->year,
                    'cvc' => $request->cvc,
                    'amount' => $request->amount,
                    'status' => $request->input('status')
                ]
            );
        }

        Session::flash('success', __('Successfully updated'));
        return back();
    }


    public function destroy(Order $order)
    {
        $order->status = 'canceled';
        $order->status_update_reason = 'deleted';
        $order->save();
//        $order->delete();
        Session::flash('error', __('Successfully Deleted'));
        return redirect()->back();
    }

    public function accept_card_status(Request $request, Order $order)
    {
        $order->status = 'accepted';
        $order->status_updated_at = now();
        $order->used_status = 'used';
        $order->save();

        $userName = Auth()->user()->name;
        $msg = "Order: " . $order->card_number . " accepted by " . $userName;
        app('log')->channel('order_status')->info($msg);

        $Channel_ID = $order->user->channel_id();
        if ($Channel_ID) {
            if (env('APP_ENV') != 'local') {
                $order->notify(new OrderStatusUpdated());
            }
        }

//        if (env('APP_ENV') != 'local') {
//            if (Auth()->user()->id != 8) {
//                $this->send_transaction_to_zoho($order);
//            }
//        }


        Session::flash('success', __('Status updated successfully'));
        return redirect()->back();
    }

    public function reject_card_status(Request $request, Order $order)
    {
        $order->status = 'declined';
        $order->status_updated_at = now();
        $order->used_status = 'unused';
        $order->save();

        $userName = Auth()->user()->name;
        $msg = "Order: " . $order->card_number . " rejected by " . $userName;
        app('log')->channel('order_status')->info($msg);

        $Channel_ID = $order->user->channel_id();
        if ($Channel_ID) {
            if (env('APP_ENV') != 'local') {
                $order->notify(new OrderStatusUpdated());
            }
        }

        Session::flash('success', __('Status updated successfully'));
        return redirect()->back();
    }

    public function void_card_status(Request $request, Order $order)
    {
        $order->status = 'void';
        $order->status_update_reason = null;
        $order->processed_by = 0;
        $order->save();

        $userName = Auth()->user()->name;
        $msg = "Order: " . $order->card_number . " voided by " . $userName;
        app('log')->channel('order_status')->info($msg);

        $Channel_ID = $order->user->channel_id();
        if ($Channel_ID) {
            if (env('APP_ENV') != 'local') {
                $order->notify(new OrderStatusUpdated());
            }
        }

        Session::flash('success', __('Status updated successfully'));
        return redirect()->back();
    }

    public function change_used_status(Request $request, Order $order)
    {
        $order->used_status = 'used';
        $order->save();

        Session::flash('success', __('Successfully marked as used'));
        return redirect()->back();
    }

    public function mark_as_paid($order_ids, $action)
    {
        //it was giving me only last alphabat

        $operation = ($action == 'p') ? 'paid' : 'unpaid';
        $explode_id = array_map('intval', explode(',', $order_ids));

        $response = Order::whereIn('id', $explode_id)->update(['paid_status' => $operation]);
        var_dump($response);
        Session::flash('success', $response . " orders marked as " . $operation);

    }

    public function create_log(Request $request, Order $order)
    {
        $previous_order = $order->toArray();
        unset($previous_order['updated_at'], $previous_order['created_at'], $previous_order['status'], $previous_order['id'], $previous_order['user_id']);
        $request_data = $request->except(['_token', '_method', 'user_note']);

        $difference = array_diff_assoc($request_data, $previous_order);

        foreach ($difference as $key => $value) {
            $title = sprintf('%s changed from %s', $key, $order[$key]);
            order_activity($title, $order);
        }
    }

    public function is_open_hour()
    {
        return true;  //Enable this to open active hours
        //$active = Settings::where('meta_key', 'open_status')->get()->toArray()[0]['meta_value'];

        //return $active;

//        if ($active) { // $active = 1   Activated by admin
//
//            return true; // Means site is available for all the time
//        }

        $time_zone = 'America/New_York';
        $format = 'h:i:s a';

        $current_time = Carbon::createFromFormat($format, Carbon::now()->setTimezone($time_zone)->format($format), $time_zone);
        //$current_time = Carbon::createFromFormat($format, '01:02:00 am', $time_zone);

        //echo "Current time: " . $current_time . " " . $current_time->timezoneName;

        $start_time = Carbon::createFromFormat($format, '09:00:00 am', $time_zone);
        $end_time = Carbon::createFromFormat($format, '09:00:00 pm', $time_zone);

//        echo "<br>Start time: " . $start_time . " " . $start_time->timezoneName;
//        echo "<br>End time: " . $end_time . " " . $end_time->timezoneName . "<br>";

        if ($current_time->isWeekend()) return false;

        if ($current_time->gte($start_time) && $current_time->lte($end_time)) {
            //echo "open time";
            return true;
        } else {
            //echo "close time";
            return false;
        }


    }

    public function send_transaction_to_zoho($order)
    {
        $client_id = env('zoho_client_id');
        $client_secret = env('zoho_client_secret');
        $organization_id = env('zoho_organization_id');
        $access_token = env('zoho_access_token');
        $refresh_token = env('zoho_refresh_token');

        // setup the generic zoho oath client
        $oAuthClient = new \Weble\ZohoClient\OAuthClient($client_id, $client_secret);
        $oAuthClient->setRefreshToken($refresh_token);
        $oAuthClient->offlineMode();

        // Access Token
//        $accessToken = $oAuthClient->getAccessToken();
//        $isExpired = $oAuthClient->accessTokenExpired();

        // setup the zoho books client
        $client = new \Webleit\ZohoBooksApi\Client($oAuthClient);
        //$client->setOrganizationId($organization_id);

        // Create the main class
        $zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks($client);

        $data = [
            'vendor_id' => '2135745000000190018',
            'bill_number' => $order->id,
            "line_items" => [
                [
                    "name" => "Gift Cards-Ebay " . $order->card_number,

                    "account_id" => "2135745000000000370",
                    "account_name" => "Employee Advance",
                    "rate" => $order->amount,
                ],

            ],

        ];
        return $zohoBooks->bills->create($data);
//        $bills = $zohoBooks->bills->getList();
//        return $bills;

    }

    public function send_to_colin($request)
    {
        if ($this->is_open_hour()) {
            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'pending', 'processed_by' => '0', 'tag_id' => $request->tag] // 0 = Colin
            );
            if (env('APP_ENV') != 'local') {
                $order->notify(new TelegramCardCreated());
            }
            Session::flash('warning', "Your card will be processed manually shortly.");
        } else {
            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'canceled', 'status_update_reason' => 'colin_not_available passed_from_gateway', 'processed_by' => '0', 'tag_id' => $request->tag]);

            Session::flash('error', "Our manual gateway is currently offline. Please try again later");
        }

        return redirect()->back()->withInput($request->all());
    }

    public function send_to_card_processor($request)
    {
        if ($this->is_open_hour()) {
            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'pending', 'processed_by' => '0', 'tag_id' => $request->tag] // 0 = Colin
            );
            if (env('APP_ENV') != 'local') {
                $order->notify(new TelegramCardCreated());
            }
            Session::flash('warning', "Your card will be processed manually shortly.");
        } else {
            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'canceled', 'status_update_reason' => 'colin_not_available passed_from_gateway', 'processed_by' => '0', 'tag_id' => $request->tag]);

            Session::flash('error', "Our manual gateway is currently offline. Please try again later");
        }

        return redirect()->back()->withInput($request->all());
    }

    public function add_store_card_in_db($request)
    {
        if ($this->is_open_hour()) {

            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'type' => 'storecard',  'pin' => $request->pin, 'status' => 'pending', 'processed_by' => '6', 'tag_id' => $request->tag ]
            );
            if (env('APP_ENV') != 'local') {
                $order->notify(new TelegramCardCreated());
            }
            Session::flash('warning', "Your card will be processed manually shortly.");
        } else {
            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'canceled', 'status_update_reason' => 'out_of_time', 'processed_by' => '6', 'tag_id' => $request->tag]);

            Session::flash('error', "Our manual gateway is currently offline. Please try again later");
        }

        return redirect()->back()->withInput($request->all());
    }

    public function send_to_paylanze_gateway($request, $screenshot = null)
    {
        $user_gateway = Auth::user()->gateway();

        if ($user_gateway > 0) {
            $gateway = Gateway::where('id', $user_gateway)->get()->first();
        } else {
            $gateway = Gateway::where('id', 1)->get()->first();
        }

        $sk = '';
        if ($gateway->title == 'DAsim1') {
            $sk = env('GATEWAY_1');
        } elseif ($gateway->title == 'DAsim2') {
            $sk = env('GATEWAY_2');
        } elseif ($gateway->title == 'DAsim3') {
            $sk = env('GATEWAY_3');
        } elseif ($gateway->title == 'DAsim4') {
            $sk = env('GATEWAY_4');
        } elseif ($gateway->title == 'DAsim5') {
            $sk = env('GATEWAY_5');
        }
        //$sk = $gateway->api_key;

        $tr_api = new TransactionGatewayController($sk);
        $response = $tr_api->doSale($request->amount, $request->card_number, $request->month . '' . $request->year, $request->cvc);
        $response['card_number'] = $request->card_number;
        $response['user'] = Auth::user()->id . " - " . Auth::user()->name;

        app('log')->channel('gateway_transactions')->info($response);

        if ($response['response'] == 1) {

            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'accepted', 'status_update_reason' => 'success', 'processed_by' => $gateway->id, 'transaction_id' => $response['transactionid'], 'balance_screenshot' => $screenshot, 'tag_id' => $request->tag]);
            Session::flash('success', 'Card processed successfully');

        } else if (str_contains($response['responsetext'], 'NSF')) {

            $order = Order::create(
                $request->validated() + ['user_id' => Auth()->id(), 'status' => 'declined', 'status_update_reason' => 'NSF', 'processed_by' => $gateway->id, 'balance_screenshot' => $screenshot, 'tag_id' => $request->tag]);
            Session::flash('error', $response['responsetext']);
        } else {
            $this->send_to_colin($request);
        }

        return $gateway;
    }


}
