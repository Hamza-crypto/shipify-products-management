<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Screenshot extends Model implements HasMedia {
	use HasFactory, InteractsWithMedia;

	protected $fillable = [
		'order_id',
		'assist_note',
		'user_note',
		'image',
	];

	public function registerMediaConversions(Media $media = null): void{
		$this->addMediaConversion('thumb')
			->width(500)
			->height(400);

	}

}
