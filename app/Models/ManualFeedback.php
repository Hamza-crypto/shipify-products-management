<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ManualFeedback extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'card_number',
        'month',
        'year',
        'cvc',
        'amount',
        'user_note',
        'assistant_note',
        'status',
    ];

    public function registerMediaConversions(Media $media = null): void{
        $this->addMediaConversion('thumb')
        ->width(700)
            ->height(400);
 

    }
}
