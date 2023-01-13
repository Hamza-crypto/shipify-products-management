<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;

class DatatableController extends Controller
{

    public function products(Request $request)
    {
        $totalData = Product::filters($request->all())->count();

        $totalFiltered = $totalData;

        $start = $request->length == -1 ? 0 : $request->start;
        $limit = $request->length == -1 ? $totalData : $request->length;


        $products = Product::filters($request->all());

        if (empty($request->input('search.value'))) {
            $products = $products->offset($start)->limit($limit)->get();
        }
        $data = [];

        foreach ($products as &$product) {
            $product->DT_RowId = $product->id;
            $product->status = '<span class="badge badge-' . $product->getStatusColor() . '">' . $product->blacklist_status() . '</span>';
            $data[] = $product;
        }

        $data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            'data' => $data,
        ];

        return response()->json($data);


    }


}
