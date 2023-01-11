<?php

namespace App\Http\Controllers;

use App\Models\ManualFeedback;
use App\Models\Screenshot;

class ImageController extends Controller {
	public function store() {

		 $screenshot = new Screenshot();
		 $screenshot->id = 0;
		 $screenshot->exists = true;

		 $image = $screenshot->addMediaFromRequest('upload')->toMediaCollection('images');

		 return response()->json([
		 	'url' => $image->getUrl('thumb'),
		 ]);

	}

    public function store_feedback() {

		 $manual_feedback = new ManualFeedback();
        $manual_feedback->id = 0;
        $manual_feedback->exists = true;

		 $image = $manual_feedback->addMediaFromRequest('upload')->toMediaCollection('feedback_images');

		 return response()->json([
		 	'url' => $image->getUrl('thumb'),
		 ]);

	}
}
