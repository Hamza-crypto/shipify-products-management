<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\TransactionGatewayController;
use App\Http\Requests\OrderRequest;
use App\Models\Batch;
use App\Models\Bin;
use App\Models\Card;
use App\Models\Gateway;
use App\Models\Order;
use App\Models\OrderCategory;
use App\Models\Screenshot;
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

        return view('pages.order.index', compact('order_status', 'users', 'average'));
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

        $categories = OrderCategory::all();

        $non_available_users = UserMeta::select('user_id')
            ->where('meta_key', 'availability')
            ->where('meta_value', 0)
            ->get()->toArray();

        $non_available_categories = UserMeta::select('meta_value')
            ->whereIn('user_id', $non_available_users)
            ->where('meta_key', 'order_category')
            ->get()->toArray();

        $non_available_categories_array = [];
        foreach ($non_available_categories as $category) {
            $non_available_categories_array[] = $category['meta_value'];
        }


        //dd($non_available_categories);

        return view('pages.order.add', compact('open', 'msg_title', 'msg', 'categories', 'non_available_categories_array'));
    }


    public function store(OrderRequest $request)
    {
        $card = Order::where('card_number', $request->card_number)->get()->toArray();

        // if ($card) {
        //     Session::flash('error', 'This card is already used');
        //     return redirect()->back()->withInput($request->all());
        // }

        $bin_from_user = substr($request->card_number, 0, 6);

        $bin = Bin::where('number', $bin_from_user)->get()->toArray();

//        if (!$bin) {
//            Session::flash('error', 'This type of card is not allowed. Try different one.');
//            return redirect()->back()->withInput($request->all());
//        }

        $response = $this->check_balance($request->card_number, $request->month, $request->year, $request->cvc);
        //dd($response);

        if ($response->success) {
            $screenshot = $response->screenshot->image;
            $balance = $response->data->balance;
            //dd($balance, $screenshot);

            if ($balance < $request->amount) {
                Session::flash('error', "You don't have sufficient balance. Current balance " . $balance);
                return redirect()->back()->withInput($request->all() + ['image' => $screenshot]);
            }

            $this->send_to_paylenze_gateway($request, $screenshot);

        } else {
            $screenshot = null;
            $this->send_to_paylenze_gateway($request, $screenshot);

        }

        return redirect()->back()->withInput($request->all());

    }

    public function send_to_paylenze_gateway($request, $screenshot)
    {
        $user_gateway = Auth::user()->gateway();

        if ($user_gateway > 0) {
            $gateway = Gateway::where('id', $user_gateway)->get()->first();
        } else {
            $gateway = Gateway::where('id', 1)->get()->first();
        }

        if ($gateway) {

            $sk = $gateway->api_key;

            $tr_api = new TransactionGatewayController($sk);
            $response = $tr_api->doSale($request->amount, $request->card_number, $request->month . '' . $request->year, $request->cvc);
            $response['card_number'] = $request->card_number;

            app('log')->channel('gateway_transactions')->info($response);
            if ($response['response'] == 1) {

                $order = Order::create(
                    $request->validated() + ['user_id' => Auth()->id(), 'status' => 'accepted', 'status_update_reason' => 'success', 'processed_by' => $gateway->id, 'transaction_id' => $response['transactionid'], 'balance_screenshot' => $screenshot]);
                Session::flash('success', 'Card processed successfully');

            } else if (str_contains($response['responsetext'], 'NSF')) {

                $order = Order::create(
                    $request->validated() + ['user_id' => Auth()->id(), 'status' => 'declined', 'status_update_reason' => 'NSF', 'processed_by' => $gateway->id, 'balance_screenshot' => $screenshot]);
                Session::flash('error', $response['responsetext']);
            } else {
                if ($this->is_open_hour()) {

                    $order = Order::create(
                        $request->validated() + ['user_id' => Auth()->id(), 'status' => 'pending', 'processed_by' => '0', 'balance_screenshot' => $screenshot] // 0 = Colin
                    );
                    if (env('APP_ENV') != 'local') {
                        $order->notify(new TelegramCardCreated());
                    }
                    Session::flash('warning', "Your card will be processed manually shortly.");
                } else {
                    Session::flash('error', "Our manual gateway is currently offline. Please try again later");
                }

            }

        }


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

        if (env('APP_ENV') != 'local') {
            if (Auth()->user()->id != 8) {
                $this->send_transaction_to_zoho($order);
            }
        }


        Session::flash('success', __('Status updated successfully'));
        return redirect()->back();
    }

    public function reject_card_status(Request $request, Order $order)
    {
        $order->status = 'declined';
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
        //$active = Settings::where('meta_key', 'open_status')->get()->toArray()[0]['meta_value'];

        //return $active;

//        if ($active) { // $active = 1   Activated by admin
//
//            return true; // Means site is available for all the time
//        }

        $time_zone = 'America/New_York';
        //$time_zone = 'Asia/Karachi';

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
        $client_id = '1000.NKP2E4U73SBAX6R06MV1JGO6HJBQCR';
        $client_secret = 'cf3d35d67aad287413e03e0f9eef07f22afe781e48';
        $organization_id = '717975227';
        $access_token = '1000.84335aebe90eb09e8fa7aeada1a1da28.dd5875b2b440003f1fb7001c716c44f1';
        $refresh_token = '1000.6ed2b87d3d587135fdd553e5054f391a.a5fe37014d58a2bd1b48da521fc57f01';

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

    public function check_balance($card, $month, $year, $cvc)
    {
        $end_point = "http://35.238.47.14/checkCard?token=26241a26f4cc3f7d8dbe31031042a171&number=$card&month=$month&year=$year&cvv=$cvc&screenshot=1";
        $response = Http::get($end_point);
        $response_for_log = $response->body() . " card : " . $card;

        app('log')->channel('balance_checking')->info( $response_for_log);
        return json_decode($response);
    }


}
