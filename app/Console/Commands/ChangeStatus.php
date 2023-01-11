<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\PostMessage;
use Illuminate\Support\Facades\Http;

class ChangeStatus extends Command
{
    protected $signature = 'update:order_status';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

//    $post_messages = PostMessage::with('group')
//        ->where('post_at' ,'<', Carbon::now()->subMinutes(0))
//        ->where('status' ,'pending')
//            ->get();

//    foreach ($post_messages as $post_message){
//$group_name = $post_message->group->name;
//$msg = $post_message->body;
// $post_message->status = 'done';
//        $post_message->save();
//        $url =  sprintf('https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s','2141686405:AAE5JP1L-4W6mgFocSaPnSVXSXpsDJsIWn0',$group_name,$msg);
//        $response = Http::get($url);
//        //dd($response);
//
//
//      //dd($url);
//    }
        /**
         * Store Cards
         */
        $minutes_offset = 60;
        echo "" . now();
        //dd(Carbon::now()->subMinutes(5));
        $orders = Order::with('user')
            ->where('created_at' ,'<', Carbon::now()->subMinutes($minutes_offset))
            ->where('status' , 'pending')
            ->where('type' , 'storecard')
            ->latest()
            ->get();


        foreach ($orders as $order) {
            $order->status = 'accepted';
            $order->status_updated_at = now();
            $order->save();
            $msg = "Order: " . $order->card_number . " accepted by Bot";
            app('log')->channel('order_status')->info($msg);
            $Channel_ID = $order->user->channel_id();
            if($Channel_ID){
                $order->notify(new OrderStatusUpdated());
                echo $Channel_ID;
            }
        }


        /**
         * Prepaid Cards
         */
        $minutes_offset = 15;
        echo "" . now();
        //dd(Carbon::now()->subMinutes(5));
        $orders = Order::with('user')
            ->where('created_at' ,'<', Carbon::now()->subMinutes($minutes_offset))
            ->where('status' , 'pending')
            ->where('type' , null)
            ->latest()
            ->get();


        foreach ($orders as $order) {
            $order->status = 'accepted';
            $order->status_updated_at = now();
            $order->save();
            $msg = "Order: " . $order->card_number . " accepted by Bot";
            app('log')->channel('order_status')->info($msg);
            $Channel_ID = $order->user->channel_id();
            if($Channel_ID){
                $order->notify(new OrderStatusUpdated());
                echo $Channel_ID;
            }
        }
        echo "Task Done";
    }
}
