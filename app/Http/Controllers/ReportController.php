<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{

    public function payable(Request $request)
    {
        $request_user_id = $request->user;

        if ($request_user_id == null || $request_user_id == -100) {
            $request_user_id = 1;
        }
        $request_user_rate = User::find($request_user_id)->rate();
        $request_user_rate = $request_user_rate / 100;

        //dd($request_user_rate);
        $date_7th_day_from_now = Carbon::now()->subDays(40);

        $orders_with_day = Order::select(['amount', 'status', 'created_at'])
            ->filters($request->all())
            ->where('created_at', '>=', $date_7th_day_from_now)
            ->where('status', 'accepted')
            ->where('paid_status', 'unpaid')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d M');
            });

        //dd($orders_with_day);
        $data = [];
        foreach ($orders_with_day as $day => $orders) {
            $orders_array = [];
            $orders_array['date'] = $day;
            $sum = $orders->sum('amount');
            $orders_array['total_amount'] = $sum;
            $orders_array['payable_amount'] = $sum * $request_user_rate;

            $data[] = $orders_array;
        }

        $users = User::all();

        return view('pages.report.index', compact('data', 'users'));
    }

    public function daily(Request $request)
    {
        //dd($request->all());
        $request_date = $request->date;
        $request_gateway = $request->gateway;
        $request_user = $request->user;
        $request_manager = $request->manager;
        $request_sub_user = $request->sub_user;


        if ($request_date == null) {
            $request_date = 0;
        }

        if ($request_gateway == null) {
            $request_gateway = -1;
        }


        //dd($date);

        $orders = Order::with('gateway', 'user')
            // ->whereDate('created_at', '>=', $date)
            ->orderBy('created_at', 'DESC');


        if ($request_gateway == 0) {
            $orders = $orders->whereIn('processed_by', ['0', '']);
        }

        if ($request_gateway > 0 && $request_gateway < 999) { // 999 = 'Select gateway'
             $orders->where('processed_by', $request_gateway);
        }

        if ($request_user > 0) {
            $orders = $orders->where('user_id', $request_user);
        }


        if ($request_sub_user != 'null' && $request_sub_user != '' & $request_sub_user != 0) {
            $orders = $orders->where('user_id', $request_sub_user);
        } else {
            if ($request_manager > 0) {
                $orders = $orders->where('user_id', $request_manager);
            }
        }

        if ($request_date == 1000) {

            if (isset($request->daterange)) {

                $dateRange = explode(' - ', $request->daterange);

                $orders->whereBetween('orders.created_at', [\Carbon\Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
            }
        } else {
            $date = Carbon::now()->subDays($request_date)->format('Y-m-d');

            $orders->where('created_at', '>', $date);
        }

       // dd($request->all());

        if (isset($request->daterange)) {
            $dateRange = explode(' - ', $request->daterange);
            $orders->whereBetween('orders.created_at', [\Carbon\Carbon::parse($dateRange[0])->format('Y-m-d'), Carbon::parse($dateRange[1])->format('Y-m-d')]);
        }

        $orders = $orders->get();
        //dd($orders->toArray());

        $total_volume = $orders->sum('amount');
        $total_orders_count = $orders->count();

        if ($request_gateway > 0) {
            $g1_total_volume = $orders->where('processed_by', $request_gateway)->sum('amount');
        } else {
            $g1_total_volume = $total_volume;
        }

        $g2_total_volume = $orders->where('processed_by', 2)->sum('amount');

        //dd($request_gateway);


        $accepted_orders = $orders->where('status', 'accepted');
        $decline_orders = $orders->where('status', 'declined');
        $pending_orders = $orders->where('status', 'pending');
        $canceled_orders = $orders->where('status', 'canceled');

        $g1_accepted_orders = $accepted_orders
            ->where('processed_by', $request_gateway);

        $g1_decline_orders = $decline_orders
            ->where('processed_by', $request_gateway);

        $g1_pending_orders = $pending_orders
            ->where('processed_by', $request_gateway);

        $g2_accepted_orders = $accepted_orders
            ->where('processed_by', 2);

        $g2_decline_orders = $decline_orders
            ->where('processed_by', 2);

        $g2_pending_orders = $pending_orders
            ->where('processed_by', 2);


        //dd($g1_accepted_orders, $g1_decline_orders, $g1_pending_orders);
        $data = [
            'total_volume' => $total_volume,
            'total_orders_count' => $total_orders_count,

            'total_accepted_volume' => $accepted_orders->sum('amount'),
            'total_decline_volume' => $decline_orders->sum('amount'),
            'total_pending_volume' => $pending_orders->sum('amount'),
            'total_canceled_volume' => $canceled_orders->sum('amount'),

            'total_accepted_orders_count' => $accepted_orders->count(),
            'total_pending_orders_count' => $pending_orders->count(),
            'total_rejected_orders_count' => $decline_orders->count(),
            'total_canceled_orders_count' => $canceled_orders->count(),

            'g1_total_volume' => $g1_total_volume,
            'g1_accepted_orders' => $g1_accepted_orders->sum('amount'),
            'g1_decline_orders' => $g1_decline_orders->sum('amount'),
            'g1_pending_orders' => $g1_pending_orders->sum('amount'),

            'g2_total_volume' => $g2_total_volume,
            'g2_accepted_orders' => $g2_accepted_orders->sum('amount'),
            'g2_decline_orders' => $g2_decline_orders->sum('amount'),
            'g2_pending_orders' => $g2_pending_orders->sum('amount'),
        ];

        $gateways = Gateway::all();
        $gateways = $gateways->sortBy('id');

        $users = User::all();
        $managers = $users->where('role', 'manager');
        return view('pages.report.daily', compact('data', 'orders', 'gateways', 'users', 'managers'));
    }

    public function get_sub_users($id)
    {
        $users = User::where('parent_id', $id)->get();

        $option = '<option value="0"> Select User </option>';

        foreach ($users as $user) {
            $option .= '<option value="' . $user->id . '"> ' . $user->name . ' </option>';
        }

        return $option;
    }
}
