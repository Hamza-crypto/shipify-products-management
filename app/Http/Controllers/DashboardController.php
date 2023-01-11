<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserMeta;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $date_7th_day_from_now = Carbon::now()->subDays(7);

        $orders_with_day = Order::select(['amount', 'status', 'created_at'])
            ->where('created_at', '>=', $date_7th_day_from_now)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d');
            });

        $startDate = now(); // Today
        $format = 'd';
        $endDate = $date_7th_day_from_now;
        $periods = \Carbon\CarbonPeriod::create($endDate, $startDate);
        $datePeriods = [];
        $datePeriods2 = [];
        foreach ($periods as $date) {
            $datePeriods[] = $date->format($format);
            $datePeriods2[] = $date->format('d M');
        }
        $labels = $datePeriods2;

        $accepted_array = [];
        $rejected_array = [];
        $pending_array = [];

        foreach ($orders_with_day as $day => $orders) {
            $accepted_array[$day] = $orders->where('status', 'accepted')->sum('amount');
            $rejected_array[$day] = $orders->where('status', 'declined')->sum('amount');
            $pending_array[$day] = $orders->where('status', 'pending')->sum('amount');
        }

        foreach ($datePeriods as $index => $d) {
            if (!isset($pending_array[$d])) {
                $pending_array[$d] = 0;
                $rejected_array[$d] = 0;
                $accepted_array[$d] = 0;

            }
        }

        ksort($rejected_array);
        ksort($accepted_array);
        ksort($pending_array);

        $pending = $pending_array;
        $accepted = $accepted_array;
        $rejected = $rejected_array;


       //dd($labels,$accepted_array, $pending_array, $rejected_array);

            $customer_status = UserMeta::select('meta_value')
            ->where('meta_key','availability')
            ->where('user_id', 2)
            ->first();

//        if($customer_status){
//            $customer_status = $customer_status->meta_value;
//        }
//        else{
//            $customer_status = 0;
//        }

        $order_controller = new OrderController();
        $customer_status = $order_controller->is_open_hour();


        return view('pages.dashboard.index', compact("rejected", "accepted", "pending","labels","customer_status"));


    }

}

























//foreach ($orders_with_day as $day => $orders) {
//    foreach ($orders as $order) {
//        $totalsale[$order->status] = ($totalsale[$order->status] ?? 0) + $order->amount;
//    }
//}
//
//$pending_sale = $totalsale['pending'];
//$accepted_sale = $totalsale['accepted'];
// dd($pending_sale);
