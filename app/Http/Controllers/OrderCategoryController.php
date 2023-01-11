<?php

namespace App\Http\Controllers;
use App\Models\OrderCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderCategoryController extends Controller {

	public function index() {

		$categories = OrderCategory::all();
		return view('pages.order_category.index', compact('categories'));
	}

	public function create() {
		return view('pages.order_category.add');
	}

	public function store(Request $request) {

		$validated = $request->validate([
			'order_type' => 'required',
		]);

		OrderCategory::create(
			['type' => $request->order_type]
		);

		Session::flash('success', __('Successfully Added'));
		return redirect()->back();
	}

	public function destroy(OrderCategory $order_category) {
		$order_category->delete();
		Session::flash('success', __('Successfully Deleted'));
        return redirect()->back();
	}
}
