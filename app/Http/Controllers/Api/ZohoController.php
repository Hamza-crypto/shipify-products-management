<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ZohoController extends Controller
{
    public function users()
    {
        return User::all();
    }

    public function orders($id)
    {
        return Order::with('user')
            ->where('user_id', $id)
                ->get();
    }
}
