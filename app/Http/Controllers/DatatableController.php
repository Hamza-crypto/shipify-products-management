<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;

class DatatableController extends Controller
{

    public function orders(Request $request)
    {

        $request_user_id = $request->user;

        if ($request_user_id != -100 && $request_user_id != null) {
            $request_user_rate = User::find($request_user_id)->rate();
        } else {
            $request_user_rate = 89;
        }

        $user = Auth()->user();
        $role = $user->role;

        if ($role != 'admin') {
            $request_user_rate = $user->rate();
        }

        $dbColumns = [
            1 => "amount",
            0 => "created_at"
        ];

        if ($role == 'user') {
            $totalData = Order::with('gateway','user')
                ->filters($request->all())
                ->WhereHas('user', function ($q2) use ($user) {
                    $q2->where('id', $user->id);
                })->count();
        } else {
            $totalData = Order::with('user')
                ->filters($request->all())->count();
        }

        // Widget for Orders index page
        $totalOrderCount = ['accepted' => 0, 'pending' => 0, 'declined' => 0];


        $totalFiltered = $totalData;

        $start = $request->length == -1 ? 0 : $request->start;
        $limit = $request->length == -1 ? $totalData : $request->length;
//        $orderColumnIndex = $request->input('order.0.column');
//        $orderDbColumn = $dbColumns[$orderColumnIndex];
//        $orderDirection = $request->input('order.0.dir');

        if ($role == 'user') {
            $orders = Order::with('user')
                ->filters($request->all())
                ->WhereHas('user', function ($q2) use ($user) {
                    $q2->where('id', $user->id);
                });
        } else {
            $orders = Order::with('gateway','user')
                ->filters($request->all());
        }

        //$orders = $orders->orderBy($orderDbColumn, $orderDirection);

        if (empty($request->input('search.value'))) {

            $totalOrders = $orders->get(); //if any filter is selected , then count according to that filter
            $total_sum = $totalOrders->sum('amount');

            $accepted_amount = $totalOrders->where('status', 'accepted')->sum('amount');
            $rejected_amount = $totalOrders->where('status', 'declined')->sum('amount');
            $pending_amount = $totalOrders->where('status', 'pending')->sum('amount');

            $orders = $orders->offset($start)->limit($limit)->get();
//            dd($total_sum,$accepted_amount, $rejected_amount, $pending_amount,   $orders, $start, $limit);

        } else {
            $search = $request->input('search.value');
            $orders = $orders->filters($request->all());
            $orders = $orders->where(function ($q1) use ($search) {
                $q1->where('id', 'LIKE', "%$search%")
                    ->orWhere('card_number', 'LIKE', "%$search%")
                    ->orWhere('amount', 'LIKE', "%$search%");
            })
                ->get();

            $totalOrders = $orders; //if any filter is selected , then count according to that filter
            $total_sum = $totalOrders->sum('amount');

            $accepted_amount = $orders->where('status', 'accepted')->sum('amount');
            $rejected_amount = $orders->where('status', 'declined')->sum('amount');
            $pending_amount = $orders->where('status', 'pending')->sum('amount');

            $totalFiltered = count($orders);
            $orders = $orders->skip($start)->take($limit);
        }

        //dd($orders->toArray());
        foreach ($totalOrders as $order) {
            $totalOrderCount[$order->status] = ($totalOrderCount[$order->status] ?? 0) + 1;
        }

        $data = [];

        foreach ($orders as &$order) {
            $order->DT_RowId = $order->id;
            $order->null = "";
            // $order->created_at_new = '
            // <span title="' . $order->created_at . '"> ' . $order->created_at->diffForHumans() . '</span>';

            $order->created_at_new = $order->created_at->format('Y-m-d h:i:s a');

            if(!empty($order->pin)){
                $order->month_year = '<span class="badge badge-info">StoreCard</span>';
            }
            else{
                $order->month_year = $order->month . '/' . $order->year;
            }


            if (($role == 'admin' || $role == 'user') && $order->paid_status() == 'paid') {
                $order->amount = '<span class="badge badge-' . $order->getPaidStatusColor() . '">' . $order->amount . '</span>';
            }

            //onclick="return confirmAction(this, \'Are you sure to accept this?\')"
            $accept = '<a href="' . route('order.accept', $order->id) . '" class="btn" style="display: inline" ><i class="fa fa-check text-success"></i></a>';
            $reject = '<a href="' . route('order.reject', $order->id) . '" class="btn" style="display: inline"><i class="fa fa-times text-danger"></i></a>';
            $void = '<a href="' . route('order.void', $order->id) . '" class="btn" style="display: inline"><i class="fa fa-bullseye text-info"></i></a>';

            $used = '<a href="' . route('order.used', $order->id) . '" class="btn" style="display: inline"><i class="fa fa-hand-holding-usd text-success"></i></a>';

            if ($role == 'admin' || $role == 'customer') {
                // Customer field is hidden for user_role ->customer using data-table
                $order->customer = '<a href="' . route('users.edit', $order->user->id) . '" target="_blank"> ' . $order->user->name . ' </a>';


            } else {
                $order->customer = '<span class=""> ' . $order->user->name . '</span>';
            }

            $edit = '<a href="' . route('orders.edit', $order->id) . '" class="btn" style="display: inline"><i class="fa fa-edit text-info"></i></a>';
            $alertTitle = __("Are you sure you want to Delete") . ' ' . $order->card_number . ' card?';
            $delete = '
                    <form method="post" action="' . route('orders.destroy', $order->id) . '" style="display: inline"
                        onsubmit="return confirmSubmission(this, \'' . $alertTitle . '\')">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn text-danger" href="' . route('orders.destroy', $order->id) . '"><i class="fa fa-trash"></i></button>
                    </form>
                    ';

            if ($role == 'admin' && $order->used_status() == 'used') {
                $order->card_number = '<span class="badge badge-' . $order->getUsedStatusColor() . '">' . $order->card_number . '</span>';
            }

            if ($role == 'admin') {
                if (in_array($order->status, ['accepted', 'pending'])) {
                    $order->actions .= $reject;
                }
                if (in_array($order->status, ['declined', 'pending'])) {
                    $order->actions .= $accept;
                }

                $order->actions .= $edit;
                $order->actions .= $delete;
                $order->actions .= $void;
                $order->actions .= $used;



            } elseif ($role == 'customer') {

                if ($order->status == 'pending') {
                    $order->actions .= $reject;
                    $order->actions .= $accept;
                    $order->actions .= $edit;
                    $order->actions .= $delete;

                } else if ($order->status == 'accepted') {
                    $order->actions .= $reject;
                    $order->actions .= $edit;

                } else if ($order->status == 'void') {
                    $order->actions .= $reject;
                    $order->actions .= $accept;

                } else if ($order->status == 'declined') {
                    $order->actions .= $accept;
                    $order->actions .= $void;
                }
                 $order->actions .= $used;




            } else {

                if ($order->status == 'pending') {

                    $order->actions .= $edit;
                    $order->actions .= $delete;
                } else {
                    $order->actions .= 'No further action required';
                }

            }


            if( in_array($order->processed_by,['0',''] ) == 0 ){ // not processed by Colin

                if ($role == 'admin') {
                    if($order->status_update_reason == 'success'){
                        $order->actions .= '<span class="badge badge-success"> '. $order->gateway->title .' </span>'; // appending in action list
                    }
                    else{
                        $order->actions .= '<span class="badge badge-danger"> '. $order->gateway->title .' </span>';
                    }
                }
                else{
                    if($order->status_update_reason == 'success'){
                        $order->actions = '<span class="badge badge-success"> Auto processed </span>'; // assigning option list
                    }
                    else{
                        $order->actions = '<span class="badge badge-danger"> Auto processed </span>';
                    }
                }


            }


            $status_time = '';
            if (!is_null($order->status_updated_at)) {
                $status_time = $order->created_at->diffInMinutes($order->status_updated_at);
                $status_time .= " minutes";
            }

            //$order->status_updated_at = !(is_null($order->status_updated_at)) ? $order->status_updated_at->diffForHumans() : '';
            $order->status = '<span class="badge badge-' . $order->getStatusColor() . '">' . $order->status() . '</span> <br> <span> ' . $status_time . '</span>';
            $data[] = $order;
        }


        $extraInfo = [
            'total_orders_count' => $totalFiltered,
            'orders_status_accepted' => $totalOrderCount['accepted'],
            'orders_status_pending' => $totalOrderCount['pending'],
            'orders_status_declined' => $totalOrderCount['declined'],
            'orders_total_sum' => round($total_sum, 2),
            'amount_accepted' => round($accepted_amount, 2),
            'amount_rejected' => round($rejected_amount, 2),
            'amount_pending' => round($pending_amount, 2),
            'user_rate' => $request_user_rate
        ];


        $data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            'data' => $data,
            'extra_info' => $extraInfo
        ];

        return response()->json($data);


    }


}
